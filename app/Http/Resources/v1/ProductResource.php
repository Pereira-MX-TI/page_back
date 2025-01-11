<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\v1\Description;
use App\Models\v1\FilePS;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'clave' => $this->clave,
            'nombre' => $this->nombre,
            'brand' => $this->brand,
            'category' => $this->category,
            'messuare' => $this->messuare,
            'material' => $this->material,
            'description' => Description::where('id',$this->description_id)->first(),
            'files' => FilePS::where([['register_id',$this->id],['register_type','P'],['estatus_crud','C'],['formato','IMG']])->orderBy('id','ASC')->get()
        ];
    }
}
