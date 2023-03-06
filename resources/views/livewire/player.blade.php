<div class="text-sm text-white flex gap-4 items-center ">
    @if($this->state === 'playing')
        <x-heroicon-m-pause class="w-5 h-5 cursor-pointer" wire:click.prevent="pause"/>
    @else
        <x-heroicon-m-play class="w-5 h-5 cursor-pointer" wire:click.prevent="play"/>
    @endif

    <div class="text-center hidden md:block">
        @if(!blank($this->artist) && !blank($this->title))
            <div class="max-w-[150px] truncate">{{ $this->title }}</div>
            <div class="max-w-[150px] truncate text-xs text-indigo-100">{{ $this->artist }}</div>
        @else
            No media playing
        @endif
    </div>
</div>
