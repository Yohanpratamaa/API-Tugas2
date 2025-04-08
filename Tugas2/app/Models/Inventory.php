<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventory extends Model
{
    protected $timezone = 'Asia/Jakarta';
    use HasFactory, HasUuids;

    protected $fillable = [
        'id',
        'name',
        'quantity',
        'unit',
        'item_status',
        'entry_date',
        'category',
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'entry_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($inventory) {
            if (empty($inventory->id)) {
                $inventory->id = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }
}
