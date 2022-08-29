<?php

namespace App\Http\Controllers\API\V1\Directory;

use App\Http\Controllers\Controller;
use App\Models\Directory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DirectoryController extends Controller
{
    protected $data = ['message' => '','response' => [],'errors' => [],'status' => 200];

    protected function validateDirectory($request = array()){
       return Validator::make($request->all(),[
            'name' => 'required|string',
            'phone_no' => 'required|string',
            'address' => 'required|string',
            'services_id' => 'required',
            'status'  => 'required|string',
       ]);
    }

    public function saveDirectory(Request $request){
            $validate = $this->validateDirectory($request);
            if($validate->fails()){
                $this->data['errors'] = $validate->errors()->all();
                $this->data['status'] = 422;
            }else{
                $directory = new Directory();
                $response = $directory->saveDirectory($request);
                if($response){
                    $this->data['message'] = 'Directory Saved successfully';
                }else{
                    $this->data['error'] = 'Error while saving directory';
                    $this->data['status'] = 500;
                }
            }

        return response()->json($this->data);
    }

    public function updateDirectory(Request $request,$id){
            if($id > 0){
                $validate = $this->validateDirectory($request);
                if($validate->fails()){
                    $this->data['errors'] = $validate->errors()->all();
                    $this->data['status'] = 422;
                }else{
                    $directory = new Directory();
                    if(!empty($directory->getDirectory($request,$id))){
                        $response = $directory->updateDirectory($request,$id);
                        if($response){
                            $this->data['message'] = 'Directory updated successfully';
                        }else{
                            $this->data['error'] = ['Error while updating directory'];
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

        return response()->json($this->data);
    }

    public function getDirectory(Request $request,$id = ""){
        $directory = new Directory();
        $this->data['response'] = $directory->getDirectory($request,$id);
        return response()->json($this->data);
    }

    public function deleteDirectory($id){

            if($id > 0){
                $directory = new Directory();
                $response = $directory->deleteDirectory($id);
                if($response){
                    $this->data['message'] = 'Directory deleted successfully';
                }else{
                    $this->data['errors'] = ['Error deleting city'];
                    $this->data['status'] = 500;
                }
            }else{
                $this->data['errors'] = ['Error resolving id'];
                $this->data['status'] = 400;
            }

        return response()->json($this->data);
    }
}
