@extends('layouts.app')
@section('content')
    @switch($dm['type_user'])
        @case(1)
                <winner-component></winner-component>
        @break
        @case(2)
                <winner-component></winner-component>
        @break
        @default
               <div class="col-sm-12">
                        <h1>Error 404</h1>
               </div>
    @endswitch
        
@endsection
