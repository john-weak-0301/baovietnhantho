<?php

namespace App\Orchid\Controllers;

use App\Model\Block;
use Illuminate\Http\Request;

class BlockController
{
    public function index()
    {
        return Block::take(100)->get();
    }

    public function store(Request $request)
    {
        $block = new Block;

        $block->raw_title = $request->title;
        $block->status    = $request->status;
        $block->setContent($request->get('content'));

        $block->save();

        return $block;
    }

    public function show(Request $request, $id)
    {
        return response()->json(
            Block::findOrFail($id)
        );
    }

    public function update(Request $request, $id)
    {
        $block = Block::findOrFail($id);

        $block->raw_title = $request->title;
        $block->status    = $request->status;
        $block->setContent($request->get('content'));

        $block->save();

        return $block;
    }

    public function destroy($id)
    {
        $block = Block::findOrFail($id);

        $block->delete();

        return response()->json(['message' => 'deleted']);
    }
}
