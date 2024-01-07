<?php

namespace App\Http\Controllers\v1;

use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\InfoServiceMail;
use App\Mail\QuotationMail;
use Illuminate\Support\Facades\Mail;
use Blocktrail\CryptoJSAES\CryptoJSAES;
use App\Http\Controllers\ValidatorController;
use Illuminate\Support\Facades\DB;
use App\Models\v1\ContactWeb;
use App\Models\v1\QuotationWeb;
use App\Models\v1\Contact;
use App\Models\v1\RequestService;
use App\Models\v1\DetailQuotationWeb;
use App\Models\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\Response as ResponseHttp;

class QuotatioWebController extends Controller
{
    public function registerQuotationWeb(Request $request)
    {
        try {
            ValidatorController::validatorData($request->info, [
                'contact' => 'required',
                'contact.name' => 'required',
                'contact.email' => 'required',
                'contact.phone' => 'required',
                'contact.cp' => 'required',
                'ip_address' => 'required', // Opcional
                'ip_address.ip' => 'required', // Opcional
                'listProduct' => 'required'
            ]);

            $data = $request->info;
            $validation = ValidatorController::validatorQuotationWeb($data->contact, $data->ip_address);

            if (!$validation){
                throw new CustomException('Problem with consulta', 210);
            }

            $currentTime = date('Y-m-d H:i:s');
            $listData = array();

            $contact_web = ContactWeb::where("email", $data->contact['email'])
                ->orderBy("id", "DESC")
                ->first();

            if (isset($contact_web)) {

                ContactWeb::where('id', $contact_web->id)
                    ->update([
                    'name' => $data->contact['name'],
                    'phone' => $data->contact['phone'],
                    'cp' => $data->contact['cp']
                ]);

            } else {

                ContactWeb::create([
                    'name' => $data->contact['name'],
                    'email' => $data->contact['email'],
                    'phone' => $data->contact['phone'],
                    'cp' => $data->contact['cp'],
                    'is_active' => 1
                ]);

                $contact_web = ContactWeb::where("email", $data->contact['email'])
                    ->orderBy("id", "DESC")
                    ->first();
            }

            QuotationWeb::create([
                'client_web_id' => $contact_web['id'],
                'user_id' => 7,
                'ip_address' => isset($data->ip_address) ? $data->ip_address['ip'] : '',
                'is_active' => 1,
                'status' => 'P'
            ]);

            $quotation_web = QuotationWeb::where([
                ["client_web_id", $contact_web['id']],
                ["ip_address", $data->ip_address['ip']],
                ])
                ->orderBy("id", "DESC")
                ->first();

            foreach ($data->listProduct as $itrData) {

                $cost = DB::connection('mysql2')
                    ->select("SELECT (CASE
                WHEN C.calculate_id=1 THEN ROUND(C.costo*((C.utilidad/100.000)+1.000),3)
                ELSE ROUND((C.costo*CC.valor)*((C.utilidad/100.000)+1.000),3)
                END) cost FROM costs C
                INNER JOIN cost_calculates CC ON C.calculate_id=CC.id
                WHERE C.concept_id = " . $itrData['product']['id'] . " AND
                C.tipo_concepto = 'P' ORDER BY C.id DESC LIMIT 1");

                $listData[] = [
                    'key' => $itrData['product']['clave'],
                    'name' => $itrData['product']['nombre'],
                    'description' => $itrData['product']['description']['detalle'],
                    'quantity' => $itrData['quantity'],
                ];

                DetailQuotationWeb::create([
                    'quotation_web_id' => $quotation_web['id'],
                    'concept_id' => $itrData['product']['id'],
                    'user_id' => 7,
                    'description_id' => $itrData['product']['description']['id'],
                    'tipo_concepto' => 'P',
                    'cantidad' => $itrData['quantity'],
                    'precio_unitario' => count($cost) == 0 ? 0 : $cost[0]->cost,
                    'estatus_crud' => 'C'
                ]);
            }

            $pdf = PDF::loadView("pdf.requestQuotation", compact(['quotation_web', 'contact_web', 'listData', 'currentTime']));

            $objFile['name'] = 'Folio_Solicitud_' . time();
            $objFile['data'] = base64_encode($pdf->download('requestQuotation.pdf'));

            Mail::to("solucionescomerciales_jmpf@outlook.com")
                ->send(new QuotationMail([
                'contact' => $contact_web,
                'quotation' => $quotation_web,
            ]));

            return response()->json([
                'message' => 'Successful query',
                'data' => $objFile
            ], ResponseHttp::HTTP_OK);

        } catch (CustomException $e) {
            return response([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function registerInfoServiceWeb(Request $request)
    {
        try {
            ValidatorController::validatorData($request->info, [
                'contact' => 'required',
                'contact.name' => 'required',
                'contact.email' => 'required',
                'contact.phone' => 'required',
                'contact.cp' => 'required',
                'ip_address' => 'required', // Opcional
                'ip_address.ip' => 'required', // Opcional
                'service' => 'required'
            ]);

            $data = $request->info;

            $validation = ValidatorController::validatorRequestInfoService($data->contact, $data->ip_address);

            if (!$validation){
                throw new CustomException('Problem with consulta', 210);
            }

            $contact_web = Contact::where("email", $data->contact['email'])
                ->orderBy("id", "DESC")
                ->first();

            if (isset($contact_web)) {

                Contact::where('id', $contact_web['id'])
                    ->update([
                    'name' => $data->contact['name'],
                    'phone' => $data->contact['phone'],
                    'cp' => $data->contact['cp']
                ]);

            } else {

                Contact::create([
                    'name' => $data->contact['name'],
                    'email' => $data->contact['email'],
                    'phone' => $data->contact['phone'],
                    'cp' => $data->contact['cp'],
                    'is_active' => 1
                ]);

                $contact_web = Contact::where("email", $data->contact['email'])
                    ->orderBy("id", "DESC")
                    ->first();
            }

            RequestService::create([
                'contact_id' => $contact_web['id'],
                'ip_address' => isset($data->ip_address) ? $data->ip_address['ip'] : '',
                'name' => $data['service'],
                'message' => $data['contact']['description'],
                'is_active' => 1
            ]);

            Mail::to("solucionescomerciales_jmpf@outlook.com")
                ->send(new InfoServiceMail([
                'contact' => $contact_web,
                'service' => $data['service'],
                'message' => $data->contact['description']
            ]));

            return response()->json([
                'message' => 'Successful query',
                'data' => $data
            ], ResponseHttp::HTTP_OK);

        } catch (CustomException $e) {
            return response([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
