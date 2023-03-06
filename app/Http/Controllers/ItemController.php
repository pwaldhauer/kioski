<?php

namespace App\Http\Controllers;

use App\Models\Item;

class ItemController extends Controller
{


    public function index($id = null)
    {
        $items = Item::query();
        if (!blank($id)) {
            $items->where('parent_id', $id);
        } else {
            $items->whereNull('parent_id');
        }

        $items
            ->orderBy('sort_order');

        $items = $items->get();

        if (filled($id)) {
            $parent = Item::find($id);

            if (filled($parent->include_regex)) {
                $items = $items->filter(function ($item) use ($parent) {
                    return preg_match($parent->include_regex, $item->name);
                });
            }

            if (filled($parent->exclude_regex)) {
                $items = $items->filter(function ($item) use ($parent) {
                    return !preg_match($parent->exclude_regex, $item->name);
                });
            }
        }

        return view('items.index', [
            'items' => $items
        ]);
    }
}
