@extends('layouts.app')

@section('content')
<div class="flex items-center">
    <div class="md:w-1/2 md:mx-auto">
        <div class="rounded shadow">
            <div class="font-medium text-lg text-green-700 bg-green-200 p-3 rounded-t">
                @lang('app.resetpassword')
            </div>
            <div class="bg-white p-3 rounded-b">
                @if (session('status'))
                    <div class="bg-green-100 border border-green-400 text-green-600 text-sm px-4 py-3 rounded mb-4">
                        {{ session('status') }}
                    </div>
                @endif

                <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="flex items-stretch mb-3">
                        <label for="email" class="text-right font-semibold text-gray-600 text-sm pt-2 pr-3 align-middle w-1/4">@lang('app.email')</label>
                        <div class="flex flex-col w-3/4">
                            <input id="email" type="email" class="flex-grow h-8 px-2 border rounded {{ $errors->has('email') ? 'border-red-600' : 'border-gray-400' }}" name="email" value="{{ old('email') }}" required autofocus>
                            {!! $errors->first('email', '<span class="text-red-600 text-sm mt-2">:message</span>') !!}
                        </div>
                    </div>

                    <div class="flex">
                        <div class="w-3/4 ml-auto">
                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white text-sm font-sembiold py-2 px-4 rounded mr-3">
                                @lang('app.sendresetlink')
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

