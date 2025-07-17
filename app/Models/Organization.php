<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'brand_color',
        'is_active',
        'phone',
        'email',
    ];

    /**
     * Get the users for the organization.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
