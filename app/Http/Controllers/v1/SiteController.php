<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Models\v1\Parent_celebrated;
use App\Http\Models\v1\Phone;
use App\Http\Models\v1\Photo;
use App\Http\Models\v1\Place;
use App\Http\Models\v1\Presentation;
use App\Http\Models\v1\Site;
use App\Http\Models\v1\Weather;
use App\Http\Models\v1\BackgroundImg;
use Illuminate\Support\Facades\Http;


use Symfony\Component\HttpFoundation\Response as ResponseHttp;

class SiteController extends Controller
{
    public function infoGeneralSite(Request $request)
    {
        try {
            ValidatorController::validatorData($request->info, [
                'id' => 'required',
            ]);

            $INFO = $request->info;
            $site = Site::where('id', $INFO->id)->first();


            $data = $site 
            ? [
                "site" =>  $site,
                "weather" =>  Weather::where('site_id', $INFO->id)->first(),
                "presentation" =>  Presentation::where('site_id', $INFO->id)->first(),
                "parent_celebrated" =>  Parent_celebrated::where('site_id', $INFO->id)->get(),
                "phone" =>  Phone::where('site_id', $INFO->id)->latest()->first(),
                "photo" =>  Photo::where('site_id', $INFO->id)->get(),
                "img_background" => BackgroundImg::where([['site_id', $INFO->id],["is_active",1]])->get(),
                "place" =>  Place::where('site_id', $INFO->id)->orderby("date_time","ASC")->get()
            ]
            : null;

            return response([
                'message' => 'Success',
                'data' => $data,
            ], ResponseHttp::HTTP_OK);

        } catch (CustomException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public function weatherSite(Request $request)
    {
        try {
            ValidatorController::validatorData($request->info, [
                'city_id' => 'required',
            ]);

            $INFO = $request->info;
            $apiKey = '4d55693a6d7efe61788edc7eb5df5437';
            $url = 'https://api.openweathermap.org/data/2.5/weather?q=' . $INFO->city_id . '&appid=' . $apiKey . '&units=metric';

            $response = Http::get($url);

            if ($response->successful()) {
                $data = $response->json();
            } else {
                return response()->json(['message' => 'Error al obtener los datos del clima.'], 500);
            }

            return response([
                'message' => 'Success',
                'data' => $data,
            ], ResponseHttp::HTTP_OK);

        } catch (CustomException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public function registerSite(Request $request)
    {
        try {
            ValidatorController::validatorData($request->info, [
                'name' => 'required',
                'expiration' => 'required'
            ]);

            $INFO = $request->info;
            $general_id = date('ymd') . date('Hi') . date('s') . substr(microtime(), 2, 3); 

            Site::create([
                'id' => $general_id,
                'name' => $INFO->name,
                'expiration' => $INFO->expiration,
                'is_active' => 1,
            ]);


            if (isset($INFO->lora_params)) {
                Lora_params_gateway::create([
                    'id' => $general_id,
                    'gateway_id' => $general_id,
                    'device_id' => $INFO->lora_params->device_id,
                    'dev_eui' => $INFO->lora_params->dev_eui,
                    'is_active' => 1,
                ]);
            }

            return response(['message' => 'Register created successfully'], ResponseHttp::HTTP_OK);
        } catch (CustomException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }
}
