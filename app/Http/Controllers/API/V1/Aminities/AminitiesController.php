<?php

namespace App\Http\Controllers\API\V1\Aminities;

use App\Http\Controllers\Controller;
use App\Models\Aminities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AminitiesController extends Controller
{
    protected $data = ['message' => '','response' => [],'errors' => [],'status' => 200];

    protected function validateAminities($request = array()){
        return Validator::make($request->all(),[
            'status' => 'required|string',
        ]);
    }

    public function saveAminities(Request $request){
        if(checkType()){
            $validate = $this->validateAminities($request);
            if($validate->fails()){
                $this->data['errors'] = $validate->errors()->all();
                $this->data['status'] = 422;
            }else{
                $aminities = new Aminities();
                $response = $aminities->saveAminities($request);
                if($response){
                    $this->data['message'] = 'Aminity saved successfully';
                }else{
                    $this->data['errors'] = ['Error while saving Aminity'];
                    $this->data['status'] = 500;
                }
            }
        }else{
            $this->data['message'] = 'Sorry You dont have permission to access this page';
            $this->data['status'] = 403;
        }
        return response()->json($this->data);
    }

    public function deleteAminities($id){
        if(checkType()){
            if($id > 0){
                $aminities = new Aminities();
                $response = $aminities->deleteAminities($id);
                if($response){
                    $this->data['message'] = 'Animity Deleted successfully';
                }else{
                    $this->data['message'] = 'No Record Found';
                    $this->data['status'] = 404;
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

    public function updateAminities(Request $request,$id = ''){
        if(checkType()){
            if($id > 0){
                $aminities = new Aminities();
                if(!empty(Aminities::find($id))){
                    $response = $aminities->updateAminities($request,$id);
                    if($response){
                        $this->data['message'] = 'Aminity updated successfully';
                    }else{
                        $this->data['errros'] = ['Error while updating aminity'];
                        $this->data['status'] = 500;
                    }
                }else{
                    $this->data['message'] = 'No Record Found!';
                    $this->data['status'] = 404;
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

    public function getAminities(Request $request,$id = ""){
        $aminities = new Aminities();
        $this->data['response']  = $aminities->getAminities($request,$id);
        return response()->json($this->data);
    }
}
