<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
            'discounted_price' => $this->discounted_price,
            'final_price' => $this->final_price,
            'has_discount' => $this->has_discount,
            'discount_percentage' => $this->discount_percentage,
            'formatted_price' => $this->formatted_price,
            'formatted_final_price' => $this->formatted_final_price,
            'stock_quantity' => $this->stock_quantity,
            'in_stock' => $this->in_stock,
            'sku' => $this->sku,
            'images' => $this->images ?? [],
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,
            'ingredients' => $this->ingredients,
            'spice_level' => $this->spice_level,
            'preparation_time' => $this->preparation_time,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
