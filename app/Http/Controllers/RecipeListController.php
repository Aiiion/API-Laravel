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

        $res = Auth::user()->recipeLists()->get();
        foreach($res as $i=>$list) {
            $res[$i]->recipes = json_decode($list->recipes);
        }
        Log::info($res);
        return response()->json($res);
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
        $res = $user->recipeLists()->get();
        foreach($res as $i=>$list) {
            $res[$i]->recipes = json_decode($list->recipes);
        }
        Log::info($res);
        return response()->json($res);
    }

    public function delete(Request $request) {
        Log::info($request);
        $list = RecipeList::find($request->id);
        Log::info($list);
        if(Auth::user()->id != $list->user_id) return response('Forbidden', 403);

        $list->delete();

        $res = Auth::user()->recipeLists()->get();
        foreach($res as $i=>$list) {
            $res[$i]->recipes = json_decode($list->recipes);
        }
        Log::info($res);
        return response()->json($res);
    }

    public function update(Request $request) {
        Log::info($request);
        $list = RecipeList::find($request[1]);
        if(Auth::user()->id != $list->user()->first()->id) return response('Forbidden', 403);

        if(!$list->recipes) $list->recipes = json_encode(array());
        
        $recipes = json_decode($list->recipes);
        $recipeNotSaved = true;
        foreach($recipes as $recipe) {
            if($recipe->id == $request[0]["id"]){
                $recipeNotSaved = false;
            }
        }
        if($recipeNotSaved) {
            array_push($recipes, $request[0]);
            $list->recipes = json_encode($recipes);
            $list->save();
        }

        $res = Auth::user()->recipeLists()->get();
        foreach($res as $i=>$list) {
            $res[$i]->recipes = json_decode($list->recipes);
        }
        Log::info($res);
        return response()->json($res);
    }
    public function remove(Request $request) {
        Log::info($request);
        $list = RecipeList::find($request->list_id);
        if(Auth::user()->id != $list->user()->first()->id) return response('Forbidden', 403);

        if(!$list->recipes) $list->recipes = json_encode(array());
        
        $recipes = json_decode($list->recipes);
        
        foreach($recipes as $i=>$recipe) {
            if($recipe->id == $request->recipe["id"]){
                unset($recipes[$i]);
            }
        }
        $list->recipes = json_encode($recipes);
        $list->save();

        $res = Auth::user()->recipeLists()->get();
        foreach($res as $i=>$list) {
            $res[$i]->recipes = json_decode($list->recipes);
        }
        Log::info($res);
        return response()->json($res);
    }
}
