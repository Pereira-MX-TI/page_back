<?php

namespace App\Http\Controllers\v1;

use App\Exceptions\CustomException;
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
use Symfony\Component\HttpFoundation\Response as ResponseHttp;

class ProductController extends Controller
{
    public function autoCompletedProduct(Request $request)
    {
        try {
            ValidatorController::validatorData($request->info, [
                'word' => 'required|string',
                'opc' => 'required|integer',
            ]);


            $data = $request->info;
            $ListAutoCompletedClear = $ListAutoCompleted = [];
            $word = strtolower($data->word);

            if ($data->opc == 0) {
                {
                    $ListAutoCompleted = DB::connection('mysql2')
                        ->select("SELECT LOWER(CONCAT(P.nombre,'~',P.clave,'~',M.nombre,'~',
                            UM.nombre,'~',C.nombre,'~',B.nombre)) AS WORD FROM products AS P
                            INNER JOIN brands AS B ON P.brand_id=B.id
                            INNER JOIN materials AS M ON P.material_id=M.id
                            INNER JOIN messuares AS UM ON P.messuare_id=UM.id
                            INNER JOIN categories AS C ON P.category_id=C.id
                            WHERE P.estatus_crud='C' AND
                            P.is_web = 1 AND(
                            P.nombre  LIKE '%" . $word . "%' OR
                            P.clave   LIKE '%" . $word . "%' OR
                            M.nombre  LIKE '%" . $word . "%' OR
                            UM.nombre LIKE '%" . $word . "%' OR
                            C.nombre  LIKE '%" . $word . "%' OR
                            B.nombre  LIKE '%" . $word . "%')
                            ORDER BY P.nombre ASC"
                        );
                }
            }

            foreach ($ListAutoCompleted as $itrAutoCompleted) {

                $listSplit1 = explode("~", $itrAutoCompleted->WORD);

                foreach ($listSplit1 as $itrSplit1) {
                    if (str_contains($itrSplit1, $word)) {
                        $pos = strpos($itrSplit1, $word);
                        if ($pos < strlen($word) && $pos)
                            $ListAutoCompletedClear[] = trim($itrSplit1, " \n\r\t\v\0");

                        if (substr($itrSplit1, 0, strlen($word)) == $word)
                            $ListAutoCompletedClear[] = trim($itrSplit1, " \n\r\t\v\0");

                        $listSplit2 = explode(" ", $itrSplit1);
                        foreach ($listSplit2 as $itrSplit2)
                            if (substr($itrSplit2, 0, strlen($word)) == $word)
                                $ListAutoCompletedClear[] = trim($itrSplit2, " \n\r\t\v\0");
                    }
                }
            }

            $ListAutoCompletedClear = array_unique($ListAutoCompletedClear);

