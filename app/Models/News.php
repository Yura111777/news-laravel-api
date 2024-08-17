<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;

class News extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'description', 'imgNewsPath'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeTitle(Builder $query, string $title): Builder
    {
        return $query->where('title', 'LIKE', '%' . $title . '%');
    }

    public function scopeLastMonth(Builder $query)
    {
        return $this->DateRangeFilter($query, now()->subMonth(), now());
    }
    public function scopeLast6Months(Builder $query)
    {
        return $this->DateRangeFilter($query, now()->subMonths(6), now());
    }

    private function DateRangeFilter(Builder $query, $from = null, $to = null)
    {
        if ($from && !$to) {
            $query->where('created_at', '>=', $from);
        } elseif (!$from && $to) {
            $query - where('created_at', '<=', $to);
        } elseif ($from && $to) {
            $query->whereBetween('created_at', [$from, $to]);
        }
    }
}
