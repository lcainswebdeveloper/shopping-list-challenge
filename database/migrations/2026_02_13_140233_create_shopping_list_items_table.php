<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shopping_list_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedInteger('quantity')->default(0);
            $table->unsignedInteger('cost_in_units')->default(0);
            $table->string('grocery_slug');
            $table->uuid('shopping_list_id');
            $table->foreign('grocery_slug')->references('slug')->on('groceries')->restrictOnDelete();
            $table->foreign('shopping_list_id')->references('id')->on('shopping_lists')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['shopping_list_id', 'grocery_slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shopping_list_items');
    }
};
