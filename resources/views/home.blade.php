@extends('layouts.app')

@section('content')
<status></status>
<router-view></router-view>

<noscript>
    <div class="flex justify-center text-center items-center text-3xl m-8 p-4 bg-red-200 h-32 sm:shadow">
        @lang('app.nojs')
    </div>
</noscript>
@endsection
