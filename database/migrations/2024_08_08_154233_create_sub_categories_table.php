<?php

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
        Schema::create('sub_categories', function (Blueprint $table) {
            $table->id();
    $table->unsignedBigInteger('category_id');
    $table->string('name');
    $table->string('slug');
    $table->tinyInteger('status')->default(0);
    $table->string('meta_title');
    $table->text('meta_description');
    $table->text('meta_keywords');
    $table->bigInteger('created_by');
    $table->timestamps();
    $table->softDeletes();
    
    // Adding the foreign key constraint
    $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_categories');
    }
};
