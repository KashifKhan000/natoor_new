<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Directory extends Model
{
    use BelongsToTenant;

    protected $table = 'service_providers';
    protected $perPage = 15;
    protected $locale = 'en';

    public function saveDirectory($request = array()){
        $directory = new Directory();
        $directory->name = $request->input('name','');
        $directory->phone_no = $request->input('phone_no','');
        $directory->address = $request->input('address','');
        $directory->status = $request->input('status','');
        if($directory->save()){
            if($request->has('services_id')){
                $services_ids = $request->input('services_id','');
                ServicesServiceProviders::whereIn('id',$services_ids)->delete();
                foreach($services_ids as $service_id){
                    $ids_relations = new ServicesServiceProviders();
                    $ids_relations->services_id = $service_id;
                    $ids_relations->service_providers_id = $directory->id;
                    $ids_relations->save();
                }
            }
            if($request->has('persons')){
                $persons = $request->input('persons');
                foreach($persons as $person){
                    $contacts = new ServiceProviderContact();
                    $contacts->full_name = $person['full_name'];
                    $contacts->contact_no = $person['contact_no'];
                    $contacts->comment = $person['comment'];
                    $contacts->service_providers_id = $directory->id;
                    $contacts->save();
                }
            }
            return true;
        }else{
            return false;
        }
    }

    public function deleteDirectory($id){
        $directory = Directory::find($id);
        if(!empty($directory)){
            $directory->delete();
            return true;
        }else{
            return false;
        }
    }

    public function updateDirectory($request = array(),$id = "")
    {
        $directory = Directory::find($id);
        $directory->name = $request->input('name','');
        $directory->phone_no = $request->input('phone_no','');
        $directory->address = $request->input('address','');
        $directory->status = $request->input('status','');
        if($directory->save()){
            if($request->has('services_id')){
                $services_ids = $request->input('services_id','');
                ServicesServiceProviders::whereIn('id',$services_ids)->delete();
                foreach($services_ids as $service_id){
                    $ids_relations = new ServicesServiceProviders();
                    $ids_relations->services_id = $service_id;
                    $ids_relations->service_providers_id = $id;
                    $ids_relations->save();
                }
            }
            if($request->has('persons')){
                $persons = $request->input('persons');
                foreach($persons as $person){
                    $contacts = new ServiceProviderContact();
                    if(!empty($person['id'])){
                        $contacts = ServiceProviderContact::find($person['id']);
                    }
                    $contacts->full_name = $person['full_name'];
                    $contacts->contact_no = $person['contact_no'];
                    $contacts->comment = $person['comment'];
                    $contacts->service_providers_id = $id;
                    $contacts->save();
                }
            }
            return true;
        }else{
            return false;
        }
    }

    public function getDirectory($request = array(),$id = ""){
        $status = $request->has('status') ? $request->input('status','') : '';
        $search = $request->has('search') ?  $request->input('search','') : '';
        $lang_code = $request->has('lang_code') ?  $request->input('lang_code','') : 'en';
        $order_by = $request->has('order_by') ?  $request->input('order_by','id') : '';
        $order = $request->has('order') ? $request->input('order','DESC') : '';


        $query = Directory::select('service_providers.*');


        if(!empty($status)){
            $query->where('service_providers.status',$status);
        }

        if(!empty($search)){
            $searchFields = array('name','phone_no');
            $query->where( function($query) use($searchFields, $search){
                foreach($searchFields as $field) {
                    $query->orWhere('service_providers.'.$field, 'LIKE', '%'.$search.'%');
                }
            });
        }

        if($id > 0){
            $query->where('service_providers.id',$id);
        }

        if(!empty($order_by) && !empty($order)){
            $query->orderBy('service_providers.'.$order_by,$order);
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
