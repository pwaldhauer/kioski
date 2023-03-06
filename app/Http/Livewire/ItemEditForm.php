<?php

namespace App\Http\Livewire;

use App\Components\Spotify\Api;
use App\Models\Item;
use Illuminate\Filesystem\Filesystem;
use Livewire\Component;
use Livewire\WithFileUploads;

class ItemEditForm extends Component
{
    use WithFileUploads;

    public string $type = 'plex';
    public Item $item;

    public $plexLibrary = '';
    public $plexArtist = '';
    public $plexAlbum = '';

    public string $coverUrl = '';

    public ?array $children = null;

    public $coverUpload;

    protected $rules = [
        'item.name' => 'required',
        'item.uri' => 'nullable|string',
        'item.include_regex' => 'nullable|string',
        'item.exclude_regex' => 'nullable|string',
        'item.skip_first_track_condition' => 'nullable|string',
    ];


    public function setType($type)
    {
        $this->type = $type;
    }

    public function getIsArtistProperty()
    {
        return str_contains($this->item->uri, '/artist');
    }

    public function save()
    {
        $this->validate();

        if ($this->type === 'plex') {
            $params = [];
            if (filled($this->plexLibrary)) {
                $params['library_name'] = $this->plexLibrary;
            }

            if (filled($this->plexArtist)) {
                $params['artist_name'] = $this->plexArtist;
            }

            if (filled($this->plexAlbum)) {
                $params['album_name'] = $this->plexAlbum;
            }

            $this->item->uri = 'plex://' . json_encode($params);
        }

        if (!blank($this->coverUrl)) {
            /** @var \League\Flysystem\Filesystem $disk */
            $disk = \Storage::disk('local');

            $randomName = sha1(uniqid()) . '.jpg';

            if(!$disk->directoryExists('covers')) {
                $disk->createDirectory('covers');
            }

            $disk->put('covers/' . $randomName, file_get_contents($this->coverUrl));

            $this->item->cover_path = $randomName;
        }


        if (!empty($this->coverUpload)) {
            $this->coverUpload->storeAs('covers', $this->coverUpload->hashName());
            $this->item->cover_path = $this->coverUpload->hashName();
        }

        $this->item->save();


        if ($this->item->wasRecentlyCreated) {
            $this->redirect(route('edit', $this->item));
        }
    }

    public function getShowFetchButtonProperty(): bool
    {
        return $this->spotifyApi()->isConfigured();
    }

    public function fetch()
    {
        if(!$this->spotifyApi()->isConfigured()) {
            return;
        }

        // https://open.spotify.com/artist/4jqmOtAaAmhgv3xsl9k5Mx?si=8n-XKMG8TAOnZKaOknAqaQ
        $uri = $this->item->uri;
        if (preg_match('#spotify\.com/artist/([a-zA-Z0-9]+)#', $uri, $matches)) {
            // fetch artist info
            $info = $this->spotifyApi()->fetchArtistInfo($matches[1]);

            $this->item->name = $info->name;
            $this->coverUrl = $info->images[0]->url;
        }

        if (preg_match('#spotify\.com/album/([a-zA-Z0-9]+)#', $uri, $matches)) {
            $info = $this->spotifyApi()->fetchAlbumInfo($matches[1]);

            $this->item->name = $info->artists[0]->name . ' - ' . $info->name;
            $this->coverUrl = $info->images[0]->url;
        }

        if (preg_match('#spotify\.com/playlist/([a-zA-Z0-9]+)#', $uri, $matches)) {
            $info = $this->spotifyApi()->fetchPlaylistInfo($matches[1]);

            $this->item->name = $info->name;
            $this->coverUrl = $info->images[0]->url;
        }
    }

    public function fetchChildren()
    {
        $existingChildren = $this->item->children()->pluck('uri')->toArray();

        $uri = $this->item->uri;
        if (preg_match('#spotify\.com/artist/([a-zA-Z0-9]+)#', $uri, $matches)) {
            $this->children = $this->spotifyApi()->fetchAllAlbumsForArtist($matches[1]);

            // filter out existing children
            $this->children = array_filter($this->children, function ($item) use ($existingChildren) {
                return !in_array('https://open.spotify.com/album/' . $item['id'], $existingChildren);
            });

            if (filled($this->item->include_regex)) {
                $this->children = array_filter($this->children, function ($item) {
                    return preg_match($this->item->include_regex, $item['name']);
                });
            }

            if (filled($this->item->exclude_regex)) {
                $this->children = array_filter($this->children, function ($item) {
                    return !preg_match($this->item->exclude_regex, $item['name']);
                });
            }

        }
    }

    public function saveChildren()
    {
        foreach ($this->children as $child) {

            // Trick 17
            $sortOrder = 0;
            if (preg_match('/Folge (\d+)/i', $child['name'], $matches)) {
                $sortOrder = $matches[1];
            }

            Item::updateOrCreate([
                'uri' => 'https://open.spotify.com/album/' . $child['id'],
                'parent_id' => $this->item->id,
            ], [
                'name' => $child['name'],
                'cover_path' => $child['images'][0]['url'],
                'sort_order' => $sortOrder,
            ]);
        }

        $this->children = null;

        return $this->redirect(route('edit', $this->item));
    }

    public function render()
    {
        return view('livewire.item-edit-form');
    }

    public function mount()
    {
        if (str_contains($this->item->uri, 'spotify.com')) {
            $this->type = 'spotify';

            if (!$this->item->exists) {
                $this->fetch();
            }
        }

        if (str_starts_with($this->item->uri, 'plex://')) {
            $this->type = 'plex';
            $params = json_decode(str_replace('plex://', '', $this->item->uri), true);
            $this->plexLibrary = $params['library_name'] ?? '';
            $this->plexArtist = $params['artist_name'] ?? '';
            $this->plexAlbum = $params['album_name'] ?? '';
        }


    }

    private function spotifyApi(): Api
    {
        return new Api();
    }
}
