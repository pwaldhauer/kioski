<div class="flex gap-4">
    <div class="bg-white rounded p-4 w-6/12 space-y-2">

        <h4 class="text-xl flex gap-2 items-center">
            Home Assistant
            @if($this->hassConfigured)
                <x-heroicon-m-check-badge class="h-5 w-5 text-green-500" title="All set!"/>
            @endif
        </h4>
        <div class="text-sm">We connect your speakers via Home Assistant. You can create an API token in your profile.
        </div>
        <x-form.row>
            <x-form.label for="hass_host">Host</x-form.label>
            <x-form.text name="hass_host" type="text" wire:model="credentials.hass_host"
                         placeholder="https://hass.local:8123"/>
            @error('credentials.hass_host')
                <div class="text-red-400 mt-2">{{$message}}</div>
            @enderror
        </x-form.row>
        <x-form.row>
            <x-form.label for="hass_token">Token</x-form.label>
            <x-form.text name="hass_token" type="password" wire:model="credentials.hass_token" placeholder=""/>
        </x-form.row>
        <x-form.row>
            <x-form.button
                wire:loading.class="group is-loading"
                wire:click.prevent="submitHass"
            >Save
            </x-form.button>
        </x-form.row>
    </div>

    <div class="bg-white rounded p-4  w-6/12 space-y-2">

        <h4 class="text-xl flex gap-2 items-center">
            Spotify
            @if($this->spotifyConfigured)
                <x-heroicon-m-check-badge class="h-5 w-5 text-green-500" title="All set!"/>
            @endif
        </h4>
        <div class="text-sm">We need credentials for a Spotify app to fetch information about spotify links. You can
            create
            one at <a href="https://developer.spotify.com/" class="underline">developer.spotify.com</a>.
        </div>
        <x-form.row>
            <x-form.label for="spotify_client_id">Client ID</x-form.label>
            <x-form.text name="spotify_client_id" type="text" wire:model="credentials.spotify_client_id"
                         placeholder=""/>
            @error('credentials.spotify_client_id')
            <div class="text-red-400 mt-2">{{$message}}</div>
            @enderror
        </x-form.row>

        <x-form.row>
            <x-form.label for="spotify_client_secret">Client Secret</x-form.label>
            <x-form.text name="spotify_client_secret" type="password" wire:model="credentials.spotify_client_secret"
                         placeholder=""/>
        </x-form.row>

        <x-form.row>
            <x-form.button
                wire:loading.class="group is-loading"
                wire:click.prevent="submitSpotify"
            >Save
            </x-form.button>
        </x-form.row>
    </div>
</div>


