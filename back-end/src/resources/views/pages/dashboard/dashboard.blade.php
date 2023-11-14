@extends('layouts.dashboard')

@section('content')
    <div class="dashboard-page">
        <div class="dashboard-page-title">
            <h1 class="text-white h1 h1-responsive fw-light">Contadores</h1>
        </div>

        <div class="mt-5 d-flex flex-wrap gap-2">
            <div class="card bg-dark text-light shadow-sm" style="width: 18rem">
                <div class="border border-success"></div>
                <div class="card-body">
                    <h5 class="card-title fw-light text-center">Usuários cadastrados</h5>
                    <h1 class="card-subtitle text-center">{{ $counts['users'] }}</h1>
                </div>
            </div>

            <div class="card bg-dark text-light shadow-sm" style="width: 18rem">
                <div class="border border-info"></div>
                <div class="card-body">
                    <h5 class="card-title fw-light text-center">Posts publicados</h5>
                    <h1 class="card-subtitle text-center">{{ $counts['published_posts'] }}</h1>
                </div>
            </div>

            <div class="card bg-dark text-light shadow-sm" style="width: 18rem">
                <div class="border border-warning"></div>
                <div class="card-body">
                    <h5 class="card-title fw-light text-center">Posts não publicados</h5>
                    <h1 class="card-subtitle text-center">{{ $counts['unpublished_posts'] }}</h1>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
@endsection
