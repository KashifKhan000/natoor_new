<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class UserRoom extends Model
{
    use BelongsToTenant;

    protected $table = 'user_rooms';
    protected $perPage = 15;
    protected $locale = 'en';
    protected $primaryKey = 'id';

    public function users()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function building()
    {
        return $this->hasOne(Building::class, 'id', 'building_id');
    }

    public function floor()
    {
        return $this->hasOne(Floor::class, 'id', 'floor_id');
    }

    public function rooms()
    {
        return $this->hasOne(Room::class, 'id', 'room_id');
    }

    public function saveuserRoom($request){
        $room = new UserRoom();
        $room->user_id = $request->input('user_id','');
        $room->building_id = $request->input('building_id','');
        $room->floor_id = $request->input('floor_id','');
        $room->room_id = $request->input('room_id','');
        $room->tenant_id = $request->input('tenant_id','');
        $room->status = $request->input('status','Active');
        if($room->save()){
            return true;
        }else{
            return false;
        }
    }

    public function updateRoom($request,$id){
        $room = UserRoom::find($id);
        $room->user_id = $request->input('user_id','');
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
        $room = UserRoom::find($id);
        if(!empty($room)){
            $room->delete();
            return true;
        }else{
            return false;
        }
    }
}
