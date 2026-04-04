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
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->comment('カテゴリID');
            $table->text('question')->comment('質問');
            $table->text('answer')->comment('回答');
            $table->text('note')->nullable()->comment('補足');
            $table->string('pdf')->nullable()->comment('PDF');
            $table->string('url')->nullable()->comment('URL');
            $table->integer('sort_order')->default(0)->comment('表示順');
            $table->boolean('is_visible')->default(true)->comment('表示フラグ');
            $table->timestamps();
            $table->softDeletes();

            $table->index('category_id');
            $table->index('sort_order');
            $table->index('is_visible');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};