@extends('layouts.app')
@section('content')
    @switch($dm['type_user'])
        @case(1)
                <user-component></user-component>
        @break
        @default
                <profile-component></profile-component>
    @endswitch
        
@endsection
