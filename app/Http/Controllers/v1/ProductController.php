<?php

namespace App\Http\Controllers\v1;

use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ValidatorController;
use App\Http\Resources\v1\ProductResource;
use App\Models\v1\Brand;
use App\Models\v1\Category;
use App\Models\v1\Material;
use App\Models\v1\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response as ResponseHttp;

class ProductController extends Controller
{
    public function filtersProduct()
    {
        try {
            $list = [
                'brands' => Brand::where('estatus_crud', 'C')
                    ->orderBy('nombre', 'ASC')
                    ->get(),
                'materials' => Material::where('estatus_crud', 'C')
                    ->orderBy('nombre', 'ASC')
                    ->get(),
                'categories' => Category::where('estatus_crud', 'C')
                    ->orderBy('nombre', 'ASC')
                    ->get(),
            ];

            return response([
                'message' => 'Successful query',
                'data' => $list,
            ], ResponseHttp::HTTP_OK);

        } catch (CustomException $e) {
            return response([
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
    }

    public function autoCompletedProduct(Request $request)
    {
        try {

            ValidatorController::validatorData($request->info, [
                'word' => 'required',
            ]);

            $INFO = $request->info;
            $ListAutoCompletedClear = $ListAutoCompleted = [];
            $word = strtolower($INFO->word);

            $ListAutoCompleted = DB::connection('mysql2')
                ->select("SELECT LOWER(CONCAT(P.nombre,'~',P.clave,'~',M.nombre,'~',
                    UM.nombre,'~',C.nombre,'~',B.nombre)) AS WORD
                    FROM products AS P
                    INNER JOIN brands AS B ON P.brand_id=B.id
                    INNER JOIN materials AS M ON P.material_id=M.id
                    INNER JOIN messuares AS UM ON P.messuare_id=UM.id
                    INNER JOIN categories AS C ON P.category_id=C.id
                    WHERE P.estatus_crud='C' AND
                    P.is_web = 1 AND(
                    P.nombre  LIKE '%".$word."%' OR
                    P.clave   LIKE '%".$word."%' OR
                    M.nombre  LIKE '%".$word."%' OR
                    UM.nombre LIKE '%".$word."%' OR
                    C.nombre  LIKE '%".$word."%' OR
                    B.nombre  LIKE '%".$word."%')
                    ORDER BY P.nombre ASC"
                );

            foreach ($ListAutoCompleted as $itrAutoCompleted) {

                $listSplit1 = explode('~', $itrAutoCompleted->WORD);

                foreach ($listSplit1 as $itrSplit1) {
                    if (str_contains($itrSplit1, $word)) {
                        $pos = strpos($itrSplit1, $word);
                        if ($pos < strlen($word) && $pos) {
                            $ListAutoCompletedClear[] = str_replace(',', '', trim($itrSplit1, " \n\r\t\v\0"));
                        }

                        if (substr($itrSplit1, 0, strlen($word)) == $word) {
                            $ListAutoCompletedClear[] = str_replace(',', '', trim($itrSplit1, " \n\r\t\v\0"));
                        }

                        $listSplit2 = explode(' ', $itrSplit1);
                        foreach ($listSplit2 as $itrSplit2) {
                            if (substr($itrSplit2, 0, strlen($word)) == $word) {
                                $ListAutoCompletedClear[] = str_replace(',', '', trim($itrSplit2, " \n\r\t\v\0"));
                            }
                        }
                    }
                }
            }

            $ListAutoCompletedClear = array_filter(array_unique($ListAutoCompletedClear), function ($word) {
                return strlen($word) >= 4;
            });

            return response([
                'message' => 'Successful query',
                'data' => $ListAutoCompletedClear,
            ], ResponseHttp::HTTP_OK);

        } catch (CustomException $e) {
            return response([
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
    }

    public function listProduct(Request $request)
    {
        try {
            ValidatorController::validatorData($request->info, [
                'word' => 'required|string',
                'totalRecords' => 'required|integer',
                'offset' => 'required|integer',
                'limit' => 'required|integer',
                'orderby' => 'required|string|in:ASC,DESC',
            ]);

            $INFO = $request->info;
            $totalRecords = 0;

            $query = "SELECT P.id
            FROM products  P
            INNER JOIN brands  B ON P.brand_id=B.id
            INNER JOIN materials  M ON P.material_id=M.id
            INNER JOIN messuares  UM ON P.messuare_id=UM.id
            INNER JOIN categories  C ON P.category_id=C.id
            WHERE P.estatus_crud = 'C' AND
            P.is_web = 1 AND(
            P.nombre  LIKE '%".$INFO->word."%' OR
            P.clave   LIKE '%".$INFO->word."%' OR
            M.nombre  LIKE '%".$INFO->word."%' OR
            UM.nombre LIKE '%".$INFO->word."%' OR
            C.nombre  LIKE '%".$INFO->word."%' OR
            B.nombre  LIKE '%".$INFO->word."%')
            ORDER BY P.nombre ".$INFO->orderby;

            $list = [];
            $listDirty = DB::connection('mysql2')->select($query.'
            LIMIT '.(int) $INFO->limit.'
            OFFSET '.(int) $INFO->offset);

            foreach ($listDirty as $itr) {
                $product =
                    Product::with('brand', 'messuare', 'category', 'material')->
                        where('id', $itr->id)->
                        first();

                if($product)$list[] = new ProductResource($product);
            }

            if ($INFO->totalRecords == 1) {
                $totalRecords = count(
                    DB::connection('mysql2')
                        ->select($query)
                );
            }

            return response([
                'message' => 'Successful query',
                'data' => [
                    'list' => $list,
                    'totalRecords' => $totalRecords,
                ],
            ], ResponseHttp::HTTP_OK);

        } catch (CustomException $e) {
            return response([
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
    }

    public function detailProduct(Request $request)
    {
        try {

            ValidatorController::validatorData($request->info, [
                'id' => 'required',
            ]);

            $INFO = $request->info;

            $product = new ProductResource(
                Product::with('brand', 'messuare', 'category', 'material')
                    ->where('id', $INFO->id)
                    ->first()
            );

            return response([
                'message' => 'Successful query',
                'data' => [
                    'product' => $product,
                ],
            ], ResponseHttp::HTTP_OK);

        } catch (CustomException $e) {
            return response([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function productsByCategory(Request $request)
    {
        try {

            ValidatorController::validatorData($request->info, [
                'product_id' => 'required',
            ]);

            $INFO = $request->info;

            $product = Product::where('id', $INFO->product_id)->first();

            $list = ProductResource::collection(
                Product::with('brand', 'messuare', 'category', 'material')
                    ->where([
                        ['category_id', $product['category_id']],
                        ['id', '!=',$product['id']],
                        ['estatus_crud', 'C'],
                        ['is_web', 1]
                    ])
                    ->inRandomOrder()
                    ->limit(10)
                    ->get()
            );


            return response([
                'message' => 'Successful query',
                'data' => $list,
            ], ResponseHttp::HTTP_OK);

        } catch (CustomException $e) {
            return response([
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
    }
}
