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
        Schema::create('items', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('parent_id')->nullable();

            $table->string('name');
            $table->string('cover_path')->nullable();
            $table->integer('sort_order')->nullable();

            $table->string('include_regex')->nullable();
            $table->string('exclude_regex')->nullable();
            $table->string('skip_first_track_condition')->nullable();

            $table->text('uri');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
