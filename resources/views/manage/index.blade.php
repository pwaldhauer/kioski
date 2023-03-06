<x-layout>

    <h1 class="text-3xl font-bold mt-4">
        Manage library
    </h1>

    <form class="my-8" action="{{ route('manage.create') }}" method="post">
        @csrf
        <x-form.row>
            <x-form.label for="uri">Add new…</x-form.label>
            <div class="flex gap-4">

                <x-form.text name="uri" type="text" placeholder="URL to media (or nothing)"/>
                <x-form.button submit>
                    Go
                </x-form.button>
            </div>
        </x-form.row>


    </form>

    <livewire:sortable-list model="App\Models\Item"/>

    <h1 class="text-xl font-bold mt-8">
        Manage credentials
    </h1>

    <div class="mt-8">
        <livewire:credentials-panel/>
    </div>

</x-layout>

