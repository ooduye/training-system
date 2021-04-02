@if(session()->has('user_token'))
    @include('layouts.navbars.navs.auth')
@else
    @include('layouts.navbars.navs.guest')
@endif
