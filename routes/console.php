<?php

use App\Models\Item;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('covers:load', function () {
    $items = Item::where('cover_path', 'like', 'http%')->get();
    foreach ($items as $item) {
        $randomName = sha1(uniqid()) . '.jpg';
        $coversPath = storage_path('app/covers/');
        if (!is_dir($coversPath)) {
            mkdir($coversPath);
        }

        $path = $coversPath . $randomName;

        file_put_contents($path, file_get_contents($item->cover_path));

        $this->info('Cover for ' . $item->name . ' loaded to disk');

        $item->cover_path = $randomName;
        $item->save();
    }
})->purpose('Load hot-linked covers to disk');
