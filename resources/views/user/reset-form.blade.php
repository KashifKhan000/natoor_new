@extends('user.userlayout')
@section('content')
<div class="limiter">
    <div class="container-login100 bacu">
        <div class="wrap-login100">

            <form class="login100-form validate-form" action="/updatePass" method="POST">
                @csrf
                <input type="hidden" name="password_token" value="{{$token}}" />
                <span class="login100-form-title p-b-34 p-t-27">
                    Change Password
                </span>
                @if(Session::has('success'))
                <div class="alert alert-success m-4" style="width: 90%;">
                    {{ Session::get('success')}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger m-4 text-center ml-3">
                    {{ session('error')}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
                <div class="wrap-input100 validate-input" data-validate="Enter Email">
                    <input class="input100" type="email" name="email" placeholder="Enter Your Email" value="{{$email}}">
                    <span class="focus-input100" data-placeholder="&#xf191;"></span>
                </div>
                <div class="wrap-input100 validate-input" data-validate="New Password">
                    <input class="input100" type="password" name="password" placeholder="New Password">
                    <span class="focus-input100" data-placeholder="&#xf191;"></span>
                </div>

                <div class="wrap-input100 validate-input" data-validate="Confirm Password">
                    <input class="input100" type="password" name="c_password" placeholder="Confirm Password">
                    <span class="focus-input100" data-placeholder="&#xf191;"></span>
                </div>

                <div class="container-login100-form-btn">
                    <button class="login100-form-btn" type="submit">
                        Save
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>


<div id="dropDownSelect1"></div>


@endsection