@extends('user.layouts')
@section('content')
<div class="limiter">
    <div class="container-login100 bacu">
        <div class="wrap-login100">
        <?php
                    if (isset($employee->id) && $employee->id != 0) {
                        $url = url('password' . $employee->id ?? '');
                    } else {
                        $url = url('password');
                    }
                    ?>
            <form class="login100-form validate-form" action="{{url($url)}}" method="POST">
                @csrf
                <span class="login100-form-title p-b-34 p-t-27">
                    Change Password
                </span>
                @if(Session::has('success'))
                <div class="alert alert-success m-4" style="    width: 115%; margin-left: -32px !important;">
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
                @if(session('email1'))
                <div class="alert alert-danger m-4 text-center ml-3">
                    {{ session('email1')}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
                @if(session('email'))
                <div class="alert alert-danger m-4 text-center ml-3">
                    {{ session('email')}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
                <div class="wrap-input100 validate-input" data-validate="Enter username">
                    <input class="input100" type="password" name="password" placeholder="Old Password">
                    <span class="focus-input100" data-placeholder="&#xf191;"></span>
                </div>

                <div class="wrap-input100 validate-input" data-validate="Enter password">
                    <input class="input100" type="password" name="new_password" placeholder="New Password">
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