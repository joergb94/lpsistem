@extends('layouts.app')
@section('content')
    @switch($dm['type_user'])
        @case(1)
                <user-component></user-component>
        @break
        @case(2)
                <user-component></user-component>
        @break
        @case(3)
                <user-component></user-component>
        @break
        @case(4)
                <profile-component></profile-component>
        @break
        @default
                <profile-component></profile-component>
    @endswitch
        
@endsection
