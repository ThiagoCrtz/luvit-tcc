<?php

namespace App\Http\Controllers;

use App\Models\ListModel;
use App\Models\ListItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User; 
use Illuminate\Support\Facades\Validator;

class ListsController extends Controller
{
    public function userLists(string $arroba)
    {
        $user = User::where('arroba', $arroba)->firstOrFail();
        $lists = $user->lists()->with('items')->withCount('items')->latest()->get();

        return view('lists.index', compact('lists', 'user'));
    }

    public function index()
    {
        $lists = ListModel::where('user_id', Auth::id())
            ->with('items')->withCount('items')
            ->latest()
            ->get();
            
        return view('lists.index', compact('lists'));
    }

    public function create()
    {
        return view('lists.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'media' => 'nullable|array',
            'media.*' => 'json',
        ]);

        $items = collect($request->input('media', []))->map(function ($json) {
            return Validator::make(json_decode($json, true) ?? [], [
                'media_type' => 'required|in:movie,game',
                'media_id' => 'required|integer|min:1',
                'title' => 'required|string|max:255',
                'poster_path' => 'nullable|url:http,https|max:2048',
            ])->validate();
        })->unique(fn ($item) => $item['media_type'] . ':' . $item['media_id'])->values();

        DB::beginTransaction();
        try {
            $list = ListModel::create([
                'user_id' => Auth::id(),
                'name' => $request->name,
                'description' => $request->description,
            ]);

            if ($items->isNotEmpty()) {
                foreach ($items as $index => $itemData) {
                    if ($itemData && isset($itemData['media_type'], $itemData['media_id'], $itemData['title'])) {
                        ListItem::create([
                            'list_id' => $list->id,
                            'media_type' => $itemData['media_type'],
                            'media_id' => $itemData['media_id'],
                            'title' => $itemData['title'],
                            'poster_path' => $itemData['poster_path'] ?? null,
                            'position' => $index
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('lists.index')->with('success', 'Lista criada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return back()->withInput()->withErrors(['error' => 'Não foi possível criar a lista. Tente novamente.']);
        }
    }

    public function show($id)
    {
        $list = ListModel::with(['items', 'user'])->findOrFail($id);
        $isOwner = Auth::check() && Auth::id() === $list->user_id;
        return view('lists.show', compact('list', 'isOwner'));
    }

    public function viewPublicList($listId)
    {
        $list = ListModel::with(['items', 'user'])->findOrFail($listId); 
    
        $isOwner = Auth::check() && Auth::id() === $list->user_id;

        return view('lists.show', compact('list', 'isOwner'));
    }

    public function addItem(Request $request, $id)
    {
        $list = ListModel::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $request->validate([
            'media_type' => 'required|in:movie,game',
            'media_id' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'poster_path' => 'nullable|url:http,https|max:2048'
        ]);

        $exists = ListItem::where('list_id', $list->id)
            ->where('media_type', $request->media_type)
            ->where('media_id', $request->media_id)
            ->exists();

        if ($exists) {
            return response()->json(['error' => 'Item já está na lista!'], 400);
        }

        $maxPosition = ListItem::where('list_id', $list->id)->max('position') ?? -1;

        $item = ListItem::create([
            'list_id' => $list->id,
            'media_type' => $request->media_type,
            'media_id' => $request->media_id,
            'title' => $request->title,
            'poster_path' => $request->poster_path,
            'position' => $maxPosition + 1
        ]);

        return response()->json(['success' => true, 'item' => $item]);
    }

    public function removeItem($listId, $itemId)
    {
        $list = ListModel::where('user_id', Auth::id())
            ->where('id', $listId)
            ->firstOrFail();

        $item = ListItem::where('list_id', $list->id)
            ->where('id', $itemId)
            ->firstOrFail();

        $item->delete();

        return back()->with('success', 'Item removido da lista!');
    }

    public function destroy($id)
    {
        $list = ListModel::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $list->delete();

        return redirect()->route('lists.index')->with('success', 'Lista excluída com sucesso!');
    }
}
