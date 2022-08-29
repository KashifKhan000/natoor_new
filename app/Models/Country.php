<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'countries';

    public function saveCountry($request = array()){
        $name_en = $request->input('name_en','');
        $name_ar = $request->input('name_ar','');
        $status = $request->input('status','Active');
        $country = new Country();
        $country->status = $status;
        if($country->save()){
            saveLanguage('en',$name_en,$country->id,'Country');
            saveLanguage('ar',$name_ar,$country->id,'Country');
            return true;
        }
        else{
            return false;
        }
    }

    public function updateCountries($request = array(),$id = ""){
        $name_en = $request->input('name_en','');
        $name_ar = $request->input('name_ar','');
        $status = $request->input('status','Active');
        $country = Country::find($id);
        $country->status = $status;
        if($country->save()){
            updateLanguage('en',$name_en,$id,'Country');
            // arabic
            updateLanguage('ar',$name_ar,$id,'Country');
            return true;
        }else{
            return false;
        }
    }

    public function getCountries($request = array(),$id = ""){
        $status = $request->has('status') ? $request->input('status','') : '';
        $search = $request->has('search') ?  $request->input('search','') : '';
        $lang_code = $request->has('lang_code') ?  $request->input('lang_code','') : 'en';
        $order_by = $request->has('order_by') ?  $request->input('order_by','id') : '';
        $order = $request->has('order') ? $request->input('order','DESC') : '';


        $query = Country::select('countries.*','lcontent.content_title');
        $condition = [];
        if(!empty($lang_code)){
            $condition = ['lcontent.lang_code' => $lang_code];
        }
        $query->leftJoin('languages_contents as lcontent',function($join) use($lang_code,$condition){
            $join->on('lcontent.item_id','countries.id')->where(['lcontent.item_type'=>'Country'])->where($condition);
        });

        if(!empty($status)){
            $query->where('status',$status);
        }

        if(!empty($search)){
            $searchFields = array('lcontent.content_title');
            $query->where( function($query) use($searchFields, $search){
                foreach($searchFields as $field) {
                    $query->orWhere($field, 'LIKE', '%'.$search.'%');
                }
            });
        }

        if($id > 0){
            $query->where('countries.id',$id);
        }

        if(!empty($order_by) && !empty($order)){
            $query->orderBy('countries.'.$order_by,$order);
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

    public function deleteCountry($id){
        $country = Country::find($id);
        if(!empty($country)){
            $country->delete();
            return true;
        }else{
            return false;
        }
    }

}
