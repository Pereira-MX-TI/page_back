<?php

namespace App\Http\Resources\v1;
use App\Models\v1\CarouselD;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Controllers\v1\CarouselController;

class CarouselResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'details' =>  CarouselController::detailCarousel(CarouselD::where('carousel_id',$this->id)->get())
        ];
    }
}
