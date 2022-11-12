<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recipe_lists', function (Blueprint $table) {
            $table->dropColumn('recipe_ids');
            $table->json('recipes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recipe_lists', function (Blueprint $table) {
            $table->dropColumn('recipe_ids');
            $table->json('recipe_ids');
        });
    }
};
