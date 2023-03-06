<x-layout>

    <div class="mb-4">
        <a href="{{ route('manage') }}" class="flex gap-2 items-center">
            <x-heroicon-m-arrow-left class="w-5 h-5"/>
            Back
        </a>
    </div>

    <div class="flex">
        @if($item->exists)
            <h1 class="text-3xl font-bold">
                Edit {{ $item->name }}
            </h1>

            <form
                action="{{ route('manage.delete', $item) }}"
                method="POST"
                class="ml-auto"
            >
                @csrf
                @method('DELETE')

                <x-form.button variant="danger">
                    Delete
                </x-form.button>
            </form>
        @else
            <h1 class="text-3xl font-bold">
                Create new item
            </h1>
        @endif
    </div>


    <livewire:item-edit-form :item="$item"/>

</x-layout>

