<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';

    public function saveCity($request = array()){
        $name_en = $request->input('name_en','');
        $name_ar = $request->input('name_ar','');
        $country_id = $request->input('country_id','');
        $status = $request->input('status','Active');
        $city = new City();
        $city->status = $status;
        $city->country_id = $country_id;
        if($city->save()){
            // save english content
            saveLanguage('en',$name_en,$city->id,'City');
            // save arabicn content
            saveLanguage('ar',$name_ar,$city->id,'City');
            return true;
        }
        else{
            return false;
        }
    }

    public function updateCities($request = array(),$id = ""){
        $name_en = $request->input('name_en','');
        $name_ar = $request->input('name_ar','');
        $country_id = $request->input('country_id','');
        $status = $request->input('status','Active');
        $city = City::find($id);
        $city->status = $status;
        $city->country_id = $country_id;
        if($city->save()){
            // update english
            updateLanguage('en',$name_en,$id,'City');
            // arabic
            updateLanguage('ar',$name_ar,$id,'City');
            return true;
        }else{
            return false;
        }
    }

    public function getCity($request = array(),$id = ""){
        $status = $request->has('status') ? $request->input('status','') : '';
        $search = $request->has('search') ?  $request->input('search','') : '';
        $lang_code = $request->has('lang_code') ?  $request->input('lang_code','') : 'en';
        $order_by = $request->has('order_by') ?  $request->input('order_by','id') : '';
        $order = $request->has('order') ? $request->input('order','DESC') : '';


        $query = City::select('cities.*','lcontent_city.content_title','lcontent_country.content_title');
        $condition_city = [];
        $condition_country = [];
        if(!empty($lang_code)){
            $condition_city = ['lcontent_city.lang_code' => $lang_code];
            $condition_country = [ 'lcontent_country.lang_code' => $lang_code ];
        }
        $query->leftJoin('languages_contents as lcontent_city',function($join) use($lang_code,$condition_city){
            $join->on('lcontent_city.item_id','cities.id')->where(['lcontent_city.item_type'=>'City'])->where($condition_city);
        });

        $query->leftJoin('languages_contents as lcontent_country',function($join) use($lang_code,$condition_country){
            $join->on('lcontent_country.item_id','cities.country_id')->where(['lcontent_country.item_type'=>'Country'])->where($condition_country);
        });

        if(!empty($status)){
            $query->where('status',$status);
        }

        if(!empty($search)){
            $searchFields = array('lcontent_city.content_title','lcontent_country.content_title');
            $query->where( function($query) use($searchFields, $search){
                foreach($searchFields as $field) {
                    $query->orWhere($field, 'LIKE', '%'.$search.'%');
                }
            });
        }

        if($id > 0){
            $query->where('cities.id',$id);
        }

        if(!empty($order_by) && !empty($order)){
            $query->orderBy('cities.'.$order_by,$order);
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

    public function deleteCity($id){
        $city = City::find($id);
        if(!empty($city)){
            $city->delete();
            return true;
        }else{
            return false;
        }
    }
}
