<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TenantSmsGateway;
use App\Models\SmsHistory;
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
        $id =$request->id;
        $tenant_id = $request->input('tenant_id');
        $api_id = $request->input('api_id');
        $api_password = $request->input('api_password');
        $sender_id = $request->input('sender_id');
        $phonenumber = $request->input('phonenumber');
        $textmessage = $request->input('textmessage');
        $amount= $request->input('amount');
        $msg_type = $request->input('msg_type', 1);
    
        
        $userVerified = $this->verifyUser($api_id, $api_password);
        if ($userVerified) {
            // Assuming `send_ooredoo_sms` is a custom function.
            send_ooredoo_sms($sender_id, $phonenumber, $textmessage, $msg_type);

            $tenantSmsGateway = TenantSmsGateway::where('tenant_id', $tenant_id)->first();

            if (!$tenantSmsGateway) {
                return $this->error('Tenant SMS gateway not found.', 404);
            }
    
            // If condition is true, insert data automatically.
            // You can replace `YourModelName::create` with the appropriate model and attributes.

            $msg_count = strlen($textmessage) > 160 ? 2 : 1;

            $smsHistory = SmsHistory::create([
                'tenantsms_id' =>$tenantSmsGateway->id,                
                'tenant_id' => $tenant_id,
                'msg_length' => strlen($textmessage),
                'msg_count' => $msg_count,
                'msg_price' => $msg_count * $amount,
            ]);
    
            if ($smsHistory) {
                return $this->success("SMS sent successfully.", 200); 
            } else {
                return $this->error('Failed to save SMS history.', 500);
            }
        } else {
            return $this->error('Invalid API credentials.', 401);
        }
        
        // if ($userVerified) {
          
        //     send_ooredoo_sms($sender_id, $phonenumber, $textmessage, $msg_type);
        //     return $this->success("SMS sent successfully.", 200); 
        // } else {
           
        //     return $this->error('Invalid API credentials.', 401);
        // }
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
