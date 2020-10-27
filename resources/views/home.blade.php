@extends('layouts.app')
@section('content')
@switch($dm['type_user'])
        @case(5)
                <home-component-client></home-component-client>
        @break
        @default
                <home-component></home-component>     
    @endswitch
 
@endsection
