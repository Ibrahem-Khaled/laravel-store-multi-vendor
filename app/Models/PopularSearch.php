<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PopularSearch extends Model
{
    use HasFactory;

    protected $fillable = [
        'query',
        'type',
        'search_count',
        'results_count',
        'last_searched_at',
    ];

    protected $casts = [
        'search_count' => 'integer',
        'results_count' => 'integer',
        'last_searched_at' => 'datetime',
    ];

    /**
     * تحديث أو إنشاء بحث شائع
     */
    public static function updateOrCreatePopular($query, $type = 'product', $resultsCount = 0)
    {
        $popular = self::firstOrNew([
            'query' => $query,
            'type' => $type,
        ]);

        $popular->search_count = ($popular->search_count ?? 0) + 1;
        $popular->results_count = $resultsCount;
        $popular->last_searched_at = now();
        $popular->save();

        return $popular;
    }

    /**
     * Scope للحصول على الأكثر بحثاً
     */
    public function scopeMostPopular($query, $type = null, $limit = 10)
    {
        $query->orderBy('search_count', 'desc')
            ->orderBy('last_searched_at', 'desc');

        if ($type) {
            $query->where('type', $type);
        }

        return $query->limit($limit);
    }
}
