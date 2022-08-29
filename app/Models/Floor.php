<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Floor extends Model
{
    use BelongsToTenant;

    protected $table = 'floors';
    protected $perPage = 15;
    protected $locale = 'en';

    public function saveFloor($request = array()){
        $floor = new Floor();
        $floor->floor_no = $request->input('floor_no','');
        $floor->building_id = $request->input('building_id','');
        $floor->status = $request->input('status','Active');
        if($floor->save()){
            return true;
        }else{
            return false;
        }
    }

    public function deleteFloor($id = ''){
        $floor = Floor::find($id);
        if(!empty($floor)){
            $floor->delete();
            return true;
        }else{
            return false;
        }
    }

    public function updateFloor($request = array(),$id = ''){
        $floor = Floor::find($id);
        $floor->floor_no = $request->input('floor_no','');
        $floor->building_id = $request->input('building_id','');
        $floor->status = $request->input('status','Active');
        if($floor->save()){
            return true;
        }else{
            return false;
        }
    }

    public function getFloors($request = array(),$id = ''){
        $status = $request->has('status') ? $request->input('status','') : '';
        $search = $request->has('search') ?  $request->input('search','') : '';
        $lang_code = $request->has('lang_code') ?  $request->input('lang_code','') : 'en';
        $order_by = $request->has('order_by') ?  $request->input('order_by','id') : '';
        $order = $request->has('order') ? $request->input('order','DESC') : '';


        $query = Floor::select('floors.*')->with('building');

        if(!empty($status)){
            $query->where('floors.status',$status);
        }

        if(!empty($search)){
            $searchFields = array('floors.floor_no');
            $query->where( function($query) use($searchFields, $search){
                foreach($searchFields as $field) {
                    $query->orWhere($field, 'LIKE', '%'.$search.'%');
                }
            });
        }

        if($id > 0){
            $query->where('floors.id',$id);
        }

        if(!empty($order_by) && !empty($order)){
            $query->orderBy('floors.'.$order_by,$order);
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

    public function building(){
        return $this->belongsTo(Building::class,'building_id');
    }

}
