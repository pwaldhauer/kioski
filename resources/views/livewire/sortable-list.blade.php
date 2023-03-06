<div class="space-y-2" data-sortable x-data="sortableList({})">

    @foreach($this->items as $item)

        <div data-id="{{$item->id}}" class="px-4 py-2 border flex gap-8 bg-white items-center rounded">
            <div class="handle">
                <x-heroicon-o-bars-3 class="w-6 h-6 text-gray-500"/>
            </div>


            @if($item->cover_path)
                <img src="{{ $item->coverImageUrl() }}" alt="{{ $item->name }}" class="w-16">
            @endif

            <div class="text-xl">
                {{ $item->name }}
            </div>


            <div class="ml-auto flex gap-2">
                <x-form.button
                    href="{{ route('edit', $item) }}"
                >
                    Edit
                </x-form.button>
            </div>

        </div>
    @endforeach

</div>
