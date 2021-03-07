@extends('layout.layout')

@section('page_content')

<div class="container-fluid mt-3" id="app-createAccount" v-cloak>
  @include('layout.includes.loading')
  @include('layout.includes.account-infobar')
  <hr/>

  <div class="row">
    <div class="col-md-3">
        @include('layout.includes.account-sidemenu')
    </div>
    <div class="col-md-9">
    </div>
  </div>
</div>

@endsection