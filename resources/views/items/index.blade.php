<x-layout>

    @if(blank($items))
        <p class="text-xl text-center">Nothing here yet! Head to <a class="underline hover:no-underline" href="{{route('manage')}}">manage</a> to add something.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach($items as $item)
                <x-item :item="$item"/>
            @endforeach
        </div>
    @endif

</x-layout>

