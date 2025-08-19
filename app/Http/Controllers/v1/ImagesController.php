<?php

namespace App\Http\Controllers\v1;

use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Models\v1\Image;
use Aws\Credentials\Credentials;
use Aws\S3\S3Client;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseHttp;

class ImagesController extends Controller
{
    public static function replaceUrlImage($data)
    {
        if (isset($data)) {
            return config('app.aws.url_image').$data['url'];
        }

        return 'http://invitationapidev.eventosenqueretaro.com.mx/images/default/default_logo.png';
    }

    public static function uploadFileAmazonS3(array $data, string $db)
    {
        $credentials = new Credentials(config('app.aws.access_key_id'), config('app.aws.secret_access_key'));
        $s3 = new S3Client([
            'version' => 'latest',
            'region' => config('app.aws.default_region'),
            'credentials' => $credentials,
        ]);

        $extension = explode('.', $data['image']->name)[1];

        if ($extension === 'pdf') {
            $fileData = base64_decode(preg_replace('#^data:application/\w+;base64,#i', '', $data['image']->data));
        } else {
            $fileData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data['image']->data));
        }

        $destinationPath = $db.'/';
        $filename = time().'.'.$extension;

        $s3->putObject([
            'Bucket' => config('app.aws.bucket'),
            'Key' => $destinationPath.$filename,
            'Body' => $fileData,
            'ACL' => 'public-read',
        ]);

        return $db.'/'.$filename;
    }

    public static function deleteFileAmazonS3($url)
    {
        $credentials = new Credentials(config('app.aws.access_key_id'), config('app.aws.secret_access_key'));
        $s3 = new S3Client([
            'version' => 'latest',
            'region' => config('app.aws.default_region'),
            'credentials' => $credentials,
        ]);

        $s3->deleteObject([
            'Bucket' => config('app.aws.bucket'),
            'Key' => $url,
        ]);
    }

    public static function uploadFileAndDataBase(array $data, string $db)
    {
        $url = self::uploadFileAmazonS3($data, $db);

        (new Image)->setConnection($db)->create([
            'id' => date('ymd') . date('Hi') . date('s') . substr(microtime(), 2, 3),
            'url' => $url,
            'reference_type' => $data['information']['type'],
            'is_active' => 1,
            'reference_id' => $data['information']['id'],
        ]);
    }

    public static function deleteFileAndDataBase(object $data, string $db)
    {
        self::deleteFileAmazonS3($data->url);
        (new Image)->setConnection($db)->where('id', $data->id)->update(['is_active' => 0]);
    }

    public function registerImage(Request $request)
    {
        try {
            ValidatorController::validatorData($request->info, [
                'image' => 'required',
                'register_id' => 'required',
                'register_type' => 'required',
            ]);

            $INFO = $request->info;
            $API = $request->api;

            ImagesController::uploadFileAndDataBase([
                'image' => $INFO->image,
                'information' => [
                    'id' => $INFO->register_id,
                    'type' => $INFO->register_type,
                ],
            ], $API->database);

            return response(['message' => 'registration successfully'], ResponseHttp::HTTP_OK);
        } catch (CustomException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public function deleteImage(Request $request)
    {
        try {
            ValidatorController::validatorData($request->info, [
                'id' => 'required',
            ]);

            $INFO = $request->info;
            $API = $request->api;

            $image = (new Image)->setConnection($API->database)->where('id', $INFO->id)->first();

            if (isset($image)) {
                ImagesController::deleteFileAndDataBase($image, $API->database);
            }

            return response(['message' => 'delete successfully'], ResponseHttp::HTTP_OK);
        } catch (CustomException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }
}
