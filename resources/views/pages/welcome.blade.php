@extends('layouts.app')

@section('content')
<div class="py-4">
    <div class="container">
        <div class="row top-nav justify-content-between">
            <div class="w-brand">
                <img class="w-logo" src="/img/brand_logo.png"/>
            </div>
            <div class="d-none d-sm-block">
                <a class="w-login" href="/login">
                    Login
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 w-text">
                <h1 class="w-h1">Your personal<br/>assistant</h1>
                <h6 class="w-h6">Kill routine and do important things!<h6>
                <div class="w-text-login-box">
                    <a class="w-text-login" href="/login">Login</a>
                </div>
            </div>
            <div class="col-sm-6" style="z-index: -1;">
                <img class="w-img" src="/img/w_img.png"/>
            </div>
        </div>
    </div>
</div>
@endsection
