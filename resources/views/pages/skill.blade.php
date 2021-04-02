@extends('layouts.app')

@section('content')
    <div class="header py-7 py-lg-8 p-5">
        <div class="row">
            @forelse ($activities as $activity)
                <div class="my-3 p-3 bg-white rounded box-shadow">
                    <h3 class="card-title">{{ $activity['title'] }}</h3>
                    <h6 class="card-subtitle mb-3 text-muted">{{ $activity['skill_name'] }}</h6>
                    <p class="card-text">{{ $activity['description'] }}</p>
                    <p>
                        From: <span class="text-muted">{{ $activity['start_date'] }}</span> to <span class="text-muted">{{ $activity['end_date'] }}</span>
                    </p>
                    <p class="mt-4 mb-2">
                        <span class="lead">Participants:</span>
                    </p>
                    <ul>
                        @forelse($activity['participants'] as $participant)
                            <li>{{ $participant['name'] }}</li>
                            <ul class="mb-1">
                                <li><small>{{ $participant['profileName'] }}</small></li>
                                <li><small>{{ $participant['skillName'] }}</small></li>
                            </ul>
                        @empty
                            <p>No participants in this activity</p>
                        @endforelse
                    </ul>
                </div>
            @empty
                <p>No activity for this skill.</p>
            @endforelse
        </div>

    </div>
@endsection


