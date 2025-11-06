<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'query',
        'type',
        'user_id',
        'ip_address',
        'results_count',
    ];

    protected $casts = [
        'results_count' => 'integer',
    ];

    /**
     * علاقة مع المستخدم
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * تسجيل عملية بحث
     */
    public static function log($query, $type = 'product', $resultsCount = 0, $userId = null)
    {
        return self::create([
            'query' => $query,
            'type' => $type,
            'user_id' => $userId,
            'ip_address' => request()->ip(),
            'results_count' => $resultsCount,
        ]);
    }
}
