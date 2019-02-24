@extends('layouts.nav')

@section('content')
    <div class="container-fluid py-4">
        <h3>Моё Расписание</h3>
        <schedule2 groupid="{{ $user->student->group->id }}"></schedule2>
    </div>
@endsection
