<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class RecipeList extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'recipes'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function userRecipes() {
        return RecipeList::where('user_id', $this->user_id)->orderBy('created_at', 'asc');
    }
}