            return response()->json([
                'message' => 'Successful query',
                'error' => false,
                'code' => 200,
                'data' => ['list' => $ListAutoCompletedClear]
            ], ResponseHttp::HTTP_OK);

        } catch (CustomException $e) {
            return response([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function getListProduct(Request $request)
    {
        try {
            ValidatorController::validatorData($request->info, [
                'opc' => 'required|integer',
                'filters' => 'required|integer',
                'quantity' => 'required|integer',
                'orderby' => 'required|string|in:ASC,DESC'
            ]);

            $data = $request->info;
            $filters = $list = $quantity = null;

            switch ($data->opc) {
                case 0:
                    {
                        $list = ProductResource::collection(
                            Product::with('brand', 'messuare', 'category', 'material')
                            ->where([['estatus_crud', 'C'], ['is_web', 1]])
                            ->orderBy('nombre', $data->orderby)
                            ->get()
                        );
                    }
                    break;
                case 1:
                    {

                        ValidatorController::validatorData($request->info, [
                            'offset' => 'required|integer',
                            'limit' => 'required|integer'
                        ]);

                        $list = ProductResource::collection(
                            Product::with('brand', 'messuare', 'category', 'material')
                                ->where([['estatus_crud', 'C'], ['is_web', 1]])
                                ->orderBy('nombre', $data->orderby)
                                ->offset($data->offset)
                                ->limit($data->limit)
                                ->get()
                        );
                    }
                    break;
            }

            if ($data->filters == 1) {
                $filters = [
                    'brands' => Brand::where('estatus_crud', 'C')
                        ->orderBy('nombre', 'ASC')
                        ->get(),
                    'materials' => Material::where('estatus_crud', 'C')
                        ->orderBy('nombre', 'ASC')
                        ->get(),
                    'categories' => Category::where('estatus_crud', 'C')
                        ->orderBy('nombre', 'ASC')
                        ->get()
                ];
            }

            if ($data->quantity == 1) {
                $quantity = count(
                    DB::connection('mysql2')
                    ->select("SELECT P.id FROM products AS P WHERE P.estatus_crud = 'C' AND P.is_web = 1")
                );
            }

            return response()->json([
                'message' => 'Successful query',
                'list' => $list,
                'filters' => $filters,
                'quantity' => $quantity
            ], ResponseHttp::HTTP_OK);

        } catch (CustomException $e) {
            return response([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function getProduct(Request $request)
    {
        try {
            ValidatorController::validatorData($request->info, [
                'id' => 'required|integer',
                'publicity' => 'required|integer'
            ]);

            $data = $request->info;
            $publicity = ['listProduct1' => [], 'listProduct2' => []];

            $product = new ProductResource(
                Product::with('brand', 'messuare', 'category', 'material')
                    ->where('id', $data->id)
                    ->first()
            );

            if ($data->publicity == 1) {

                $publicity['listProduct1'] = ProductResource::collection(
                    Product::with('brand', 'messuare', 'category', 'material')
                        ->where([['category_id', $product['category']['id']], ['id', '!=', $product['id']], ['estatus_crud', 'C'], ['is_web', 1]])
                        ->orderBy('id', rand(0, 1) == 1 ? 'ASC' : 'DESC')
                        ->offset(rand(1, 3)) //TODO: duda que quieres hacer
                        ->limit(3) //TODO: duda que quieres hacer
                        ->get()
                );

                $publicity['listProduct2'] = ProductResource::collection(
                    Product::with('brand', 'messuare', 'category', 'material')
                        ->where([['brand_id', $product['brand']['id']], ['id', '!=', $product['id']], ['estatus_crud', 'C'], ['is_web', 1]])
                        ->orderBy('id', rand(0, 1) == 1 ? 'ASC' : 'DESC')
                        ->offset(rand(1, 3)) //TODO: duda que quieres hacer
                        ->limit(15) //TODO: duda que quieres hacer
                        ->get()
                );
            }

            return response()->json([
                'message' => 'Successful query',
                'product' => $product,
                'publicity' => $publicity
            ], ResponseHttp::HTTP_OK);

        } catch (CustomException $e) {
            return response([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function searchListProduct(Request $request)
    {
        try {

            ValidatorController::validatorData($request->info, [
                'opc' => 'required|integer',
                'search' => 'required|string',
                'quantity' => 'required|integer',
                'filters' => 'required',
                'orderby' => 'required'
            ]);

            $data = $request->info;
            $list = $quantity = $filters = null;

            switch ($data->opc) {
                case 0:
                    {
                        $list = DB::connection('mysql2')
                            ->select("SELECT  P.id AS id
                    FROM products AS P
                    INNER JOIN brands AS B ON P.brand_id=B.id
                    INNER JOIN materials AS M ON P.material_id=M.id
                    INNER JOIN messuares AS UM ON P.messuare_id=UM.id
                    INNER JOIN categories AS C ON P.category_id=C.id
                    WHERE P.estatus_crud='C' AND
                    P.is_web = 1 AND
                    (P.id = '" . $data->search. "' OR
                     P.nombre  LIKE '%" .$data->search . "%' OR
                    (M.nombre  LIKE '%" . $data->search . "%' AND M.estatus_crud='C') OR
                    (B.nombre  LIKE '%" . $data->search . "%' AND B.estatus_crud='C') OR
                    (UM.nombre LIKE '%" . $data->search . "%' AND UM.estatus_crud='C') OR
                    (C.nombre  LIKE '%" . $data->search . "%' AND C.estatus_crud='C'))
                    ORDER BY P.nombre");
                    }
                    break;

                case 1:
                    {
                        ValidatorController::validatorData($request->info, [
                            'offset' => 'required|integer',
                            'limit' => 'required|integer'
                        ]);


                        $list = DB::connection('mysql2')
                            ->select("SELECT  P.id AS id
                                        FROM products AS P
                                        INNER JOIN brands AS B ON P.brand_id=B.id
                                        INNER JOIN materials AS M ON P.material_id=M.id
                                        INNER JOIN messuares AS UM ON P.messuare_id=UM.id
                                        INNER JOIN categories AS C ON P.category_id=C.id
                                        WHERE P.estatus_crud='C' AND
                    P.is_web = 1 AND
                    (P.id = '" . $data->search . "' OR
                     P.nombre  LIKE '%" . $data->search . "%' OR
                    (M.nombre  LIKE '%" . $data->search . "%' AND M.estatus_crud='C') OR
                    (B.nombre  LIKE '%" . $data->search . "%' AND B.estatus_crud='C') OR
                    (UM.nombre LIKE '%" . $data->search . "%' AND UM.estatus_crud='C') OR
                    (C.nombre  LIKE '%" . $data->search . "%' AND C.estatus_crud='C'))
                    ORDER BY P.nombre " . $data->orderby .
                            " LIMIT " . $data->limit . " OFFSET " . $data->offset);
                    }
                    break;

                case 2:
                    {
                        $list = DB::connection('mysql2')
                            ->select("SELECT  P.id AS id FROM products AS P
                    INNER JOIN categories AS C ON P.category_id=C.id
                    WHERE P.estatus_crud='C' AND
                    P.is_web = 1 AND
                    C.nombre = '" . $data->search . "'");
                    }
                    break;

                case 3:
                    {
                        ValidatorController::validatorData($request->info, [
                            'offset' => 'required|integer',
                            'limit' => 'required|integer'
                        ]);

                        $list = DB::connection('mysql2')
                            ->select("SELECT  P.id AS id FROM products AS P
                    INNER JOIN categories AS C ON P.category_id=C.id
                    WHERE P.estatus_crud='C' AND
                    P.is_web = 1 AND
                    C.nombre = '" . $data->search . "'
                    ORDER BY P.nombre " . $data->orderby .
                            " LIMIT " . $data->limit . " OFFSET " . $data->offset);
                    }
                    break;

                case 4:
                    {
                        $list = DB::connection('mysql2')
                            ->select("SELECT  P.id AS id FROM products AS P
                    INNER JOIN brands AS B ON P.brand_id=B.id
                    WHERE P.estatus_crud='C' AND
                    P.is_web = 1 AND
                    B.nombre = '" . $data->search . "'");
                    }
                    break;

                case 5:
                    {
                        ValidatorController::validatorData($request->info, [
                            'offset' => 'required|integer',
                            'limit' => 'required|integer'
                        ]);

                        $list = DB::connection('mysql2')
                            ->select("SELECT  P.id AS id
                    FROM products AS P
                    INNER JOIN brands AS B ON P.brand_id=B.id
                    WHERE P.estatus_crud='C' AND
                    P.is_web = 1 AND
                    B.nombre = '" . $data->search . "'
                    ORDER BY P.nombre " . $data->orderby .
                            " LIMIT " . $data->limit . " OFFSET " . $data->offset);
                    }
                    break;

                case 6:
                    {
                        $list = DB::connection('mysql2')
                            ->select("SELECT  P.id AS id FROM products AS P
                    INNER JOIN materials AS M ON P.material_id=M.id
                    WHERE P.estatus_crud='C' AND
                    P.is_web = 1 AND
                    M.nombre = '" . $data->search . "'");
                    }
                    break;

                case 7:
                    {
                        ValidatorController::validatorData($request->info, [
                            'offset' => 'required|integer',
                            'limit' => 'required|integer'
                        ]);

                        $list = DB::connection('mysql2')
                            ->select("SELECT  P.id AS id
                    FROM products AS P
                    INNER JOIN materials AS M ON P.material_id=M.id
                    WHERE P.estatus_crud='C' AND
                    P.is_web = 1 AND
                    M.nombre = '" . $data->search . "'
                    ORDER BY P.nombre " . $data->orderby .
                            " LIMIT " . $data->limit . " OFFSET " . $data->offset);
                    }
                    break;
            }
            $listId = array();

            foreach ($list as $itrId)
                $listId[] = $itrId->id;

            $list = ProductResource::collection(Product::with('brand', 'messuare', 'category', 'material')
                ->whereIn('id', $listId)
                ->orderBy('nombre', $data->orderby)
                ->get());
            if ($data->filters == 1) {
                $filters = [
                    'brands' => Brand::where('estatus_crud', 'C')->orderBy('nombre', 'ASC')->get(),
                    'materials' => Material::where('estatus_crud', 'C')->orderBy('nombre', 'ASC')->get(),
                    'categories' => Category::where('estatus_crud', 'C')->orderBy('nombre', 'ASC')->get()
                ];
            }
            if ($data->quantity == 1) {
                switch ($data->opc) {
                    case 1:
                        {
                            $quantity = count(DB::connection('mysql2')
                                ->select(
                                "SELECT  P.id AS id FROM products AS P
                        INNER JOIN brands AS B ON P.brand_id=B.id
                        INNER JOIN materials AS M ON P.material_id=M.id
                        INNER JOIN messuares AS UM ON P.messuare_id=UM.id
                        INNER JOIN categories AS C ON P.category_id=C.id
                        WHERE P.estatus_crud='C' AND
                        P.is_web = 1 AND
                        (P.id = '" . $data->search . "' OR
                         P.nombre  LIKE '%" . $data->search . "%' OR
                        (M.nombre  LIKE '" . $data->search . "%' AND M.estatus_crud='C') OR
                        (B.nombre  LIKE '" . $data->search . "%' AND B.estatus_crud='C') OR
                        (UM.nombre LIKE '" . $data->search . "%' AND UM.estatus_crud='C') OR
                        (C.nombre  LIKE '" . $data->search . "%' AND C.estatus_crud='C'))"));
                        }
                        break;

                    case 2:
                        {
                            $quantity = count(DB::connection('mysql2')
                                ->select("SELECT  P.id AS id FROM products AS P
                                        INNER JOIN categories AS C ON P.category_id=C.id
                                        WHERE P.estatus_crud='C' AND
                                        P.is_web = 1 AND
                                        C.nombre = '" . $data->search . "'"));
                        }
                        break;

                    case 3:
                        {
                            $quantity = count(DB::connection('mysql2')
                                ->select("SELECT  P.id AS id FROM products AS P
                        INNER JOIN categories AS C ON P.category_id=C.id
                        WHERE P.estatus_crud='C' AND
                        P.is_web = 1 AND
                        C.nombre = '" . $data->search . "'"));
                        }
                        break;

                    case 4:
                        {
                            $quantity = count(DB::connection('mysql2')
                                ->select("SELECT  P.id AS id FROM products AS P
                                        INNER JOIN brands AS B ON P.brand_id=B.id
                                        WHERE P.estatus_crud='C' AND
                                        P.is_web = 1 AND
                                        B.nombre = '" . $data->search . "'"));
                        }
                        break;

                    case 5:
                        {
                            $quantity = count(DB::connection('mysql2')
                                ->select("SELECT  P.id AS id FROM products AS P
                        INNER JOIN brands AS B ON P.brand_id=B.id
                        WHERE P.estatus_crud='C' AND
                        P.is_web = 1 AND
                        B.nombre = '" . $data->search . "'"));
                        }
                        break;

                    case 6:
                        {
                            $quantity = count(DB::connection('mysql2')
                                ->select("SELECT  P.id AS id FROM products AS P
                        INNER JOIN materials AS M ON P.material_id=M.id
                        WHERE P.estatus_crud='C' AND
                        P.is_web = 1 AND
                        M.nombre = '" . $data->search . "'"));
                        }
                        break;

                    case 7:
                        {
                            $quantity = count(DB::connection('mysql2')
                                ->select("SELECT  P.id AS id FROM products AS P
                        INNER JOIN materials AS M ON P.material_id=M.id
                        WHERE P.estatus_crud='C' AND
                        P.is_web = 1 AND
                        M.nombre = '" . $data->search . "'"));
                        }
                        break;
                }
            }
//            return response()->json(["data" => CryptoJSAES::encrypt(json_encode([
//                'list' => $list,
//                'filters' => $filters,
//                'quantity' => $quantity
//            ]), strval(env('APP_KEY')))], 200);

            return response()->json([
                'message' => 'Successful query',
                'data' => compact('list', 'filters', 'quantity')
            ], ResponseHttp::HTTP_OK);

        } catch (CustomException $e) {
            return response([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
