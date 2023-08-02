@extends('layouts.layout')
@section('title')
    {{__('Batch Reassignment')}}
@endsection
@section('sidebar')
    @include('layouts.sidebar', ['sidebar'=> Menu::get('sidebar_request')])
@endsection
@section('css')
    <link rel="stylesheet" href="{{mix('/css/package.css', 'vendor/processmaker/packages/package-batch-reassignment')}}">
@endsection
@section('content')
    <div id="app-package-batch-reassignment" style="margin: 0px 1rem;">
        <div class="row">
            <div class="col-sm-12">
                <h1>{{__('Batch Request Reassignment')}}</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <sample-listing id="sample-list" ref="listing" :filter="filter"></sample-listing>
            </div>
        </div>
    </div>
@section('js')
<script src="{{mix('/js/package.js', 'vendor/processmaker/packages/package-batch-reassignment')}}"></script>
@endsection
@endsection
