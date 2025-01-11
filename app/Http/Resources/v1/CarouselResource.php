<?php

namespace App\Http\Resources\v1;
use App\Models\v1\CarouselD;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\v1\Product;
use App\Models\v1\File;
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
            // 'id' => $this->id ?? null,
            // 'name' => $this->name ?? null,
            // 'description' => $this->description ?? null,
            // 'details' =>  $this->detailCarousel(CarouselD::where('carousel_id',$this->id)->get())
        ];
    }

    public function detailCarousel($data)
    {
        $list = array();
        foreach($data as $itr)
        {
            $newDetail = null;
            if($itr['type_register']=='product')
            {
                $newDetail =[
                    'id' => $itr['id'],
                    // 'product' => new ProductResource(Product::with('brand','messuare','category','material')->where('id',$itr['register_id'])->first())
                ];
            }
            else
            {
                $newDetail =[
                    'id' => $itr['id'],
                    // 'url' =>(File::where('id',$itr['register_id'])->first())['url'],
                    'link' => $itr['link']
                ];
            }

            $list[] = $newDetail;
        }

        return $list;
    }
}
