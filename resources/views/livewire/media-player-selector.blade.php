<div class="border text-sm rounded border-indigo-600 text-indigo-100 relative" x-data="{open: false}">

    <div class="flex gap-2 items-center p-2 cursor-pointer text-xs md:text-sm" x-on:click="open = true" x-on:click.away="open = false">
        <x-heroicon-m-speaker-wave class="w-5 h-5"/>
        <span class="truncate">{{ $this->selectedPlayer ?? 'No player selected' }}</span>
        <x-heroicon-m-chevron-up-down class="w-5 h-5"/>
    </div>

    @if($error)
        <div class="z-20 absolute bg-red-400 p-2 mt-1 text-white rounded text-xs">
            <button
                class="absolute top-[-5px] right-[-5px]"
                wire:click="closeError"
            >
                <x-heroicon-m-x-circle class="w-4 h-4"/>
            </button>

            Error playing media. Sorry.
        </div>
    @endif

    <ul x-cloak x-show="open"
        class="z-10 absolute mt-1 w-full bg-white text-black drop-shadow-lg rounded bg-amber-500 border-amber-600">
        @foreach($this->mediaPlayers as $mediaPlayer)
            @if($mediaPlayer->name === $this->selectedPlayer)
                @continue
            @endif
            <li>
                <button
                    class="block w-full text-left text-sm hover:bg-indigo-700 hover:text-white p-2"
                    wire:click.prevent="selectPlayer('{{ $mediaPlayer->entityId }}')"
                >
                    {{ $mediaPlayer->name }}
                </button>
            </li>
        @endforeach

        <li>
            <button class="block w-full text-left text-xs p-2 flex items-center gap-2" wire:click.prevent="reloadCache">
                <x-heroicon-m-arrow-path class="w-3 h-3"/>
                Reload available players
            </button>
        </li>
    </ul>

</div>
