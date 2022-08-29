<?php

namespace App\Http\Controllers\API\V1\Floors;

use App\Http\Controllers\Controller;
use App\Models\Floor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FloorsController extends Controller
{
    protected $data = ['message' => '','response' => [],'errors' => [],'status' => 200];

    protected function validateFloor($request){
        return Validator::make($request->all(),[
            'floor_no' => 'required|string',
            'building_id' => 'required|integer',
            'status' => 'required|string',
        ]);
    }

    public function saveFloor(Request $request){
        $validate = $this->validateFloor($request);
        if($validate->fails()){
            $this->data['errors'] = $validate->errors()->all();
            $this->data['status'] = 422;
        }else{
            $floor = new Floor();
            $response = $floor->saveFloor($request);
            if($response){
                $this->data['message'] = 'Floor created successfully';
                $this->data['status'] = 200;
            }else{
                $this->data['errors'] = ['Unable to create Floor'];
                $this->data['status'] = 500;
            }
        }
        return response()->json($this->data);
    }

    public function updateFloor(Request $request,$id){
        if($id > 0){
            $validate = $this->validateFloor($request);
            if($validate->fails()){
                $this->data['errors'] = $validate->errors()->all();
                $this->data['status'] = 422;
            }else{
                $floor = new Floor();
                $response = $floor->updateFloor($request,$id);
                if($response){
                    $this->data['message'] = "Floor updated successfully";
                    $this->data['status'] = 200;
                }else{
                    $this->data['errors'] = ['unable to update Floor'];
                    $this->data['status'] = 500;
                }
            }
        }else{
            $this->data['errors'] = ['error resolving id'];
            $this->data['status'] = 404;
        }
        return response()->json($this->data);

    }

    public function viewFloor(Request $request,$id = ""){
        $floor = new Floor();
        $this->data['response'] = $floor->getFloors($request,$id);
        return response()->json($this->data);
    }

    public function deleteFloor($id){
        $floor = new Floor();
        $response = $floor->deleteFloor($id);
        if($response){
            $this->data['message'] = 'Floor deleted successfully';
            $this->data['status'] = 200;
        }else{
            $this->data['message'] = 'unable to delete floor';
            $this->data['status'] = 500;
        }
        return response()->json($this->data);
    }
}
