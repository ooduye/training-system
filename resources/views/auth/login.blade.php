@extends('layouts.app', ['class' => 'login-page', 'contentClass' => 'login-page'])

@section('content')
    <form class="form" method="post" action="{{ route('login') }}">
        @csrf

        <div class="wrapper fadeInDown">
            <div id="formContent">
                <!-- Tabs Titles -->

                <!-- Icon -->
                <div class="p-3 fadeIn first">
                    <i class="fa-5x fas fa-user"></i>
                </div>

                <!-- Login Form -->
                <form>
                    <input type="text" id="username" class="fadeIn second" name="username" placeholder="login">
                    <input type="password" id="password" class="fadeIn third" name="password" placeholder="password">
                    <input type="submit" class="fadeIn fourth" value="Log In">
                </form>



            </div>
        </div>
    </form>
@endsection
