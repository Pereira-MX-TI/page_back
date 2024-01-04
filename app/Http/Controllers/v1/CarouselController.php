<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Blocktrail\CryptoJSAES\CryptoJSAES;
use App\Http\Controllers\ValidatorController;
use Illuminate\Support\Facades\DB;
use App\Models\v1\CarouselH;
use App\Models\v1\Product;
use App\Models\v1\File;

use App\Http\Resources\v1\CarouselResource;
use App\Http\Resources\v1\ProductResource;

class CarouselController extends Controller
{
    static public function detailCarousel($data)
    {
        $list = array();
        foreach($data as $itr)
        {
            $newDetail = null;
            if($itr['type_register']=='P')
            {
                $newDetail =[
                    'id' => $itr['id'],
                    'product' => new ProductResource(Product::with('brand','messuare','category','material')->where('id',$itr['register_id'])->first())
                ];
            }
            else
            {
                $newDetail =[
                    'id' => $itr['id'],
                    'direccion' =>(File::where('id',$itr['register_id'])->first())['address'],
                    'link' => $itr['link']
                ];
            }

            array_push($list,$newDetail);
        }

        return $list;
    }

    public function getListCarousel(Request $request)
    {    
        $validation = ValidatorController::validatorGral($request,
        [   
            'listId' => 'required'
        ],1);

        if($validation['code']!=200)
            return response()->json($validation,$validation['code']);

        $data = $validation['data'];

        return response()->json(["data" => CryptoJSAES::encrypt(json_encode([
            'list'=>CarouselResource::collection(CarouselH::whereIn('id',$data['listId'])->get())
        ]), strval(env('APP_KEY')))], 200);
    }

    public function getCarousel(Request $request)
    {    
        $validation = ValidatorController::validatorGral($request,
        [   
            'id' => 'required'
        ],1);

        if($validation['code']!=200)
            return response()->json($validation,$validation['code']);

        $data = $validation['data'];

        return response()->json(["data" => CryptoJSAES::encrypt(json_encode([
            'carousel'=>new CarouselResource(CarouselH::where('id',$data['id'])->first())
        ]), strval(env('APP_KEY')))], 200);
    }

    public function getPublicity(Request $request)
    {    
        $validation = ValidatorController::validatorGral($request,[],1);

        if($validation['code']!=200)
            return response()->json($validation,$validation['code']);

        $data = $validation['data'];
        $publicity = ['carrousel1'=>[],'carrousel2'=>[]];

        if(isset($data['listProduct']))
        {
            if(count($data['listProduct'])!=0)
            {
    
                $publicity['carrousel1']= ProductResource::collection(
                    Product::with('brand','messuare','category','material')->
                    whereNotIn("id",$data['listProduct'])->
                    where([['estatus_crud','C'],['is_web',1]])->
                    limit(12)->
                    orderBy('nombre',rand(0,1)==1?'ASC':'DESC')->get()
                );
            }
            else
            {
                $publicity['carrousel1']= ProductResource::collection(
                    Product::with('brand','messuare','category','material')->
                    where([['estatus_crud','C'],['is_web',1],['category_id',8]])->
                    limit(12)->
                    orderBy('nombre',rand(0,1)==1?'ASC':'DESC')->get()
                ); 
            }
        }
                
        return response()->json(["data" => CryptoJSAES::encrypt(json_encode($publicity), strval(env('APP_KEY')))], 200);
    }
}
