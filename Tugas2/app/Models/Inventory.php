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
        'location',
        'unit_price',
        'quantity',
        'unit',
        'minimum',
        'stock_status',
        'item_status',
        'total_price',
        'entry_date',
        'document_date',
        'date_of_manufacture',
        'date_of_expired',
        'source',
        'category',
        'condition',
        'part_number',
        'document',
        'image',
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'part_number' => 'array',
        'entry_date' => 'date',
        'document_date' => 'date',
        'date_of_manufacture' => 'date',
        'date_of_expired' => 'date'
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
