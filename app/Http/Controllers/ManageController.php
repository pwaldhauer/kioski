<?php

namespace App\Http\Controllers;

use App\Enums\Options;
use App\Models\Item;
use App\Models\Option;
use Illuminate\Http\Request;

class ManageController extends Controller
{


    public function index($id = null)
    {

        return view('manage.index', [
        ]);
    }

    public function create(Request $request)
    {
        $item = new Item();
        $item->uri = $request->post('uri');

        return view('manage.edit', [
            'item' => $item,
        ]);
    }

    public function edit($id)
    {
        return view('manage.edit', [
            'item' => Item::findOrFail($id)
        ]);
    }

    public function delete($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return redirect(route('manage'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        if (!blank($data['id'])) {
            $item = Item::findOrFail($data['id']);
        } else {
            $item = new Item();
        }

        $item->fill($data);
        $item->save();

        return redirect()->route('edit', $item);

    }
}
