<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Mail\email;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    protected $data = ['message' => '','response' => [],'errors' => [],'status' => 200];

    // validate login
    protected function validateLogin($request = array()){
        return Validator::make($request->all(),[
            'email'         => 'required|string|email',
            'password'      => 'required|string|min:8',
            'company_identifier'    => '',
            'type'          => 'required|integer',
        ]);
    }

    // login
    public function login(Request $request){
        // dd(request()->getHttpHost());
        $validator = $this->validateLogin($request);
        if($validator->fails()){
            $this->data['errors'] = $validator->errors()->all();
            $this->data['status'] = 422;
        }else{
            // if(empty($request->input('company_identifier','')) && $request->input('type') == 0){

            // } else if(!empty($request->input('company_identifier','')) && $request->input('type') == 1) {
            //     if(!$token = auth('apiUser')->attempt($validator->validated())){
            //         $this->data['errors'] = ['Unauthorized'];
            //         $this->data['status'] = 401;
            //     }else{
            //         $user = auth('apiUser')->user();
            //         $this->data['response'] = $this->createNewToken($token,$user);
            //     }
            // }else if(!empty($request->input('company_identifier','')) && $request->input('type') == 2){
            //     if(!$token = auth('apiUser')->attempt($validator->validated())){
            //         $this->data['errors'] = ['Unauthorized'];
            //         $this->data['status'] = 401;
            //     }else{
            //         $user = auth('apiUser')->user();
            //         $this->data['response'] = $this->createNewToken($token,$user);
            //     }
            // }else{
            //     $this->data['errors'] = ['Wrong Username or Password!'];
            //     $this->data['status'] = 404;
            // }

            if(!$token = auth('api')->attempt($validator->validated())){
                $this->data['errors'] = ['Unauthorized'];
                $this->data['status'] = 401;
            }else{
                $user = auth('api')->user();
                $this->data['response'] = $this->createNewToken($token,$user);
            }
        }
        return response()->json($this->data);
    }

    // protected function validateForget($request){
    //     return Validator::make($request->all(),[
    //         'email' => 'required|email|string'
    //     ]);
    // }

    

    public function reset_form(Request $request)
    {
        $token = $request->segment(3);
        $email = $request->email;
        return view('user.reset-form', compact('token', 'email'));
    }

    ////// API FOR FORGET PASSWORD //////
    public function checkemail(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'email' => [
                'required', 'email:rfc',
                function ($attribute, $value, $fail) {
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $fail($attribute . ' is invalid.');
                    }
                }
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([

                'status' => false,
                'message' => $validator->messages()->get('email')[0],
            ], 200);
        }
        
        $email = $req->email;
        $emailcheck = User::where('email', $email)->count();
        if ($emailcheck < 0) {

            return redirect()->back()->with('error', 'Email not Found!');
        }
        //Create Password Reset Token
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => Str::random(60),
            'created_at' => Carbon\Carbon::now()
        ]);

        $tokenData = DB::table('password_resets')
            ->where('email', $email)->first();

        if ($this->sendResetEmail($email, $tokenData->token)) {
            $response = ['status' => 200, 'message' => "Mail Has Been Send To Your Email"];
            return response($response, 200);
        } else {
            $response = ['status' => 422, 'message' => "Your Email Does not Exist!"];
            return response($response, 422);
        }
    }

    private function sendResetEmail($email, $token)
    {
        //Retrieve the user from the database
        $user = DB::table('users')->where('email', $email)->first();
        if ($user) {
            //Generate, the password reset link. The token generated is embedded in the link
            $link = url('/') . '/password/reset/' . $token . '?email=' . urlencode($user->email);

            try {
                Mail::to($email)->send(new email($link));
                return true;
            } catch (\Exception $e) {
                dd($e);
            }
        } else {
            $response = ['status' => false, 'message' => "Email Not Found"];
            return response($response);
        }
    }

    public function resetPassword(Request $request)
    {
        //Validate input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
            'password' => 'required',
            'password_token' => 'required'
        ]);

        //check if payload is valid before moving on
        if ($validator->fails()) {
            return redirect()->back()->withErrors(['email' => 'Email Not Found']);
        }

        $password = $request->password;
        // Validate the token
        $tokenData = DB::table('password_resets')
            ->where('token', $request->password_token)->first();
        // Redirect the user back to the password reset request form if the token is invalid
        if (!$tokenData) return view('auth.passwords.email');

        $user = User::where('email', $tokenData->email)->first();
        // Redirect the user back if the email is invalid
        if (!$user) return redirect()->back()->withErrors(['email1' => 'Email not found']);
        //Hash and update the new password
        $user->password = Hash::make($password);
        $user->update(); //or $user->save();

        //Delete the token
        DB::table('password_resets')->where('email', $user->email)
            ->delete();

        //Send Email Reset Success Email
        if ($user) {
            return redirect('/success')->with('message', 'Your Password Has Been Changed!');
        } else {
            return redirect()->back()->withErrors(['error' => trans('A Network Error occurred. Please try again')]);
        }
    }

    public function success()
    {
        return view('welcome');
    }


    protected function validateProfile($request = array()){
        return Validator::make($request->all(),[
            'first_name' => 'required|string',
            'last_name'  => 'required|string',
            'username'   => 'required|string',
            'gender'     => 'string',
            'mobile_number' => 'required|integer',
            'dob'        => 'required|date_format:Y-m-d',
        ]);
    }
    // update Profile
    public function updateProfile(Request $request){
        $validate = $this->validateProfile($request);
        if($validate->fails()){
            $this->data['errors'] = $validate->errors()->all();
            $this->data['status'] = 422;
        }else{
            $profile = new User();
            $response = $profile->updateProfile($request);
            if($response){
                $this->data['message'] = 'Profile updated successfully';
                $this->data['status'] = 200;
            }else{
                $this->data['errors'] = ['Error while updating profile'];
                $this->data['status'] = 500;
            }
        }
        return response()->json($this->data);
    }

    // add admin account

    protected function validateUserAccount($request = array()){
        return Validator::make($request->all(),[
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => [
                'required',
                Rule::unique('users')->where('dns', request()->getHttpHost()),
            ],
            'username' => [
                'required',
                Rule::unique('users')->where('dns', request()->getHttpHost()),
            ],
            'password' => 'required|min:8',
            'type'    => 'required|integer',
            'status'   => 'required|string',
            'company_name' => 'string|unique:users,company_name,except,id',
            'dns'  => 'required|string',
            'country_id' => 'required|integer',
            'city_id' => 'required|integer',
            'address' => 'required|string',
            'image_url' => 'required|string',
            'latitude' => 'string',
            'longitude' => 'string',
        ]);
    }

    public function addAdmin(Request $request){
        $validate = $this->validateUserAccount($request);

        if($validate->fails()){
            $this->data['errors'] = $validate->errors()->all();
            $this->data['status'] = 422;
        }else{
            $user = new User();
            $response = $user->saveUserAccount($request);
            if($response){
                
                $this->data['user']=  $response;
            
                $this->data['message'] = 'User saved successfully';
            }else{
                $this->data['message'] = 'Error while saving user account';
                $this->data['status'] = 500;
            }
        }
        return response()->json($this->data);
    }


    // logout
    public function logout(){
        if(Auth::check()){
            auth()->logout();
            $this->data['status'] = 200;
            $this->data['message'] = 'User successfully signed out';
        }else{
            $this->data['status'] = 401;
            $this->data['errors'] = ['Unauthorized'];
        }

        return response()->json($this->data);
    }

    // create json web token
    protected function createNewToken($token,$user = array()){
        // $x[] = ;
        // $user = $user;
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => env('JWT_TTL',null),
            'user' => $user, $user->tenant->domain,
        ];
    }

    
    public function updatepassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation fails',
                'errors' => $validator->errors()
            ], 200);
        }

            $id = Auth::guard('api')->user()->id;
            $user = User::find($id);
        if($user)
        {
            if (Hash::check($request->old_password, $user->password)) {
                $user->update([
                    'password' => Hash::make($request->password)
                ]);
                return response()->json([
                    'status' => true,
                    'message' => 'password Successfully updated',
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Old password does not matched',
                ], 200);
            }
        }
        else
        {
            return response()->json([
                    'status' => false,
                    'message' => 'ID Not Found',
                ], 200);
        }
    }
    
}
