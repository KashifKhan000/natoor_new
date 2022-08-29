<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Room extends Model
{

    use BelongsToTenant;

    protected $table = 'rooms';
    protected $perPage = 15;
    protected $locale = 'en';

    public function getRoom($request= array(), $id = ""){
        $status = $request->input('status','');
        $search = $request->input('search','');
        $order_by = $request->input('order_by','id');
        $order  = $request->input('order','desc');
        $floor_id = $request->input('floor_id','');
        $building_id = $request->input('building_id','');

        $query = Room::select('rooms.*');

        if(!empty($floor_id)){
            $query->where('rooms.floor_id',$floor_id);
        }

        if(!empty($building_id)){
            $query->where('rooms.building_id',$building_id);
        }

        if(!empty($status)){
            $query->where('rooms.status','');
        }

        if(!empty($search)){
            $searchFields = array('rooms.room_no');
            $query->where( function($query) use($searchFields, $search){
                foreach($searchFields as $field) {
                    $query->orWhere($field, 'LIKE', '%'.$search.'%');
                }
            });
        }

        if(!empty($order) && !empty($order_by)){
            $query->orderBy('rooms.'.$order_by,$order);
        }


        // limit
        $inputLimit         = $request->input('limit', 0);
        $limit              = ($inputLimit > 0) ? $inputLimit : $this->perPage;
        $inputPage          = $request->input('page', '');
        $page               = ($inputPage > 0) ? $inputPage : 1;
        $offset             = ($page - 1) * $limit;
        if($page > 1){
            $query->skip($offset)->take($limit);
        }

        // $sql    = Str::replaceArray('?', $query->getBindings(), $query->toSql());
        // echo $sql; die;
        $records = array();
        if($id > 0){
            $records = $query->first();
        }else{
            $records   = $request->input('limit','') < 0 ? $query->get() : $query->paginate($limit);
        }
        return $records;
    }

    public function saveRoom($request){
        $room = new Room();
        $room->building_id = $request->input('building_id','');
        $room->floor_id = $request->input('floor_id','');
        $room->room_no = $request->input('room_no','');
        $room->type = $request->input('type','');
        $room->status = $request->input('status','Active');
        if($room->save()){
            return true;
        }else{
            return false;
        }
    }

    public function updateRoom($request,$id){
        $room = Room::find($id);
        $room->building_id = $request->input('building_id','');
        $room->floor_id = $request->input('floor_id','');
        $room->room_no = $request->input('room_no','');
        $room->type = $request->input('type','');
        $room->status = $request->input('status','Active');
        if($room->save()){
            return true;
        }else{
            return false;
        }
    }

    public function deleteRoom($id){
        $room = Room::find($id);
        if(!empty($room)){
            $room->delete();
            return true;
        }else{
            return false;
        }
    }
}
