<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Backup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BackupController extends Controller
{
    use AuthorizesRequests;

    /**
     * عرض قائمة النسخ الاحتياطية
     */
    public function index()
    {
        $this->authorize('manage-backups');

        $backups = Backup::with('creator')
            ->latest()
            ->paginate(20);

        // إحصائيات
        $stats = [
            'total' => Backup::count(),
            'total_size' => Backup::sum('size'),
            'automatic' => Backup::where('type', 'automatic')->count(),
            'manual' => Backup::where('type', 'manual')->count(),
        ];

        return view('dashboard.backups.index', compact('backups', 'stats'));
    }

    /**
     * إنشاء نسخة احتياطية يدوياً
     */
    public function create(Request $request)
    {
        $this->authorize('manage-backups');

        try {
            Artisan::call('db:backup', ['--manual' => true]);
            
            $output = Artisan::output();
            
            return back()->with('success', 'تم إنشاء النسخة الاحتياطية بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', 'فشل إنشاء النسخة الاحتياطية: ' . $e->getMessage());
        }
    }

    /**
     * تنزيل نسخة احتياطية
     */
    public function download(Backup $backup)
    {
        $this->authorize('manage-backups');

        if (!$backup->fileExists()) {
            return back()->with('error', 'ملف النسخة الاحتياطية غير موجود');
        }

        $filePath = storage_path('app/backups/' . $backup->path);
        
        return response()->download($filePath, $backup->filename);
    }

    /**
     * حذف نسخة احتياطية
     */
    public function destroy(Backup $backup)
    {
        $this->authorize('manage-backups');

        try {
            // حذف الملف
            if ($backup->fileExists()) {
                Storage::disk('backups')->delete($backup->path);
            }
            
            // حذف السجل
            $backup->delete();

            return back()->with('success', 'تم حذف النسخة الاحتياطية بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', 'فشل حذف النسخة الاحتياطية: ' . $e->getMessage());
        }
    }

    /**
     * استعادة نسخة احتياطية
     */
    public function restore(Backup $backup)
    {
        $this->authorize('manage-backups');

        if (!$backup->fileExists()) {
            return back()->with('error', 'ملف النسخة الاحتياطية غير موجود');
        }

        try {
            $connection = DB::connection();
            $driver = config('database.default');
            $config = config("database.connections.{$driver}");
            $filePath = storage_path('app/backups/' . $backup->path);

            // تعطيل فحص المفاتيح الخارجية مؤقتاً
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            switch ($driver) {
                case 'mysql':
                case 'mariadb':
                    $this->restoreMySQL($config, $filePath);
                    break;
                
                case 'pgsql':
                    $this->restorePostgreSQL($config, $filePath);
                    break;
                
                case 'sqlite':
                    $this->restoreSQLite($config, $filePath);
                    break;
                
                default:
                    throw new \Exception("نوع قاعدة البيانات غير مدعوم: {$driver}");
            }

            // إعادة تفعيل فحص المفاتيح الخارجية
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            return back()->with('success', 'تم استعادة النسخة الاحتياطية بنجاح');
        } catch (\Exception $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            return back()->with('error', 'فشل استعادة النسخة الاحتياطية: ' . $e->getMessage());
        }
    }

    /**
     * استعادة MySQL
     */
    protected function restoreMySQL(array $config, string $filePath): void
    {
        $command = sprintf(
            'mysql --host=%s --port=%s --user=%s --password=%s %s < %s 2>&1',
            escapeshellarg($config['host']),
            escapeshellarg($config['port']),
            escapeshellarg($config['username']),
            escapeshellarg($config['password']),
            escapeshellarg($config['database']),
            escapeshellarg($filePath)
        );

        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            throw new \Exception('فشل استعادة قاعدة البيانات: ' . implode("\n", $output));
        }
    }

    /**
     * استعادة PostgreSQL
     */
    protected function restorePostgreSQL(array $config, string $filePath): void
    {
        putenv("PGPASSWORD={$config['password']}");
        
        $command = sprintf(
            'psql -h %s -p %s -U %s -d %s -f %s 2>&1',
            escapeshellarg($config['host']),
            escapeshellarg($config['port']),
            escapeshellarg($config['username']),
            escapeshellarg($config['database']),
            escapeshellarg($filePath)
        );

        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            throw new \Exception('فشل استعادة قاعدة البيانات: ' . implode("\n", $output));
        }
    }

    /**
     * استعادة SQLite
     */
    protected function restoreSQLite(array $config, string $filePath): void
    {
        $databasePath = $config['database'];
        
        if (!file_exists($filePath)) {
            throw new \Exception("ملف النسخة الاحتياطية غير موجود: {$filePath}");
        }

        // عمل نسخة احتياطية من الملف الحالي قبل الاستعادة
        if (file_exists($databasePath)) {
            $backupPath = $databasePath . '.before_restore_' . date('Y-m-d_H-i-s');
            copy($databasePath, $backupPath);
        }

        if (!copy($filePath, $databasePath)) {
            throw new \Exception('فشل نسخ ملف قاعدة البيانات');
        }
    }
}

