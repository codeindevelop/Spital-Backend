<?php

namespace Modules\Eshop\App\Http\Resources\Settings\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EshopProductSettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'redirect_to_cart' => $this->redirect_to_cart,
            'dynamic_cart' => $this->dynamic_cart,
            'placeholder_image' => $this->placeholder_image,
            'weight_unit' => $this->weight_unit,
            'dimensions_unit' => $this->dimensions_unit,
            'product_reviews' => $this->product_reviews,
            'only_owners_can_reviews' => $this->only_owners_can_reviews,
            'show_verified' => $this->show_verified,
            'star_rating_review' => $this->star_rating_review,
            'star_rating_review_required' => $this->star_rating_review_required,
            'manage_stock' => $this->manage_stock,
            'hold_stock' => $this->hold_stock,
            'low_stock_notification' => $this->low_stock_notification,
            'out_of_stock_notification' => $this->out_of_stock_notification,
            'low_stock_threshold' => $this->low_stock_threshold,
            'out_of_stock_threshold' => $this->out_of_stock_threshold,
            'out_of_stock_visibility' => $this->out_of_stock_visibility,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
