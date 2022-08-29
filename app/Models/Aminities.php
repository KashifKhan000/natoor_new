<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aminities extends Model
{
    protected $table = 'aminities';
    protected $perPage = 15;
    protected $locale  = 'en';

    public function saveAminities($request = array()){
        $aminities = new Aminities();
        $aminities->status = $request->input('status','Active');
        if($aminities->save()){
            // save english
            saveLanguage('en',$request->input('name_en',''),$aminities->id,'Aminity');
            // save arabic
            saveLanguage('ar',$request->input('name_ar',''),$aminities->id,'Aminity');
            return true;
        }else{
            return false;
        }
    }

    public function updateAminities($request = array(),$id = ""){
            $aminities = Aminities::find($id);
            $aminities->status = $request->input('status','');
            if($aminities->save()){
            // Englsih
            updateLanguage('en',$request->input('name_en',''),$aminities->id,'Aminity');
            // arabic
            updateLanguage('ar',$request->input('name_ar',''),$aminities->id,'Aminity');
            return true;
        }else{
            return false;
        }
    }

    public function deleteAminities($id = ''){
        if($id > 0){
            $aminities = Aminities::find($id);
            if(!empty($aminities)){
                $aminities->delete();
                return true;
            }else{
                return false;
            }
        }
    }

    // public function getAminityById($id = ""){
    //     $aminities = Aminities::where('id',$id)->with('languages_contents')->first();
    //     return $aminities;
    // }

    public function getAminities($request = array(),$id = ""){
        $status = $request->has('status') ? $request->input('status','') : '';
        $search = $request->has('search') ?  $request->input('search','') : '';
        $lang_code = $request->has('lang_code') ?  $request->input('lang_code','') : 'en';
        $order_by = $request->has('order_by') ?  $request->input('order_by','id') : '';
        $order = $request->has('order') ? $request->input('order','DESC') : '';

        $query = Aminities::select('aminities.*','lcontent.content_title');
        $condition = [];
        if(!empty($lang_code)){
            $condition = ['lcontent.lang_code' => $lang_code];
        }
        $query->leftJoin('languages_contents as lcontent',function($join) use($lang_code,$condition){
            $join->on('lcontent.item_id','aminities.id')->where(['lcontent.item_type'=>'Aminity'])->where($condition);
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
            $query->where('aminities.id',$id);
        }

        if(!empty($order_by) && !empty($order)){
            $query->orderBy('aminities.'.$order_by,$order);
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

    public function languages_contents(){
        return $this->hasMany(LanguagesContents::class,'item_id')->where(['item_type' => 'Aminity']);
    }
}
