@extends('master',  ['active' => 'stats'])
@section('head-section')
    {!! Charts::styles() !!}
@endsection
@section('content')
    <div class="column">
        <div class="container">
            <div class="columns">
                <div class="column is-10">
                    {!! $chart->html() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    {!! Charts::scripts() !!}
    {!! $chart->script() !!}
@endsection