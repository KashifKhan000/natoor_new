<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Services extends Model
{
    use BelongsToTenant;

    protected $table = 'services';
    protected $perPage = 15;
    protected $locale = 'en';

    public function saveService($request = array()){
        $service = new Services();
        $name_en = $request->input('name_en','');
        $name_ar = $request->input('name_ar','');
        $service->status = $request->input('status','Active');
        if($service->save()){
            // english
            saveLanguage('en',$name_en,$service->id,'Service');
            // arabic
            saveLanguage('ar',$name_ar,$service->id,'Service');
            return true;
        }else{
            return false;
        }
    }

    public function updateService($request = array(),$id=""){
        $service = Services::find($id);
        $name_en = $request->input('name_en','');
        $name_ar = $request->input('name_ar','');
        $service->status = $request->input('status','Active');
        if($service->save()){
            // english
            updateLanguage('en',$name_en,$id,'Service');
            // arabic
            updateLanguage('ar',$name_ar,$id,'Service');
            return true;
        }else{
            return false;
        }
    }

    public function deleteService($id = ""){
        $service = Services::find($id);
        if(!empty($service)){
            $service->delete();
            return true;
        }else{
            return false;
        }
    }

    public function getService($request = array(),$id = ""){
        $status = $request->input('status','');
        $search = $request->input('search','');
        $order_by = $request->input('order_by','id');
        $order  = $request->input('order','desc');
        $lang_code = $request->input('lang_code','en');

        $condition = [];

        if(!empty($lang_code)){
            $condition = ['lcontent.lang_code' => $lang_code];
        }

        $query = Services::select('services.*','lcontent.content_title as service_name');

        $query->leftJoin('languages_contents as lcontent',function($join) use($condition){
            $join->on('lcontent.item_id','services.id')->where('lcontent.item_type','Service')->where($condition);
        });

        if($id > 0){
            $query->where('services.id',$id);
        }

        if(!empty($status)){
            $query->where('services.status','');
        }

        if(!empty($search)){
            $searchFields = array('lcontent.content_title');
            $query->where( function($query) use($searchFields, $search){
                foreach($searchFields as $field) {
                    $query->orWhere($field, 'LIKE', '%'.$search.'%');
                }
            });
        }

        if(!empty($order) && !empty($order_by)){
            $query->orderBy('services.'.$order_by,$order);
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
}
