@extends('layouts.app')
@section('content')
    @switch($dm['type_user'])
        @case(1)
                <schedule-component></schedule-component>
        @break
        @case(2)
                <schedule-component></schedule-component>
        @break
        @default
               <div class="col-sm-12">
                 <h1>Error 404</h1>
               </div>
    @endswitch
        
@endsection