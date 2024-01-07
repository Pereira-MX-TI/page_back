<?php

namespace App\Http\Controllers\v1;

use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\ValidatorController;
use App\Models\v1\CarouselH;
use App\Models\v1\Product;
use App\Models\v1\File;
use App\Http\Resources\v1\CarouselResource;
use App\Http\Resources\v1\ProductResource;
use Symfony\Component\HttpFoundation\Response as ResponseHttp;

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

            $list[] = $newDetail;
        }

        return $list;
    }

    public function getListCarousel(Request $request)
    {
        try{
            ValidatorController::validatorData($request->info, [
                'listId' => 'required|array',
                'listId.*' => 'integer' // Valída que todos los elementos del array sean enteros
            ]);

            $carousel = CarouselResource::collection(CarouselH::whereIn('id',$request->info->listId)->get());

            return response([
                'message' => 'Successful query',
                'data' => $carousel,
            ], ResponseHttp::HTTP_OK);

        } catch (CustomException $e) {
            return response([
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
    }

    public function getCarousel(Request $request)
    {
        try {
            ValidatorController::validatorData($request->info, [
                'id' => 'required|integer'
            ]);

            $carousel = CarouselH::where('id', $request->info->id)->first();

            if (!$carousel){
                throw new CustomException('There are no search results', ResponseHttp::HTTP_BAD_REQUEST);
            }

            return response([
                'message' => 'Successful query',
                'data' => new CarouselResource($carousel),
            ], ResponseHttp::HTTP_OK);

        } catch (CustomException $e) {
            return response([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function getPublicity(Request $request)
    {
        try{

            ValidatorController::validatorData($request->info, [
                'listProduct' => 'array',
                'listProduct.*' => 'integer' // Valída que todos los elementos del array sean enteros
            ]);
            $data = $request->info;

            if(!isset($data->listProduct))
            {
                return response()->json([
                    'message' => 'Successful query',
                    "data" => ['carrousel1'=>[],'carrousel2'=>[]],
                    ], ResponseHttp::HTTP_OK);
            }

            if(count($data->listProduct) != 0)
            {
                $publicity['carrousel1'] = ProductResource::collection(
                    Product::with('brand','messuare','category','material')
                        ->whereNotIn("id",$data->listProduct)
                        ->where([['estatus_crud','C'],['is_web',1]])
                        ->limit(12)
                        ->orderBy('nombre',rand(0,1)==1?'ASC':'DESC')
                        ->get()
                );
            } else {
                $publicity['carrousel1'] = ProductResource::collection(
                    Product::with('brand','messuare','category','material')
                        ->where([['estatus_crud','C'],['is_web',1],['category_id',8]])
                        ->limit(12)
                        ->orderBy('nombre',rand(0,1)==1?'ASC':'DESC')
                        ->get()
                );
            }

            return response([
                'message' => 'Successful query',
                'data' => $publicity,
            ], ResponseHttp::HTTP_OK);

        } catch (CustomException $e) {
            return response([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
