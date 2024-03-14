<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Http\Resources\Tenant as TenantResource;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponser;

class TenantController extends Controller
{
    use ApiResponser;

    public function index()
    {
        $tenants = Tenant::all();
        return $this->success(TenantResource::collection($tenants), 'Tenant fetched successfully.');
    }
    
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',  
            'slug' => 'required|unique:tenants', 
            'email' => 'required|email|unique:tenants', 
            'no_of_terminals' => 'required|integer|min:1', 
            'address' => 'required',
            'phone_number' => 'required|numeric', 
        ]);
        
        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $tenant = Tenant::create($input);
        return $this->success(new TenantResource($tenant), 'Tenant created successfully.');
    }
   
    public function show($id)
    {
        $tenant = Tenant::find($id);
        if (is_null($tenant)) {
            return $this->error('Tenant not found.', 404);
        }
        return $this->success(new TenantResource($tenant), 'Tenant fetched successfully.');
    }
    
   
    public function update(Request $request, Tenant $tenant)
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'name' => 'required',  
            'slug' => 'required|unique:tenants', 
            'email' => 'required|email|unique:tenants', 
            'no_of_terminals' => 'required|integer|min:1', 
            'address' => 'required',
            'phone_number' => 'required|numeric', 
        ]);
   
        if ($validator->fails()) {
            return $this->error('Validation Error.', 400, $validator->errors());
        }
   
        $tenant->name = $input['name'];
        $tenant->slug = $input['slug'];
        $tenant->email = $input['email'];
        $tenant->no_of_terminals = $input['no_of_terminals'];
        $tenant->address = $input['address'];
        $tenant->phone_number = $input['phone_number'];
        $tenant->save();
   
        return $this->success($tenant, "Tenant updated successfully.", 200);
    }
   
    public function destroy(Tenant $tenant)
    {
        $tenant->delete();
        return $this->success([], 'Tenant deleted successfully.');
    }
}
