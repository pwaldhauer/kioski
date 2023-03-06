@props(['item'])


@php
    $uri = $item->uri;
    if($item->children->count() > 0) {
        $uri = route('items.show', $item);
    }
@endphp

<div {{ $attributes }} x-data="{step: 0}">
    @if($item->children->count() > 0)
        <a href="{{ route('items.show', $item) }}" class="block space-y-4 ">
            <div class="w-full relative shadow-xl rounded-xl hover:scale-105 transition-transform overflow-clip">
                <x-cover :url="$item->coverImageUrl()" :alt="$item->name"/>
            </div>
            <div class="text-sm text-center">{{$item->name}}</div>
        </a>
    @else
        <button x-on:click="step = 1" x-on:click.away="step = 0" class="block w-full space-y-4 relative">
            <div class="w-full relative shadow-xl rounded-xl hover:scale-105 transition-transform overflow-clip">
                <x-cover :url="$item->coverImageUrl()" :alt="$item->name"/>
                <div x-cloak
                     class="z-50 rounded-xl absolute w-full h-full top-0 left-0 bg-black bg-opacity-50 transition duration-300 flex items-center justify-center"
                     x-show="step === 1" x-on:click="Livewire.emit('play', '{{ $item->uri }}' )">
                    <x-heroicon-m-play class="w-16 h-16 text-white"/>

                </div>
            </div>

            <div class="text-sm text-center ">{{$item->name}}</div>
        </button>
    @endif


</div>
