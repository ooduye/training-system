@extends('layouts.app')

@section('content')
    <div class="header py-7 py-lg-8 p-5">
        <div class="container">
            <div class="header-body text-center mb-7">
                <div class="row justify-content-center">
                    <div class="col-lg-5 col-md-6">
                        <h1 class="">Welcome
                            @if(session()->has('profile'))
                                <span class="text-capitalize">{{ session('profile') }}</span> User
                            @endif
                            !
                        </h1>
                        <p class="text-lead mt-3">
                            The Training System for Country Delegation will control the training activities for each skill where this country will participate in a competition
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
