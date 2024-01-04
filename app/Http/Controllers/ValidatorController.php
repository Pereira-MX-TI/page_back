<?php

namespace App\Http\Controllers;

use Blocktrail\CryptoJSAES\CryptoJSAES;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Models\Response;
use App\Models\Token;


class ValidatorController extends Controller
{
    static public function validatorGral($data,$paramaters,$opc)
    {
        $validator = Validator::make($data->all(),['data' => 'required']);
        if ($validator->fails()) 
            return Response::where('code', '422')->get()->first();

        if($opc==0)
            $data = CryptoJSAES::decrypt($data['data'], env('APP_KEY'));
        else
        {
            $data = $data->all();
            //$data = str_replace(' ','+',$data['data']);   
            $data = preg_replace('/[~]/','/',$data['data']); 
            $data = base64_decode($data);

            $data = CryptoJSAES::decrypt($data, env('APP_KEY'));
        }  

        try {
            $data = json_decode($data,true);
            $validator = Validator::make($data, $paramaters);
    
            if ($validator->fails()) 
                return Response::where('code', '422')->get()->first();        
           
            return ['code'=>200,'data'=>$data];
        } 
        catch (\Exception $e) 
        {
            Log::debug($e->getMessage());
            return Response::where('code', '601')->get()->first();
        }
    }

    static public function validatorSimple($data,$paramaters)
    {
        $validator = Validator::make($data, $paramaters);

        if ($validator->fails()) 
            return Response::where('code', '422')->get()->first();   
  
        return ['code'=>200,'data'=>$data];
    }

    static public function validatorToken($data)
    {
        if(!isset($data['datatoken']))
            return ['code'=>401,'name'=>'Error','Token'=>'Token invalid'];

        if(count($data['datatoken'])==0)
            return ['code'=>401,'name'=>'Error','Token'=>'Token invalid'];

        if(Token::where([['data',$data['datatoken'][0]],['is_active',1]])->get()->count()==0)
            return ['code'=>401,'name'=>'Error','Token'=>'Token invalid'];

        return ['code'=>200,'name'=>'fine','description'=>'Fine validation'];
    }

    static public function replaceData($data)
    {
        $data = str_replace(' ','+',$data['data']);   
        $data = preg_replace('/[~]/','/',$data);
        return $data; 
    }

    static public function validatorQuotationWeb($contact,$address_ip)
    {
        $currentTime = date('Y-m-d H:i:s');
        $quantity = 0;
        if(isset($address_ip))
        {
            $quantity = count(DB::connection('mysql2')->select("SELECT QW.id FROM quotation_web QW 
            INNER JOIN contact_web CW ON QW.client_web_id=CW.id 
            WHERE QW.created_at LIKE '%".date_format(date_create($currentTime),"Y-m-d")."%' AND 
            (CW.email = '".$contact['email']."' OR 
            QW.ip_address = '".$address_ip['ip']."')"));
        }
        else
        {
            $quantity = count(DB::connection('mysql2')->select("SELECT QW.id FROM quotation_web QW 
            INNER JOIN contact_web CW ON QW.client_web_id=CW.id 
            WHERE QW.created_at LIKE '%".date_format(date_create($currentTime),"Y-m-d")."%' AND 
            CW.email = '".$contact['email']."'"));
        }

        return $quantity < 5?true:false;
    }
    
    static public function validatorRequestInfoService($contact,$address_ip)
    {
        $currentTime = date('Y-m-d H:i:s');
        $quantity = 0;
        if(isset($address_ip))
        {
            $quantity = count(DB::connection('mysql')->select("SELECT RS.id FROM request_services RS 
            INNER JOIN contacts CW ON RS.contact_id=CW.id 
            WHERE RS.created_at LIKE '%".date_format(date_create($currentTime),"Y-m-d")."%' AND 
            (CW.email = '".$contact['email']."' OR 
            RS.ip_address = '".$address_ip['ip']."')"));
        }
        else
        {
            $quantity = count(DB::connection('mysql')->select("SELECT RS.id FROM request_services RS 
            INNER JOIN contacts CW ON RS.contact_id=CW.id 
            WHERE RS.created_at LIKE '%".date_format(date_create($currentTime),"Y-m-d")."%' AND 
            CW.email = '".$contact['email']."'"));
        }

        return $quantity < 5?true:false;
    }
}
