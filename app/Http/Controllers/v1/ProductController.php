<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\ProductResource;

use App\Models\v1\Product;
use App\Models\v1\Brand;
use App\Models\v1\Category;
use App\Models\v1\Material;

use Illuminate\Http\Request;
use Blocktrail\CryptoJSAES\CryptoJSAES;
use App\Http\Controllers\ValidatorController;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function autoCompletedProduct(Request $request)
    {
        $validation = ValidatorController::validatorGral($request,
        [   
            'word' => 'required',
            'opc' => 'required',
        ],1);

        if($validation['code']!=200)
            return response()->json($validation,$validation['code']);

        $data = $validation['data'];

        $ListAutoCompletedClear = $ListAutoCompleted = [];
        $data['word'] = strtolower($data['word']);

        switch($data['opc'])
        {
            case 0:
            {
                $ListAutoCompleted = DB::connection('mysql2')->select("SELECT LOWER(CONCAT(P.nombre,'~',P.clave,'~',M.nombre,'~',
                UM.nombre,'~',C.nombre,'~',B.nombre)) AS WORD FROM products AS P 
                INNER JOIN brands AS B ON P.brand_id=B.id 
                INNER JOIN materials AS M ON P.material_id=M.id 
                INNER JOIN messuares AS UM ON P.messuare_id=UM.id 
                INNER JOIN categories AS C ON P.category_id=C.id 
                WHERE P.estatus_crud='C' AND  
                P.is_web = 1 AND(
                P.nombre  LIKE '%".$data['word']."%' OR 
                P.clave   LIKE '%".$data['word']."%' OR 
                M.nombre  LIKE '%".$data['word']."%' OR 
                UM.nombre LIKE '%".$data['word']."%' OR 
                C.nombre  LIKE '%".$data['word']."%' OR 
                B.nombre  LIKE '%".$data['word']."%')
                ORDER BY P.nombre ASC");
            }break;
        }

        foreach($ListAutoCompleted as $itrAutoCompleted)
        {
            $listSplit1 = explode("~", $itrAutoCompleted->WORD);
            foreach($listSplit1 as $itrSplit1)
            {
                if(str_contains($itrSplit1,$data['word']))
                {
                    $pos = strpos($itrSplit1, $data['word']);
                    if($pos<strlen($data['word']) && $pos)
                        array_push($ListAutoCompletedClear,trim($itrSplit1," \n\r\t\v\0"));                             
                    
                    if(substr($itrSplit1,0,strlen($data['word']))==$data['word'])
                        array_push($ListAutoCompletedClear,trim($itrSplit1," \n\r\t\v\0"));

                    $listSpli2 = explode(" ",$itrSplit1);
                    foreach($listSpli2 as $itrSplit2)
                        if(substr($itrSplit2,0,strlen($data['word']))==$data['word'])
                            array_push($ListAutoCompletedClear,trim($itrSplit2," \n\r\t\v\0"));
                }
            }                                                   
        }

        $ListAutoCompletedClear = array_unique($ListAutoCompletedClear);

        return response()->json([
            'data'=>CryptoJSAES::encrypt(json_encode([
                'list'=>$ListAutoCompletedClear]),env('APP_KEY')),
            'message'=>'',
            'error'=>false,'code'=>200]);
    }
    
    public function getListProduct(Request $request)
    {    
        $validation = ValidatorController::validatorGral($request,
        [   
            'opc' => 'required',
            'filters' => 'required',
            'quantity' => 'required',
            'orderby' => 'required'
        ],1);

        if($validation['code']!=200)
            return response()->json($validation,$validation['code']);

        $data = $validation['data'];
        $filters = null;
        $list = null;
        $quantity = null;

        switch($data['opc'])
        {
            case 0:
            {
                $list = ProductResource::collection(Product::with('brand','messuare','category','material')->
                where([['estatus_crud','C'],['is_web',1]])->
                orderBy('nombre',$data['orderby'])->get());
            }break;

            case 1:
            {
                $validation = ValidatorController::validatorSimple($data,
                [   
                    'offset' => 'required',
                    'limit' => 'required'
                ],1);
        
                if($validation['code']!=200)
                    return response()->json($validation,$validation['code']);

                $list = ProductResource::collection(Product::with('brand','messuare','category','material')->
                where([['estatus_crud','C'],['is_web',1]])->
                orderBy('nombre',$data['orderby'])->
                offset($data['offset'])->
                limit($data['limit'])->get());
            }break;
        }

        if($data['filters']==1)
        {
            $filters =[
                'brands'=> Brand::where('estatus_crud','C')->orderBy('nombre','ASC')->get(),
                'materials'=> Material::where('estatus_crud','C')->orderBy('nombre','ASC')->get(),
                'categories'=> Category::where('estatus_crud','C')->orderBy('nombre','ASC')->get()
            ];
        }

        if($data['quantity']==1)
        {
            $quantity = count(DB::connection('mysql2')->select(
            "SELECT P.id FROM products AS P WHERE P.estatus_crud = 'C' AND P.is_web = 1"));
        }

        return response()->json(["data" => CryptoJSAES::encrypt(json_encode([
            'list'=>$list,
            'filters'=>$filters,
            'quantity'=>$quantity
        ]), strval(env('APP_KEY')))], 200);
    }

    public function getProduct(Request $request)
    {    
        $validation = ValidatorController::validatorGral($request,
        [   
            'id' => 'required',
            'publicity' => 'required'
        ],1);

        if($validation['code']!=200)
            return response()->json($validation,$validation['code']);

        $data = $validation['data'];

        $publicity = ['listProduct1'=>[],'listProduct2'=>[]];
        $product = new ProductResource(Product::with('brand','messuare','category','material')->
        where('id',$data['id'])->first());

        if($data['publicity']==1)
        {
            $publicity['listProduct1'] = ProductResource::collection(Product::with('brand','messuare','category','material')->
            where([['category_id',$product['category']['id']],['id','!=',$product['id']],['estatus_crud','C'],['is_web',1]])->
            orderBy('id',rand(0,1)==1?'ASC':'DESC')->
            offset(rand(1,3))->
            limit(3)->get());

            $publicity['listProduct2'] = ProductResource::collection(Product::with('brand','messuare','category','material')->
            where([['brand_id',$product['brand']['id']],['id','!=',$product['id']],['estatus_crud','C'],['is_web',1]])->
            orderBy('id',rand(0,1)==1?'ASC':'DESC')->
            offset(rand(1,3))->
            limit(15)->get());
        }

        return response()->json(["data" => CryptoJSAES::encrypt(json_encode([
            'product'=>$product,
            'publicity'=>$publicity
        ]), strval(env('APP_KEY')))], 200);
    }

    public function searchListProduct(Request $request)
    {    
        $validation = ValidatorController::validatorGral($request,
        [   
            'opc' => 'required',
            'search' => 'required',
            'quantity' => 'required',
            'filters' => 'required',
            'orderby' => 'required'
        ],1);

        if($validation['code']!=200)
            return response()->json($validation,$validation['code']);

        $data = $validation['data'];
        $list = null;
        $quantity = null;
        $filters = null;

        switch($data['opc'])
        {
            case 0:
            {
                $list = DB::connection('mysql2')->select("SELECT  P.id AS id 
                FROM products AS P 
                INNER JOIN brands AS B ON P.brand_id=B.id 
                INNER JOIN materials AS M ON P.material_id=M.id 
                INNER JOIN messuares AS UM ON P.messuare_id=UM.id 
                INNER JOIN categories AS C ON P.category_id=C.id 
                WHERE P.estatus_crud='C' AND  
                P.is_web = 1 AND  
                (P.id = '".$data['search']."' OR 
                 P.nombre  LIKE '%".$data['search']."%' OR 
                (M.nombre  LIKE '%".$data['search']."%' AND M.estatus_crud='C') OR 
                (B.nombre  LIKE '%".$data['search']."%' AND B.estatus_crud='C') OR 
                (UM.nombre LIKE '%".$data['search']."%' AND UM.estatus_crud='C') OR 
                (C.nombre  LIKE '%".$data['search']."%' AND C.estatus_crud='C'))
                ORDER BY P.nombre");
            }break;

            case 1:
            {
                $validation = ValidatorController::validatorSimple($data,
                [   
                    'offset' => 'required',
                    'limit' => 'required'
                ],1);
        
                if($validation['code']!=200)
                    return response()->json($validation,$validation['code']);

                $list = DB::connection('mysql2')->select("SELECT  P.id AS id 
                FROM products AS P 
                INNER JOIN brands AS B ON P.brand_id=B.id 
                INNER JOIN materials AS M ON P.material_id=M.id 
                INNER JOIN messuares AS UM ON P.messuare_id=UM.id 
                INNER JOIN categories AS C ON P.category_id=C.id 
                WHERE P.estatus_crud='C' AND  
                P.is_web = 1 AND  
                (P.id = '".$data['search']."' OR 
                 P.nombre  LIKE '%".$data['search']."%' OR 
                (M.nombre  LIKE '%".$data['search']."%' AND M.estatus_crud='C') OR 
                (B.nombre  LIKE '%".$data['search']."%' AND B.estatus_crud='C') OR 
                (UM.nombre LIKE '%".$data['search']."%' AND UM.estatus_crud='C') OR 
                (C.nombre  LIKE '%".$data['search']."%' AND C.estatus_crud='C'))
                ORDER BY P.nombre ".$data['orderby'].
                " LIMIT ".$data['limit']." OFFSET ".$data['offset']);
            }break;

            case 2:
            {
                $list = DB::connection('mysql2')->select(
                "SELECT  P.id AS id FROM products AS P 
                INNER JOIN categories AS C ON P.category_id=C.id 
                WHERE P.estatus_crud='C' AND 
                P.is_web = 1 AND 
                C.nombre = '".$data['search']."'");
            }break;

            case 3:
            {
                $validation = ValidatorController::validatorSimple($data,
                [   
                    'offset' => 'required',
                    'limit' => 'required'
                ],1);
        
                if($validation['code']!=200)
                    return response()->json($validation,$validation['code']);

                $list = DB::connection('mysql2')->select(
                "SELECT  P.id AS id FROM products AS P 
                INNER JOIN categories AS C ON P.category_id=C.id 
                WHERE P.estatus_crud='C' AND 
                P.is_web = 1 AND 
                C.nombre = '".$data['search']."'
                ORDER BY P.nombre ".$data['orderby'].
                " LIMIT ".$data['limit']." OFFSET ".$data['offset']);
            }break;

            case 4:
            {
                $list = DB::connection('mysql2')->select(
                "SELECT  P.id AS id FROM products AS P 
                INNER JOIN brands AS B ON P.brand_id=B.id 
                WHERE P.estatus_crud='C' AND 
                P.is_web = 1 AND 
                B.nombre = '".$data['search']."'");
            }break;

            case 5:
            {
                $validation = ValidatorController::validatorSimple($data,
                [   
                    'offset' => 'required',
                    'limit' => 'required'
                ],1);
        
                if($validation['code']!=200)
                    return response()->json($validation,$validation['code']);

                $list = DB::connection('mysql2')->select("SELECT  P.id AS id 
                FROM products AS P 
                INNER JOIN brands AS B ON P.brand_id=B.id 
                WHERE P.estatus_crud='C' AND  
                P.is_web = 1 AND
                B.nombre = '".$data['search']."'
                ORDER BY P.nombre ".$data['orderby'].
                " LIMIT ".$data['limit']." OFFSET ".$data['offset']);
            }break;

            case 6:
            {
                $list = DB::connection('mysql2')->select(
                "SELECT  P.id AS id FROM products AS P 
                INNER JOIN materials AS M ON P.material_id=M.id 
                WHERE P.estatus_crud='C' AND 
                P.is_web = 1 AND 
                M.nombre = '".$data['search']."'");
            }break;

            case 7:
            {
                $validation = ValidatorController::validatorSimple($data,
                [   
                    'offset' => 'required',
                    'limit' => 'required'
                ],1);
        
                if($validation['code']!=200)
                    return response()->json($validation,$validation['code']);

                $list = DB::connection('mysql2')->select(
                "SELECT  P.id AS id 
                FROM products AS P 
                INNER JOIN materials AS M ON P.material_id=M.id 
                WHERE P.estatus_crud='C' AND 
                P.is_web = 1 AND
                M.nombre = '".$data['search']."'
                ORDER BY P.nombre ".$data['orderby'].
                " LIMIT ".$data['limit']." OFFSET ".$data['offset']);
            }break;
        }

        $listId = array();
        foreach ($list as $itrId)
            array_push($listId,$itrId->id);

        $list = ProductResource::collection(Product::with('brand','messuare','category','material')->
        whereIn('id',$listId)->
        orderBy('nombre',$data['orderby'])->get());

        if($data['filters']==1)
        {
            $filters =[
                'brands'    => Brand::where('estatus_crud','C')->orderBy('nombre','ASC')->get(),
                'materials' => Material::where('estatus_crud','C')->orderBy('nombre','ASC')->get(),
                'categories'=> Category::where('estatus_crud','C')->orderBy('nombre','ASC')->get()
            ];
        }

        if($data['quantity']==1)
        {
            switch($data['opc'])
            {
                case 1:
                {
                    $quantity = count(DB::connection('mysql2')->select(
                    "SELECT  P.id AS id FROM products AS P 
                    INNER JOIN brands AS B ON P.brand_id=B.id 
                    INNER JOIN materials AS M ON P.material_id=M.id 
                    INNER JOIN messuares AS UM ON P.messuare_id=UM.id 
                    INNER JOIN categories AS C ON P.category_id=C.id 
                    WHERE P.estatus_crud='C' AND
                    P.is_web = 1 AND  
                    (P.id = '".$data['search']."' OR 
                     P.nombre  LIKE '%".$data['search']."%' OR 
                    (M.nombre  LIKE '".$data['search']."%' AND M.estatus_crud='C') OR 
                    (B.nombre  LIKE '".$data['search']."%' AND B.estatus_crud='C') OR 
                    (UM.nombre LIKE '".$data['search']."%' AND UM.estatus_crud='C') OR 
                    (C.nombre  LIKE '".$data['search']."%' AND C.estatus_crud='C'))"));
                }break;

                case 2:
                {
                    $quantity = count(DB::connection('mysql2')->select(
                    "SELECT  P.id AS id FROM products AS P 
                    INNER JOIN categories AS C ON P.category_id=C.id 
                    WHERE P.estatus_crud='C' AND
                    P.is_web = 1 AND 
                    C.nombre = '".$data['search']."'"));
                }break;

                case 3:
                {
                    $quantity = count(DB::connection('mysql2')->select(
                    "SELECT  P.id AS id FROM products AS P 
                    INNER JOIN categories AS C ON P.category_id=C.id 
                    WHERE P.estatus_crud='C' AND
                    P.is_web = 1 AND  
                    C.nombre = '".$data['search']."'"));
                }break;

                case 4:
                {
                    $quantity = count(DB::connection('mysql2')->select(
                    "SELECT  P.id AS id FROM products AS P
                    INNER JOIN brands AS B ON P.brand_id=B.id  
                    WHERE P.estatus_crud='C' AND
                    P.is_web = 1 AND  
                    B.nombre = '".$data['search']."'"));
                }break;

                case 5:
                {
                    $quantity = count(DB::connection('mysql2')->select(
                    "SELECT  P.id AS id FROM products AS P
                    INNER JOIN brands AS B ON P.brand_id=B.id  
                    WHERE P.estatus_crud='C' AND
                    P.is_web = 1 AND  
                    B.nombre = '".$data['search']."'"));
                }break;

                case 6:
                {
                    $quantity = count(DB::connection('mysql2')->select(
                    "SELECT  P.id AS id FROM products AS P
                    INNER JOIN materials AS M ON P.material_id=M.id  
                    WHERE P.estatus_crud='C' AND
                    P.is_web = 1 AND 
                    M.nombre = '".$data['search']."'"));
                }break;

                case 7:
                {
                    $quantity = count(DB::connection('mysql2')->select(
                    "SELECT  P.id AS id FROM products AS P
                    INNER JOIN materials AS M ON P.material_id=M.id  
                    WHERE P.estatus_crud='C' AND
                    P.is_web = 1 AND  
                    M.nombre = '".$data['search']."'"));
                }break;
            }
        }

        return response()->json(["data" => CryptoJSAES::encrypt(json_encode([
            'list'=>$list,
            'filters'=>$filters,
            'quantity'=>$quantity
        ]), strval(env('APP_KEY')))], 200);
    }
}
