@extends('layouts.app')

@section('content')
    <div class="header py-7 py-lg-8 p-5">
        <div class="row">
            @forelse ($skills as $skill)
                <div class="col-md-3">
                    <div class="card fadeIn position-relative shadow-sm">
                        <div class="card-body">
                            <a href="/skill/{{ $skill['id'] }}" class="stretched-link text-dark">{{ $skill['name'] }}</a>
                        </div>
                    </div>
                </div>
            @empty
                <p>No skills.</p>
            @endforelse
        </div>

    </div>
@endsection
