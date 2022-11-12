<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RecipeList;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class RecipeListController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index() {
        if(!Auth::user()) return response('Forbidden', 403);

        return response()->json(Auth::user()->recipeLists()->get());
    }

    public function create(Request $request) {
        Log::info($request);
        if(!Auth::user()) return response('Forbidden', 403);

        $user = Auth::user();

        RecipeList::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'recipe_ids' => null,
            'name' => $request->name ?? 'New List'
        ]);
        Log::info($user->recipeLists()->get());
        return response()->json($user->recipeLists()->get());
    }

    public function delete(Request $request) {
        $list = RecipeList::find($request->listId);
        if(Auth::user()->id != $list->user_id) return response('Forbidden', 403);

        $recipeList->delete();

        return response()->json($recipeList->userRecipes()->get());
    }

    public function update(RecipeList $recipeList, Request $request) {
        if(Auth::user()->id != $recipeList->user()->first()->id) return response('Forbidden', 403);

        $recipeList->recipe_ids = $request->recipe_ids;
        $recipeList->save();

        return response()->json($recipeList->userRecipes()->get());
    }
}
