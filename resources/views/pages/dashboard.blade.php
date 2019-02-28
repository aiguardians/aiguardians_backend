@extends('layouts.nav')

@section('content')
    <dashboard username="{{ auth()->user()->name }}" userimg="{{ auth()->user()->student?auth()->user()->student->image:auth()->user()->teacher->image }}" userrole="{{ auth()->user()->teacher?'Teacher':'Student' }}"></dashboard>
@endsection
