<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\Multitenantable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Str;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
        'gender',
        'dob',
        'email',
        'password',
        'dns',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */

    //  public function tenant()
    // {
    //     return $this->hasOne(Domain::class, 'tenant_id', 'tenant_id');
    // }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function saveUserAccount($request = array())
    {
        $user = new User();
        $image_url           = $request->input('image_url');
        $user->first_name    = $request->input('first_name', '');
        $user->last_name     = $request->input('last_name', '');
        $user->email         = $request->input('email', '');
        $user->password      = Hash::make($request->input('password', ''));
        $user->username      = $request->input('username', '');
        $user->dob           = $request->input('dob', '');
        $user->type          = $request->input('type', '');
        $user->fcm_token     = $request->input('fcm_token', '');
        $user->gender        = $request->input('gender', '');
        $user->mobile_number = $request->input('mobile_number', '');
        $user->status        = $request->input('status', '');
        $user->dns        = $request->input('dns', '');
        if ($request->has('company_name')) {
            $user->company_name = $request->input('company_name', '');
        }
        if ($request->has('country_id')) {
            $user->country_id = $request->input('country_id', '');
        }
        if ($request->has('city_id')) {
            $user->city_id = $request->input('city_id', '');
        }
        if ($request->has('tenant_id')) {
            $user->tenant_id = 'rooms';
        }
        $user->address = $request->input('address', '');
        $user->company_identifier = $this->getUniqueIdentifier();
        if ($user->save()) {
            if ($request->has('image_url')) {
                saveImages($image_url, $user->id, 'User');
            }
            return $user;
        } else {
            return false;
        }
    }

    public function updateProfile($request = array())
    {
        $id = Auth::user()->id;
        $profile = User::find($id);
        $profile->first_name = $request->input('first_name');
        $profile->last_name = $request->input('last_name');
        $profile->username = $request->input('username');
        $profile->gender = $request->input('gender', '');
        $profile->mobile_number = $request->input('mobile_number', '');
        if ($request->has('status')) {
            $profile->status = $request->input('status', '');
        }

        if ($request->has('fcm_token')) {
            $profile->fcm_token = $request->input('fcm_token', '');
        }

        if ($request->has('company_name')) {
            $profile->company_name = $request->input('company_name', '');
        }

        if ($request->has('country_id')) {
            $profile->country_id = $request->input('country_id', '');
        }

        if ($request->has('city_id')) {
            $profile->city_id = $request->input('city_id', '');
        }

        if ($request->has('image_url')) {
            updateImages($request->input('image_url'), $id, 'User');
        }

        if ($profile->save()) {
            return true;
        } else {
            return false;
        }
    }

    protected function getUniqueIdentifier()
    {
        $str = Str::random(6);
        if ($this->checkIfExists($str)) {
            $this->getUniqueIdentifier();
        }
        return $str;
    }

    protected function checkIfExists($code = '')
    {
        return User::where('company_identifier', $code)->exists();
    }

    public function tenant()
    {
        return $this->hasOne(Domain::class, 'tenant_id', 'tenant_id');
    }
}
