<form>
    <div class="space-y-4 bg-white rounded shadow my-8">

        <div class="p-6  space-y-4">
            <ul class="flex gap-4">
                <li>
                    <x-form.button wire:click.prevent="setType('spotify')" :active="$this->type === 'spotify'">Spotify
                    </x-form.button>
                </li>
                <li>
                    <x-form.button wire:click.prevent="setType('plex')" :active="$this->type === 'plex'">Plex
                    </x-form.button>
                </li>
            </ul>

            @if($this->type === 'spotify')
                <x-form.row class="flex">
                    <x-form.label for="uri">URI</x-form.label>
                    <div class="flex gap-2">
                        <x-form.text required wire:model="item.uri" type="text" name="uri"/>
                        @if($this->showFetchButton)
                            <x-form.button wire:click.prevent="fetch">
                                Fetch
                            </x-form.button>
                        @endif
                    </div>

                </x-form.row>
            @else
                <x-form.row class="flex">
                    <x-form.label for="uri">Plex</x-form.label>
                    <div class="flex gap-2">
                        <x-form.text required wire:model="plexLibrary" type="text" name="plexLibrary"
                                     placeholder="Library"/>
                        <x-form.text wire:model="plexArtist" type="text" name="plexArtist" placeholder="Artist"/>
                        <x-form.text wire:model="plexAlbum" type="text" name="plexAlbum" placeholder="Album"/>
                    </div>
                </x-form.row>
                <x-form.row class="flex">
                    <x-form.label for="uri">URI (generated)</x-form.label>
                    <div class="flex gap-2">
                        <x-form.text required wire:model="item.uri" type="text" name="uri" readonly/>
                    </div>
                </x-form.row>
            @endif

            <x-form.row>
                <x-form.label for="name">Name</x-form.label>
                <x-form.text required type="text" name="name" wire:model="item.name"/>
                <x-form.error for="item.name" />
            </x-form.row>
        </div>

        @if($this->item->exists && $this->isArtist)
            <div class="p-6 space-y-4 bg-indigo-50">
                <h3 class="text-xl">Automatically import albums</h3>
                <p class="text-sm text-gray-600">We can automatically can import this artist's album.
                    Please use the following options to limit the import according to your needs.</p>

                <x-form.row>
                    <x-form.label for="name">Include Regex</x-form.label>
                    <x-form.text
                        required
                        type="text"
                        name="include_regex"
                        wire:model="item.include_regex"
                        placeholder="/.*/"
                    />
                    <x-form.hint>Album titles matching this regex will be shown.</x-form.hint>
                </x-form.row>

                <x-form.row>
                    <x-form.label for="name">Exclude Regex</x-form.label>
                    <x-form.text
                        required
                        type="text"
                        name="exclude_regex"
                        wire:model="item.exclude_regex"
                        placeholder="/.*/"
                    />
                    <x-form.hint>Album titles matching this regex will be hidden.</x-form.hint>
                </x-form.row>

                <x-form.row>
                    <x-form.label for="name">Skip first track if Folge > x?</x-form.label>
                    <x-form.text required type="text" name="skip_first_track_condition"
                                 wire:model="item.skip_first_track_condition"/>
                    <x-form.hint>If the episode number is bigger, we skip the first track.</x-form.hint>
                </x-form.row>

                <x-form.row>
                    @if(blank($this->children))
                        <x-form.button
                            variant="secondary"
                            wire:click.prevent="fetchChildren"
                            wire:loading.class="group is-loading"
                        >
                            Fetch Children
                        </x-form.button>
                    @else
                        <x-form.button variant="secondary" wire:click.prevent="saveChildren">
                            Save Children
                        </x-form.button>
                    @endif
                </x-form.row>

                @if($this->children !== null)
                    <h3 class="font-bold">Following Albums will be imported as children</h3>
                    @if(empty($this->children))
                        <p class="text-sm text-gray-600">No albums found.</p>
                    @else
                        <ul class="border">
                            @foreach($this->children as $child)
                                <li class="p-2 odd:bg-white"> {{ $child['name'] }}</li>
                            @endforeach
                        </ul>
                    @endif

                    @if(filled($this->children))
                        <x-form.row>
                            <x-form.button variant="secondary" wire:click.prevent="saveChildren">
                                Save Children
                            </x-form.button>
                        </x-form.row>
                    @endif
                @endif
            </div>
        @endif


        <div class="p-6 space-y-4">
            <x-form.row class="row">
                <x-form.label for="cover_path">Cover</x-form.label>

                <div class="flex gap-2">
                    @if($this->coverUrl)
                        <div class="ring rounded">
                            <img src="{{ $this->coverUrl }}" alt="Cover image" class="w-32 h-32">
                        </div>
                    @endif
                    <img src="{{ $this->item->coverImageUrl() }}" alt="Cover image" class="w-32 h-32">


                </div>

                <input type="file" wire:model="coverUpload">
            </x-form.row>

            <x-form.row>
                <x-form.button wire:click.prevent="save">Save</x-form.button>
            </x-form.row>
        </div>
    </div>

    @if($this->item->exists)
        <livewire:sortable-list model="App\Models\Item" :parent-id="$this->item->id"/>
    @endif
</form>
