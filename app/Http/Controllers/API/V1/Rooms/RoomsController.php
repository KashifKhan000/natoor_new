<?php

namespace App\Http\Controllers\API\V1\Rooms;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\UserRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RoomsController extends Controller
{
    protected $data = ['message' => '','response' => [],'errors' => [],'status' => 200];

    protected function validateRooms($request = array()){
        return Validator::make($request->all(),[
            'status' => 'required|string',
            'room_no' => 'required|string',
            'type' => 'required|string',
            'floor_id' => 'required|integer',
            'building_id' => 'required|integer',
        ]);
    }

    protected function validateuserRooms($request = array()){
        return Validator::make($request->all(),[
            'status' => 'required|string',
            'room_id' => 'required|integer',
            'tenant_id' => 'required',
            'floor_id' => 'required|integer',
            'building_id' => 'required|integer',
        ]);
    }

    public function saveRoom(Request $request){
        $validate = $this->validateRooms($request);
        if($validate->fails()){
            $this->data['errors'] = $validate->errors()->all();
            $this->data['status'] = 422;
        }else{
            $room = new Room();
            $response = $room->saveRoom($request);
            if($response){
                $this->data['message'] = 'Room saved successfully';
                $this->data['status'] = 200;
            }else{
                $this->data['errors'] = ["Unable to save room"];
                $this->data['status'] = 500;
            }
        }
        return response()->json($this->data);
    }

    public function saveuserRoom(Request $request){
        $validate = $this->validateuserRooms($request);
        if($validate->fails()){
            $this->data['errors'] = $validate->errors()->all();
            $this->data['status'] = 422;
        }else{
            $room = new UserRoom();
            $response = $room->saveuserRoom($request);
            if($response){
                $this->data['message'] = 'User Room saved successfully';
                $this->data['status'] = 200;
            }else{
                $this->data['errors'] = ["Unable to save room"];
                $this->data['status'] = 500;
            }
        }
        return response()->json($this->data);
    }


    public function getuserrooms()
    {

        $user = UserRoom::join('rooms', 'user_rooms.room_id', '=', 'rooms.id')->where(['user_rooms.tenant_id'=>Auth::user()->tenant_id])
        ->where('user_rooms.status', 'active')
        ->get();
        if (!empty($user)) {
            $response = ['status' => true, 'data' => $user];
            return response($response, 200);
        }
        $response = ['status' => false, 'data' => ''];
        return response($response, 404);
    }


    public function updateRoom(Request $request,$id){
        if($id > 0){
            $validate = $this->validateRooms($request);
            if($validate->fails()){
                $this->data['errros'] = $validate->errors()->all();
                $this->data['status'] = 422;
            }else{
                $room = new Room();
                $response = $room->updateRoom($request,$id);
                if($response){
                    $this->data['message'] = 'Room updated successfully';
                }else{
                    $this->data['errors'] = ['Error updating room'];
                    $this->data['status'] = 500;
                }
            }
        }else{
            $this->data['errors'] = ['Error resolving id'];
            $this->data['status'] = 404;
        }
        return response()->json($this->data);
    }

    public function deleteRoom($id = ""){
        if( $id > 0){
            $room = new Room();
            $response = $room->deleteRoom($id);
            if($response){
                $this->data['message'] = "Room deleted successfully";
            }else{
                $this->data['errors'] = ['Error deleting room'];
                $this->data['status'] = 500;
            }
        }else{
            $this->data['errors'] = ['Error resolving id'];
            $this->data['status'] = 404;
        }
        return response()->json($this->data);
    }

    public function viewRoom(Request $request,$id = ''){
        $room = new Room();
        $this->data['response'] = $room->getRoom($request,$id);
        return response()->json($this->data);
    }
}
