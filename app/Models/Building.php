<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Building extends Model
{
    use BelongsToTenant;

    public function saveBuilding($request = array()){
        $building = new Building();
        $building->latitude = $request->input('latitude','');
        $building->longitude = $request->input('longitude','');
        $building->lat_long = $request->input('latitude','') .','.$request->input('longitude','');
        $building->country_id = $request->input('country_id','');
        $building->city_id = $request->input('city_id','');
        $building->address = $request->input('address','');
        $images_url = $request->input('images_url','');
        if($building->save()){
            // save english
            saveLanguage('en',$request->input('name_en',''),$building->id,'Building');
            // save arabic
            saveLanguage('ar',$request->input('name_ar',''),$building->id,'Building');
            // save images urls
            saveImages($images_url,$building->id,'Building');
            return true;
        }else{
            return false;
        }
    }

    public function getBuilding($request = array(),$id){
        $status =  $request->input('status','');
        $search =  $request->input('search','');
        $lang_code =  $request->input('lang_code','en');
        $order_by = $request->input('order_by','id');
        $order = $request->input('order','DESC');
        $query = Building::select('buildings.*','lcontent.content_title','lcontent_country.content_title as country_name','lcontent_city.content_title as city_name')->with('images');
        $condition = [];
        $condition_country = [];
        $condition_city = [];
        if(!empty($lang_code)){
            $condition = ['lcontent.lang_code' => $lang_code];
            $condition_country = ['lcontent_country.lang_code' => $lang_code];
            $condition_city = ['lcontent_city.lang_code' => $lang_code];
        }

        $query->leftJoin('languages_contents as lcontent',function($join) use($lang_code,$condition){
            $join->on('lcontent.item_id','buildings.id')->where(['lcontent.item_type'=>'Building'])->where($condition);
        });
        // country
        $query->leftJoin('languages_contents as lcontent_country',function($join) use($lang_code,$condition_country){
            $join->on('lcontent_country.item_id','buildings.country_id')->where(['lcontent_country.item_type'=>'Country'])->where($condition_country);
        });
        // city
        $query->leftJoin('languages_contents as lcontent_city',function($join) use($lang_code,$condition_city){
            $join->on('lcontent_city.item_id','buildings.city_id')->where(['lcontent_city.item_type'=>'City'])->where($condition_city);
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
            $query->where('buildings.id',$id);
        }

        if(!empty($order_by) && !empty($order)){
            $query->orderBy('buildings.'.$order_by,$order);
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

    public function updateBuilding($request = array(), $id){
        $building = Building::find($id);
        $building->latitude = $request->input('latitude','');
        $building->longitude = $request->input('longitude','');
        $building->lat_long = $request->input('latitude','') .','.$request->input('longitude','');
        $building->country_id = $request->input('country_id','');
        $building->city_id = $request->input('city_id','');
        $building->address = $request->input('address','');
        $images_url = $request->input('images_url','');
        if($building->save()){
            // Englsih
            updateLanguage('en',$request->input('name_en',''),$id,'Building');
            // arabic
            updateLanguage('ar',$request->input('name_ar',''),$id,'Building');
            // update images
            updateImages($images_url,$id,'Building');
            return true;
        }else{
            return false;
        }
    }

    public function deleteBuilding($id){
        $building = Building::find($id);
        if(!empty($building)){
            $building->delete();
            return true;
        }else{
            return false;
        }
    }

    public function images(){
        return $this->hasMany(MediaFiles::class,'item_id')->where(['item_type' => 'Building' ]);
    }

    public function content(){
        return $this->hasMany(LanguagesContents::class,'item_id')->where(['item_type' => 'Building' ]);
    }

    public function country(){
        return $this->belongsTo(Country::class,'country_id');
    }

    public function city(){
        return $this->belongsTo(City::class,'city_id');
    }


    public function building_aminity()
    {

        return $this->hasMany(BuildingAminity::class,'building_id', 'id');

    }
}
     