<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Serializers\ItemSerializer;
use App\Serializers\ItemsSerializer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use League\CommonMark\CommonMarkConverter;

class ItemController extends Controller
{
    private $rules = [
        'name' => 'required|string|max:255',
        'price' => 'required|numeric',
        'url' => 'required|url',
        'description' => 'required|string',
    ];

    public function index()
    {
        $items = new ItemsSerializer(Item::all());

        return response()->serialize(compact('items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules);

        $converter = new CommonMarkConverter(['html_input' => 'escape', 'allow_unsafe_links' => false]);
        $validated['description'] = $converter->convert($validated['description'])->getContent();

        $item = new ItemSerializer(Item::create($validated));

        return response()->serialize(compact('item'));
    }

    public function show($id)
    {
        $item = new ItemSerializer(Item::findOrFail($id));

        return response()->serialize(compact('item'));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate($this->rules);

        $converter = new CommonMarkConverter(['html_input' => 'escape', 'allow_unsafe_links' => false]);

        $item = Item::findOrFail($id);
        $item->name = $validated['name'];
        $item->url = $validated['url'];
        $item->price = $validated['price'];
        $item->description = $converter->convert($validated['description'])->getContent();
        $item->save();
        
        return response()->serialize(
            [
                'item' => new ItemSerializer($item)
            ]
        );
    }
}
