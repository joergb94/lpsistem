@extends('layouts.app')
@section('content')
@switch($dm['type_user'])
        @case(1)
                <home-component></home-component>
        @break
        @case(2)
                <home-component></home-component>
        @break
        @case(3)
                <home-component></home-component>
        @break
        @case(4)
                <home-component-client></home-component-client>
        @break
        @default
                <home-component-client></home-component-client>
    @endswitch
 
@endsection
