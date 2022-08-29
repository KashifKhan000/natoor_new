<?php

namespace App\Http\Controllers\API\V1\Buildings;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\LanguagesContents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BuildingsController extends Controller
{
    protected $data = ['message' => '','response' => [],'errors' => [],'status' => 200];

   
    public function getuserbuilding()
    {
        $user = Building::where(['buildings.tenant_id'=>Auth::user()->tenant_id])->get();
        // dd(['buildings.tenant_id'=>Auth::user()->tenant_id]);
        if (!empty($user)) {
            $response = ['status' => true, 'data' => $user];
            return response($response, 200);
        }
        $response = ['status' => false, 'data' => ''];
        return response($response, 404);
    }


    public function getallbuildings(Request $request)
    {

        $inputPage = $request->total_post;
        $lang = $request->lang_code;

        $limit = $request->limit ?: 15;
        $page               = ($inputPage > 0) ? $inputPage : 1;
        $offset             = ($page - 1) * $limit;

        $forum = Building::with(['images','building_aminity'])
        ->select('buildings.*', 'languages_contents.content_title', 'languages_contents.content_title')
        ->leftJoin('languages_contents', function ($join) use($lang) { 
            $join->on('languages_contents.item_id', '=', 'buildings.id')
            ->where('languages_contents.item_type', 'Building')
            ->where('languages_contents.lang_code', $lang);
        })->where(['buildings.tenant_id'=>Auth::user()->tenant_id]);

        $totalpages = ceil($forum->count() / $limit);

        // limit
        if ($page > 1) {
            $forum->skip($offset)->take($limit);
        } else {
            $forum->skip(0)->take($limit);
        }

        $Records = $forum->get();

        // dd($Reco/rds);
        foreach($Records as $key => $value){ 
            $values = [];
            foreach($value->building_aminity as $key1 => $value1){
                $query = LanguagesContents::select('content_title')->leftJoin('aminities', function ($joins){
                    $joins->on('aminities.id', '=', 'languages_contents.item_id')->where('aminities.status', 'Active');
                })->where(['item_id' => $value1->aminity_id, 'item_type'=> 'Aminity', 'lang_code' => $lang])->first();
                $value1->content_title = $query->content_title;
            }
        }
    
        $paginate    = [
            'page' => $page,
            'count' => $Records->count(),
            'limit' => $limit,
            'Offset' => $offset,
            'Total_pages' => $totalpages
        ];
        
        $data = [
            'pagination' => $paginate,
            'response' => $Records
        ];
        if (!empty($forum)) {
            $response = ['status' => true, 'data' => $data];
            return response($response, 200);
        }
        $response = ['status' => false, 'data' => ''];
        return response($response, 200);
    }
    // building validation

    protected function validateBuilding($request){
        return Validator::make($request->all(),[
            'name_en' => 'string',
            'name_ar' => 'string',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'country_id' => 'required|integer',
            'city_id'   => 'required|integer',
        ]);
    }

    // save building
    public function saveBuilding(Request $request){
        $validate = $this->validateBuilding($request);
        if($validate->fails()){
            $this->data['errors'] = $validate->errors()->all();
            $this->data['status'] = 422;
        }else{
            $building = new Building();
            $response = $building->saveBuilding($request);
            if($response){
                $this->data['message'] = 'Building saved successfully';
                $this->data['status'] = 200;
            }else{
                $this->data['errors'] = ['Error saving building'];
                $this->data['status'] = 500;
            }
        }
        return response()->json($this->data);
    }

    // update building
    public function updateBuilding(Request $request,$id){
        if($id > 0){
            $validate = $this->validateBuilding($request);
            if($validate->fails()){
                $this->data['errors'] = $validate->errors()->all();
                $this->data['status'] = 422;
            }else{
                $building = new Building();
                $response = $building->updateBuilding($request,$id);
                if($response){
                    $this->data['message'] = 'Building updated successfully';
                    $this->data['status'] = 200;
                }else{
                    $this->data['errors'] = ['Error updating building'];
                    $this->data['status'] = 500;
                }
            }
        }else{
            $this->data['errors'] = ['Error resolving id'];
            $this->data['status'] = 404;
        }
        return response()->json($this->data);
    }
    
     // get building
    public function getBuilding(Request $request,$id=""){
        $building = new Building();
        $this->data['response'] = $building->getBuilding($request,$id);
        return response()->json($this->data);
    }

    // delete building
    public function deleteBuilding($id){
        if($id > 0){
            $building = new Building();
            $response = $building->deleteBuilding($id);
            if($response){
                $this->data['message'] = 'Building Deleted successfully';
                $this->status = 200;
            }else{
                $this->data['errors'] = ['Unable to delete building'];
                $this->status = 500;
            }
        }else{
            $this->data['errors'] = ['Error resolving id'];
            $this->status = 404;
        }
        return response()->json($this->data);

    }
}
