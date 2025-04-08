<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InventoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'location' => $this->location,
            'unit_price' => $this->unit_price,
            'quantity' => $this->quantity,
            'unit' => $this->unit,
            'minimum' => $this->minimum,
            'stock_status' => $this->stock_status,
            'item_status' => $this->item_status,
            'total_price' => $this->total_price,
            'entry_date' => $this->entry_date ? $this->entry_date->format('Y-m-d') : null,
            'document_date' => $this->document_date ? $this->document_date->format('Y-m-d') : null,
            'date_of_manufacture' => $this->date_of_manufacture ? $this->date_of_manufacture->format('Y-m-d') : null,
            'date_of_expired' => $this->date_of_expired ? $this->date_of_expired->format('Y-m-d') : null,
            'source' => $this->source,
            'category' => $this->category,
            'condition' => $this->condition,
            'part_number' => $this->part_number,
            'document' => $this->document,
            'image' => $this->image,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
