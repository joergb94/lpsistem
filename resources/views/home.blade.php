@extends('layouts.app')
@section('content')

    <template v-if="menu==0">
        <example-component></example-component>
    </template> 
    <template v-if="menu==1">
    <example-component></example-component>
    </template>
    <template v-if="menu==2">
    <example-component></example-component>
    </template>
@endsection
