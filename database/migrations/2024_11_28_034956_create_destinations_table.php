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
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->string('country')->nullable();
            $table->string('language')->nullable();
            $table->string('currency')->nullable();
            $table->string('area')->nullable();
            $table->string('timezone')->nullable();
            $table->text('best_time')->nullable();
            $table->text('health_safety')->nullable();
            $table->text('visa_requirement')->nullable();
            $table->text('activity')->nullable();
            $table->text('map')->nullable();
            $table->string('featured_photo')->nullable();
            $table->string('country_code', 2)->unique()->nullable();
            $table->integer('package_count')->default(0);
            $table->boolean('is_visible_on_map')->default(true);
            $table->integer('view_count')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destinations');
    }
};
