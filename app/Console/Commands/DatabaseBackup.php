<?php

namespace App\Console\Commands;

use App\Models\Backup;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup {--manual=false}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'إنشاء نسخة احتياطية لقاعدة البيانات';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('بدء إنشاء النسخة الاحتياطية...');

        try {
            $connection = DB::connection();
            $driver = config('database.default');
            $config = config("database.connections.{$driver}");

            $backupFile = $this->createBackup($config, $driver);
            
            if ($backupFile) {
                // حفظ معلومات النسخة الاحتياطية في قاعدة البيانات
                $backup = Backup::create([
                    'created_by' => Auth::id(),
                    'filename' => basename($backupFile),
                    'path' => $backupFile,
                    'size' => Storage::disk('backups')->size($backupFile),
                    'type' => $this->option('manual') === 'true' ? 'manual' : 'automatic',
                ]);

                $this->info("✓ تم إنشاء النسخة الاحتياطية بنجاح: {$backup->filename}");
                $this->info("الحجم: " . $this->formatBytes($backup->size));
                
                // حذف النسخ القديمة (الاحتفاظ بـ 30 نسخة فقط)
                $this->cleanupOldBackups();

                return Command::SUCCESS;
            } else {
                $this->error('فشل إنشاء النسخة الاحتياطية');
                return Command::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error('حدث خطأ: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * إنشاء النسخة الاحتياطية
     */
    protected function createBackup(array $config, string $driver): ?string
    {
        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
        $filename = "backup_{$timestamp}.sql";
        
        // إنشاء مجلد النسخ الاحتياطي
        $backupPath = storage_path('app/backups');
        if (!file_exists($backupPath)) {
            mkdir($backupPath, 0755, true);
        }

        $filePath = $backupPath . '/' . $filename;

        switch ($driver) {
            case 'mysql':
            case 'mariadb':
                return $this->backupMySQL($config, $filePath);
            
            case 'pgsql':
                return $this->backupPostgreSQL($config, $filePath);
            
            case 'sqlite':
                return $this->backupSQLite($config, $filePath);
            
            default:
                $this->error("نوع قاعدة البيانات غير مدعوم: {$driver}");
                return null;
        }
    }

    /**
     * نسخ احتياطي لـ MySQL
     */
    protected function backupMySQL(array $config, string $filePath): ?string
    {
        $command = sprintf(
            'mysqldump --host=%s --port=%s --user=%s --password=%s %s > %s 2>&1',
            escapeshellarg($config['host']),
            escapeshellarg($config['port']),
            escapeshellarg($config['username']),
            escapeshellarg($config['password']),
            escapeshellarg($config['database']),
            escapeshellarg($filePath)
        );

        exec($command, $output, $returnVar);

        if ($returnVar === 0 && file_exists($filePath) && filesize($filePath) > 0) {
            return str_replace(storage_path('app'), '', $filePath);
        }

        $this->error('فشل نسخ قاعدة البيانات MySQL');
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        return null;
    }

    /**
     * نسخ احتياطي لـ PostgreSQL
     */
    protected function backupPostgreSQL(array $config, string $filePath): ?string
    {
        putenv("PGPASSWORD={$config['password']}");
        
        $command = sprintf(
            'pg_dump -h %s -p %s -U %s -d %s -f %s 2>&1',
            escapeshellarg($config['host']),
            escapeshellarg($config['port']),
            escapeshellarg($config['username']),
            escapeshellarg($config['database']),
            escapeshellarg($filePath)
        );

        exec($command, $output, $returnVar);

        if ($returnVar === 0 && file_exists($filePath) && filesize($filePath) > 0) {
            return str_replace(storage_path('app'), '', $filePath);
        }

        $this->error('فشل نسخ قاعدة البيانات PostgreSQL');
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        return null;
    }

    /**
     * نسخ احتياطي لـ SQLite
     */
    protected function backupSQLite(array $config, string $filePath): ?string
    {
        $databasePath = $config['database'];
        
        if (!file_exists($databasePath)) {
            $this->error("ملف قاعدة البيانات غير موجود: {$databasePath}");
            return null;
        }

        if (copy($databasePath, $filePath)) {
            return str_replace(storage_path('app'), '', $filePath);
        }

        $this->error('فشل نسخ قاعدة البيانات SQLite');
        return null;
    }

    /**
     * حذف النسخ القديمة (الاحتفاظ بـ 30 نسخة فقط)
     */
    protected function cleanupOldBackups(): void
    {
        $backups = Backup::orderBy('created_at', 'desc')->get();
        
        if ($backups->count() > 30) {
            $oldBackups = $backups->slice(30);
            
            foreach ($oldBackups as $backup) {
                // حذف الملف
                if (Storage::disk('backups')->exists($backup->path)) {
                    Storage::disk('backups')->delete($backup->path);
                }
                // حذف السجل
                $backup->delete();
            }
            
            $this->info("✓ تم حذف " . $oldBackups->count() . " نسخة قديمة");
        }
    }

    /**
     * تحويل الحجم إلى صيغة قابلة للقراءة
     */
    protected function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}

