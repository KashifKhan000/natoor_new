<?php

namespace App\Http\Controllers\API\V1\Cities;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CitiesController extends Controller
{
    protected $data = ['message' => '','response' => [],'errors' => [],'status' => 200];

    protected function validateCities($request = array()){
       return Validator::make($request->all(),[
            'name_en' => '',
            'name_ar' => '',
            'country_id' => 'required|integer',
            'status'  => 'required|string',
       ]);
    }

    public function saveCity(Request $request){
        if(checkType()){
            $validate = $this->validateCities($request);
            if($validate->fails()){
                $this->data['errors'] = $validate->errors()->all();
                $this->data['status'] = 422;
            }else{
                $countries = new City();
                $response = $countries->saveCity($request);
                if($response){
                    $this->data['message'] = 'City Saved successfully';
                }else{
                    $this->data['error'] = 'Error while saving city';
                    $this->data['status'] = 500;
                }
            }
        }else{
            $this->data['message'] = 'Sorry You dont have permission to access this page';
            $this->data['status'] = 403;
        }
        return response()->json($this->data);
    }

    public function updateCity(Request $request,$id){
        if(checkType()){
            if($id > 0){
                $validate = $this->validateCities($request);
                if($validate->fails()){
                    $this->data['errors'] = $validate->errors()->all();
                    $this->data['status'] = 422;
                }else{
                    $city = new City();
                    if(!empty($city->getCity($request,$id))){
                        $response = $city->updateCities($request,$id);
                        if($response){
                            $this->data['message'] = 'City updated successfully';
                        }else{
                            $this->data['error'] = ['Error while updating city'];
                            $this->data['status'] = 500;
                        }
                    }else{
                        $this->data['error'] = ['No Record Found'];
                        $this->data['status'] = 404;
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

    public function getCities(Request $request,$id = ""){
        $city = new City();
        $this->data['response'] = $city->getCity($request,$id);
        return response()->json($this->data);
    }

    public function deleteCity($id){
        if(checkType()){
            if($id > 0){
                $country = new City();
                $response = $country->deleteCity($id);
                if($response){
                    $this->data['message'] = 'City deleted successfully';
                }else{
                    $this->data['errors'] = ['Error deleting city'];
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
