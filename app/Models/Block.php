<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Block extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'complex_id',
        'name',
        'flat_quantity',
        'commercial_space_quantity',
        'status',
        'construction_date',
        'completion_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'boolean',
        'construction_date' => 'date',
        'completion_date' => 'date',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        //
    ];

    /**
     * Get the complex that owns the block.
     */
    public function complex(): BelongsTo
    {
        return $this->belongsTo(Complex::class);
    }

    /**
     * Scope a query to only include active blocks.
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('status', true);
    }

    /**
     * Scope a query to only include inactive blocks.
     */
    public function scopeInactive(Builder $query): void
    {
        $query->where('status', false);
    }

    /**
     * Check if the block is active.
     */
    public function isActive(): bool
    {
        return $this->status;
    }

    /**
     * Check if the block is inactive.
     */
    public function isInactive(): bool
    {
        return ! $this->status;
    }

    /**
     * Get the total space quantity.
     */
    public function getTotalSpaceQuantityAttribute(): int
    {
        return $this->flat_quantity + $this->commercial_space_quantity;
    }

    /**
     * Get the status badge color for Filament.
     */
    public function getStatusColorAttribute(): string
    {
        return $this->status ? 'success' : 'danger';
    }
}
