@extends('layouts.app')

@section('content')
<div class="flex items-center">
    <div class="md:w-1/2 md:mx-auto">
        <div class="rounded shadow">
            <div class="font-medium text-lg text-green-700 bg-green-200 p-3 rounded-t">
                @lang('app.login')
            </div>
            <div class="bg-white p-3 rounded-b">
                <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="flex items-stretch mb-3">
                        <label for="email" class="text-right font-semibold text-gray-600 text-sm pt-2 pr-3 align-middle w-1/4">@lang('app.email')</label>
                        <div class="flex flex-col w-3/4">
                            <input id="email" type="email" class="flex-grow h-8 px-2 border rounded {{ $errors->has('email') ? 'border-red-600' : 'border-gray-400' }}" name="email" value="{{ old('email') }}" required autofocus>
                            {!! $errors->first('email', '<span class="text-red-600 text-sm mt-2">:message</span>') !!}
                        </div>
                    </div>

                    <div class="flex items-stretch mb-4">
                        <label for="password" class="text-right font-semibold text-gray-600 text-sm pt-2 pr-3 align-middle w-1/4">@lang('app.password')</label>
                        <div class="flex flex-col w-3/4">
                            <input id="password" type="password" class="flex-grow h-8 px-2 rounded border {{ $errors->has('password') ? 'border-red-600' : 'border-gray-400' }}" name="password" required>
                            {!! $errors->first('password', '<span class="text-red-600 text-sm mt-2">:message</span>') !!}
                        </div>
                    </div>

                    <div class="flex mb-4">
                        <label class="w-3/4 ml-auto">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> <span class="text-sm text-gray-600"> @lang('app.rememberme')</span>
                        </label>
                    </div>

                    <div class="flex">
                        <div class="w-3/4 ml-auto">
                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white text-sm font-semibold py-2 px-4 rounded mr-3">
                                @lang('app.login')
                            </button>
                            <a class="hover:underline text-green-600 text-sm" href="{{ route('password.request') }}">
                                @lang('app.forgotpassword')
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
