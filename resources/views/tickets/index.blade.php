@extends('layouts.app')
@section('content')
    @switch($dm['type_user'])
        @case(1)
                <admin-tickets-component></admin-tickets-component>
        @break
        @case(2)
                <admin-tickets-component></admin-tickets-component>
        @break
        @case(3)
                <management-tickets-component></management-tickets-component>
        @break
        @case(4)
                <user-tickets-component></management-tickets-component>
        @break
        @default
                <user-tickets-component></management-tickets-component>
    @endswitch
        
@endsection
