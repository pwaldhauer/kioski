<?php

namespace App\Http\Livewire;

use App\Models\Item;
use Livewire\Component;

class SortableList extends Component
{

    public string $model;

    public ?string $parentId = null;

    public function getItemsProperty()
    {
        return Item::query()
            ->where('parent_id', $this->parentId)
            ->orderBy('sort_order')
            ->get();
    }

    public function render()
    {
        return view('livewire.sortable-list');
    }

    public function saveSort(array $ids)
    {
        foreach ($ids as $idx => $id) {
            $item = Item::findOrFail($id);
            $item->sort_order = $idx;
            $item->save();
        }
    }
}
