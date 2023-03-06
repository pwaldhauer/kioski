<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite('resources/css/app.css')
    @livewireStyles

    <title>{{ config('app.name') }}</title>

</head>
<body class="bg-indigo-100">

<navigation class="block bg-indigo-400 py-2 w-full">

    <div class="px-4 md:px-0  sm:w-11/12 lg:w-8/12 m-auto flex justify-end items-center gap-10">
        <a href="{{ route('home') }}" class="block mr-auto">
            <x-heroicon-s-home class="w-5 h-5 text-white"/>
        </a>


        <livewire:player/>

        <livewire:media-player-selector/>

        <a href="{{route('manage')}}">
            <x-heroicon-s-cog-6-tooth class="w-5 h-5 text-white"/>
        </a>
    </div>


</navigation>

<div class="m-auto my-8 sm:w-11/12 lg:w-8/12 px-4 md:px-0  ">
    {{ $slot }}
</div>

<style>
    .bg-theme-100 {
        @apply bg-indigo-100;
    }
</style>

@vite('resources/js/app.js')
@livewireScripts

</body>
</html>
