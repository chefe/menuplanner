@extends('layouts.app')

@section('content')
<status></status>
<router-view></router-view>

<noscript>
    <div class="flex justify-center text-center items-center text-3xl m-8 p-4 bg-red-lighter h-32 sm:shadow">
        Please activate javascript, otherwise the application will not work!
    </div>
</noscript>
@endsection
