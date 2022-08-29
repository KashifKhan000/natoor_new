<?php

namespace App\Http\Controllers\API\V1\Countries;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CountriesController extends Controller
{
    protected $data = ['message' => '','response' => [],'errors' => [],'status' => 200];

    protected function validateCountries($request = array()){
       return Validator::make($request->all(),[
            'name_en' => '',
            'name_ar' => '',
            'status'  => 'required|string',
       ]);
    }

    public function saveCountries(Request $request){
        if(checkType()){
            $validate = $this->validateCountries($request);
            if($validate->fails()){
                $this->data['errors'] = $validate->errors()->all();
                $this->data['status'] = 422;
            }else{
                $countries = new Country();
                $response = $countries->saveCountry($request);
                if($response){
                    $this->data['message'] = 'Country Saved successfully';
                }else{
                    $this->data['error'] = 'Error while saving country';
                    $this->data['status'] = 500;
                }
            }
        }else{
            $this->data['message'] = 'Sorry You dont have permission to access this page';
            $this->data['status'] = 403;
        }
        return response()->json($this->data);
    }

    public function updateCountry(Request $request,$id){
        if(checkType()){
            if($id > 0){
                $validate = $this->validateCountries($request);
                if($validate->fails()){
                    $this->data['errors'] = $validate->errors()->all();
                    $this->data['status'] = 422;
                }else{
                    $countries = new Country();
                    $response = $countries->updateCountries($request,$id);
                    if($response){
                        $this->data['message'] = 'Country updated successfully';
                    }else{
                        $this->data['error'] = 'Error while updating country';
                        $this->data['status'] = 500;
                    }
                }
            }else{
                $this->data['error'] = 'Error resolving id';
                $this->data['status'] = 400;
            }
        }else{
            $this->data['message'] = 'Sorry You dont have permission to access this page';
            $this->data['status'] = 403;
        }
        return response()->json($this->data);
    }

    public function getCountries(Request $request,$id = ""){
        $country = new Country();
        $this->data['response'] = $country->getCountries($request,$id);
        return response()->json($this->data);
    }

    public function deleteCountry($id){
        if(checkType()){
            if($id > 0){
                $country = new Country();
                $response = $country->deleteCountry($id);
                if($response){
                    $this->data['message'] = 'Country deleted successfully';
                }else{
                    $this->data['errors'] = ['Error deleting country'];
                    $this->data['status'] = 500;
                }
            }else{
                $this->data['errors'] = ['Error resolving id'];
                $this->data['status'] = 400;
            }
        }else{
            $this->data['message'] = 'Sorry You dont have permission to access this page';
            $this->data['status'] = 403;
        }
        return response()->json($this->data);
    }
}
