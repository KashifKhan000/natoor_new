<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $table = 'Packages';
    protected $perPage = 15;
    protected $locale = 'en';

    // SAVE PACKAGES
    public function savePackage($request = array()){
        $package = new Package();
        $package->no_of_users = $request->input('no_of_users','');
        $package->price = $request->input('price','');
        $package->discount = $request->input('discount','');
        $package->duration = $request->input('duration','');
        $package->status = $request->input('status','');
        $package->description = $request->input('description','');
        if($package->save()){
            // save english
            saveLanguage('en',$request->input('title_en',''),$package->id,'Package');
            // save arabic
            saveLanguage('ar',$request->input('title_ar',''),$package->id,'Package');
            return true;
        }else{
            return false;
        }

    }

    // UPDATE PACKAGES

    public function updatePackage($request = array(),$id){
        $package = Package::find($id);
        $package->no_of_users = $request->input('no_of_users','');
        $package->price = $request->input('price','');
        $package->discount = $request->input('discount','');
        $package->status = $request->input('status','');
        $package->description = $request->input('description','');
        if($package->save()){
            // Englsih
            updateLanguage('en',$request->input('title_en',''),$id,'Package');
            // arabic
            updateLanguage('ar',$request->input('title_ar',''),$id,'Package');
            return true;
        }else{
            return false;
        }
    }

    // GET PACKAGES

    public function getPackage($request = array(),$id = ''){

        $status = $request->has('status') ? $request->input('status','') : '';
        $search = $request->has('search') ?  $request->input('search','') : '';
        $lang_code = $request->has('lang_code') ?  $request->input('lang_code','') : 'en';
        $order_by = $request->has('order_by') ?  $request->input('order_by','id') : '';
        $order = $request->has('order') ? $request->input('order','DESC') : '';

        $condition = [];
        if(!empty($lang_code)){
            $condition = ['lcontent.lang_code' => $lang_code];
        }
        $query = Package::select('packages.*','lcontent.content_title');

        $query->leftJoin('languages_contents as lcontent',function($join) use($condition){
            $join->on('lcontent.item_id','packages.id')->where(['lcontent.item_type'=>'Package'])->where($condition);
        });

        // $query->leftJoin('languages_contents as lcontent_country',function($join) use($lang_code,$condition_country){
        //     $join->on('lcontent_country.item_id','cities.country_id')->where(['lcontent_country.item_type'=>'Country'])->where($condition_country);
        // });

        if(!empty($status)){
            $query->where('packages.status',$status);
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
            $query->where('packages.id',$id);
        }

        if(!empty($order_by) && !empty($order)){
            $query->orderBy('packages.'.$order_by,$order);
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

    // DELETE PACKAGES

    public function deletePackage($id){
        $package = Package::find($id);
        if(!empty($package)){
            $package->delete();
            return true;
        }else{
            return false;
        }
    }
}
