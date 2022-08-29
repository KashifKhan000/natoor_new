<?php

namespace App\Http\Controllers\API\V1\Packages;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PackagesController extends Controller
{
    protected $data = ['message' => '','response' => [],'errors' => [],'status' => 200];

    protected function validatePackages($request = array()){
        return Validator::make($request->all(),[
            'status' => 'required|string',
            'price' => 'required|integer',
            'discount' => 'required|integer',
            'duration' => 'required|integer',
            'no_of_users' => 'required|integer',
        ]);
    }

    public function savePackage(Request $request){
        if(checkType()){

            $validate = $this->validatePackages($request);
            if($validate->fails()){
                $this->data['errors'] = $validate->errors()->all();
                $this->data['status'] = 422;
            }else{
                $package = new Package();
                $response = $package->savePackage($request);
                if($response){
                    $this->data['message'] = 'Package saved successfully';
                    $this->data['status'] = 200;
                }else{
                    $this->data['errors'] = ["Unable to save package"];
                    $this->data['status'] = 500;
                }
            }
        }else{
            $this->data['message'] = 'Sorry You dont have permission to access this page';
            $this->data['status'] = 403;
        }
        return response()->json($this->data);
    }

    public function updatePackage(Request $request,$id){
        if(checkType()){
            $validate = $this->validatePackages($request);
            if($validate->fails()){
                $this->data['errros'] = $validate->errors()->all();
                $this->data['status'] = 422;
            }else{
                $package = new Package();
                $response = $package->updatePackage($request,$id);
                if($response){
                    $this->data['message'] = 'Package updated successfully';
                }else{
                    $this->data['errors'] = ['Error updating package'];
                    $this->data['status'] = 500;
                }
            }
        }else{
            $this->data['message'] = 'Sorry You dont have permission to access this page';
            $this->data['status'] = 403;
        }
        return response()->json($this->data);
    }

    public function deletePackage($id = ""){
        if(checkType()){
            if( $id > 0){
                $package = new Package();
                $response = $package->deletePackage($id);
                if($response){
                    $this->data['message'] = "Package deleted successfully";
                }else{
                    $this->data['errors'] = ['Error deleting package'];
                    $this->data['status'] = 500;
                }
            }else{
                $this->data['errors'] = ['Error resolving id'];
                $this->data['status'] = 404;
            }
        }else{
            $this->data['message'] = 'Sorry You dont have permission to access this page';
            $this->data['status'] = 403;
        }
        return response()->json($this->data);
    }

    public function viewPackage(Request $request,$id = ''){
        $package = new Package();
        $this->data['response'] = $package->getPackage($request,$id);
        return response()->json($this->data);
    }
}
