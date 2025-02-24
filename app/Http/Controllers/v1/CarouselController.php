<?php

namespace App\Http\Controllers\v1;

use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ValidatorController;
use App\Http\Resources\v1\ProductResource;
use App\Models\v1\CarouselD;
use App\Models\v1\CarouselH;
use App\Models\v1\File;
use App\Models\v1\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseHttp;

class CarouselController extends Controller
{
    public function specificCarousel(Request $request)
    {
        try {

            ValidatorController::validatorData($request->info, [
                'type' => 'string', ]);
            $INFO = $request->info;

            $res = [
                'info' => null,
                'list' => [],
            ];

            switch ($INFO->type) {
                case 'publicity':
                    $res['info'] = CarouselH::where('id', 1)->first();
                    $list = CarouselD::where('carousel_id', $res['info']['id'])->get();

                    foreach ($list as $itr) {
                        $res['list'][] = [
                            'id' => $itr['id'],
                            'url' => (File::where('id', $itr['register_id'])->first())['url'],
                            'link' => $itr['link'],
                        ];
                    }
                    break;
                case 'product':
                    $res['info'] = CarouselH::where('id', 2)->first();
                    $list = CarouselD::where('carousel_id', $res['info']['id'])->get();

                    foreach ($list as $itr) {
                        $res['list'][] = [
                            'id' => $itr['id'],
                            'product' => new ProductResource(Product::with('brand', 'messuare', 'category', 'material')->where('id', $itr['register_id'])->first()),
                        ];
                    }
                    break;
                case 'water_meter':
                    $res['info'] = CarouselH::where('id', 3)->first();
                    $list = CarouselD::where('carousel_id', $res['info']['id'])->get();

                    foreach ($list as $itr) {
                        $res['list'][] = [
                            'id' => $itr['id'],
                            'product' => new ProductResource(Product::with('brand', 'messuare', 'category', 'material')->where('id', $itr['register_id'])->first()),
                        ];
                    }
                    break;
                case 'valve':
                    $res['info'] = CarouselH::where('id', 4)->first();
                    $list = CarouselD::where('carousel_id', $res['info']['id'])->get();

                    foreach ($list as $itr) {
                        $res['list'][] = [
                            'id' => $itr['id'],
                            'product' => new ProductResource(Product::with('brand', 'messuare', 'category', 'material')->where('id', $itr['register_id'])->first()),
                        ];
                    }
                    break;

                case 'connection':
                    $res['info'] = CarouselH::where('id', 5)->first();
                    $list = CarouselD::where('carousel_id', $res['info']['id'])->get();

                    foreach ($list as $itr) {
                        $res['list'][] = [
                            'id' => $itr['id'],
                            'product' => new ProductResource(Product::with('brand', 'messuare', 'category', 'material')->where('id', $itr['register_id'])->first()),
                        ];
                    }
                    break;

                case 'itron-accell':
                    $res['info'] = CarouselH::where('id', 6)->first();
                    $list = CarouselD::where('carousel_id', $res['info']['id'])->get();

                    foreach ($list as $itr) {
                        $res['list'][] = [
                            'id' => $itr['id'],
                            'product' => new ProductResource(Product::with('brand', 'messuare', 'category', 'material')->where('id', $itr['register_id'])->first()),
                        ];
                    }
                    break;

                case 'alfa':
                    $res['info'] = CarouselH::where('id', 7)->first();
                    $list = CarouselD::where('carousel_id', $res['info']['id'])->get();

                    foreach ($list as $itr) {
                        $res['list'][] = [
                            'id' => $itr['id'],
                            'product' => new ProductResource(Product::with('brand', 'messuare', 'category', 'material')->where('id', $itr['register_id'])->first()),
                        ];
                    }
                    break;

            }

            return response([
                'message' => 'Successful query',
                'data' => $res,
            ], ResponseHttp::HTTP_OK);

        } catch (CustomException $e) {
            return response([
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
    }
}
