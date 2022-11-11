<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RecipeList;

class RecipeListController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function create(User $user, Request $request) {
        if(Auth::user()->id != $user->id) return response('Forbidden', 403);

        RecipeList::create([
            'user_id' => $user->id,
            'recipe_ids' =>null,
            'name' => $request->name ?? 'New List'
        ]);

        return response()->json($user->recipeLists()->get());
    }

    public function delete(RecipeList $recipeList, Request $request) {
        if(Auth::user()->id != $recipeList->user()->first()->id) return response('Forbidden', 403);

        $recipeList->delete();

        return response()->json($recipeList->userRecipes());
    }

    public function update(RecipeList $recipeList, Request $request) {
        if(Auth::user()->id != $recipeList->user()->first()->id) return response('Forbidden', 403);

        $recipeList->recipe_ids = $request->recipe_ids;
        $recipeList->save();

        return response()->json($recipeList->userRecipes());
    }
}
