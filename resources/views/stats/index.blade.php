@extends('master',  ['active' => 'stats'])
@section('content')
<div class="column">
    <div class="container">
        <div class="columns">
            <div class="column is-12">
                <!--
                <checkout-form></checkout-form>
                -->
                <stats></stats>
                <br><br>
                <stats-by-product></stats-by-product>
            </div>
        </div>
    </div>
</div>
@endsection