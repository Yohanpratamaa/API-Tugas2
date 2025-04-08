<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InventoryOut extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'inventory_id',
        'drop_out_date',
        'quantity',
        'item_status',
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}
