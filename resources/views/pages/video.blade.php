@extends('layouts.app')

@section('content')
    <testvideostream groupid="{{ auth()->user()->student->groups[0]->id }}"></testvideostream>
@endsection

@section('extra_styles')
<style>

</style>
@endsection
