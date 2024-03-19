<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TenantSmsGateway;
use App\Http\Resources\TenantSmsGateway as TenantSmsGatewayResource;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponser;

class SMSController extends Controller
{

    use ApiResponser;

    public function sendsms(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tenant_id' => 'required',
            'api_id' => 'required',
            'api_password' => 'required',
            'sender_id' => 'required|string', 
            'phonenumber' => 'required|numeric|digits_between:8,15',
            'textmessage' => 'required|string', 
            'msg_type' => 'nullable|in:1,2' 
        ]);
    
        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }
    
        $tenant_id = $request->input('tenant_id');
        $api_id = $request->input('api_id');
        $api_password = $request->input('api_password');
        $sender_id = $request->input('sender_id');
        $phonenumber = $request->input('phonenumber');
        $textmessage = $request->input('textmessage');
        $msg_type = $request->input('msg_type', 1);
    
        
        $userVerified = $this->verifyUser($api_id, $api_password);
    
        if ($userVerified) {
          
            send_ooredoo_sms($sender_id, $phonenumber, $textmessage, $msg_type);
            return $this->success("SMS sent successfully.", 200); 
        } else {
           
            return $this->error('Invalid API credentials.', 401);
        }
    }

    public function verifyUser($api_id, $api_password) {
       
        $tenantsmsgateway = TenantSmsGateway::where('api_id', $api_id)->first();

        if ($tenantsmsgateway && $tenantsmsgateway->api_password === $api_password) {
            return true;
        } else {
            return false;
        }
    }


   
}
