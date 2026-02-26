<?php

namespace App\Models;

use App\Enums\TicketStatusesEnum;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Ticket extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'customer_id',
        'subject',
        'message',
        'status',
        'answered_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => TicketStatusesEnum::class,
            'answered_at' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments');
    }

    public function scopeCreatedSince(Builder $query, CarbonInterface $since): Builder
    {
        return $query->where('created_at', '>=', $since);
    }

    public function scopeCreatedBetween(Builder $query, CarbonInterface $from, CarbonInterface $to): Builder
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    public function scopeFilter(Builder $q, array $filters): Builder
    {
        return $q
            ->when($filters['status'] ?? null, fn ($qq, $v) => $qq->where('status', $v))
            ->when($filters['email'] ?? null, fn ($qq, $v) => $qq->whereHas('customer', fn ($c) => $c->where('email', 'ilike', "%{$v}%")))
            ->when($filters['phone'] ?? null, fn ($qq, $v) => $qq->whereHas('customer', fn ($c) => $c->where('phone', 'ilike', "%{$v}%")))
            ->when($filters['date_from'] ?? null, fn ($qq, $v) => $qq->whereDate('created_at', '>=', $v))
            ->when($filters['date_to'] ?? null, fn ($qq, $v) => $qq->whereDate('created_at', '<=', $v));
    }
}
