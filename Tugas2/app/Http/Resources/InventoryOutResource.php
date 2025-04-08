<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryOutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'inventory_id' => $this->inventory_id,
            'inventory_name' => $this->inventory->name ?? null,
            'category' => $this->inventory->category ?? null,
            'destination' => $this->destination,
            'unit_price' => $this->unit_price,
            'drop_out_date' => $this->drop_out_date,
            'quantity' => $this->quantity,
            'item_status ' => $this->item_status,
            'document' => $this->document ? asset('storage/' . $this->document) : null,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
