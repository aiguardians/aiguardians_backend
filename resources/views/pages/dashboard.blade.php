@extends('layouts.nav')

@section('content')
    <dashboard userimg="{{ auth()->user()->student?auth()->user()->student->image:auth()->user()->teacher->image }}" userrole="{{ auth()->user()->teacher?'Teacher':'Student' }}"></dashboard>
@endsection
