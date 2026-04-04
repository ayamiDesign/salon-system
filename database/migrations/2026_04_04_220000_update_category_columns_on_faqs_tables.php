<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // faqs に category1_id / category2_id を追加
        Schema::table('faqs', function (Blueprint $table) {
            $table->unsignedBigInteger('category1_id')->nullable()->after('id');
            $table->unsignedBigInteger('category2_id')->nullable()->after('category1_id');

            $table->index('category1_id');
            $table->index('category2_id');
        });

        // faq_histories に category1_id / category2_id を追加
        Schema::table('faq_histories', function (Blueprint $table) {
            $table->unsignedBigInteger('category1_id')->nullable()->after('faq_id');
            $table->unsignedBigInteger('category2_id')->nullable()->after('category1_id');

            $table->index('category1_id');
            $table->index('category2_id');
        });

        // 既存データを category_id -> category1_id に移行
        DB::table('faqs')->update([
            'category1_id' => DB::raw('category_id'),
        ]);

        DB::table('faq_histories')->update([
            'category1_id' => DB::raw('category_id'),
        ]);

        // 古い category_id を削除
        Schema::table('faqs', function (Blueprint $table) {
            $table->dropColumn('category_id');
        });

        Schema::table('faq_histories', function (Blueprint $table) {
            $table->dropColumn('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // category_id を戻す
        Schema::table('faqs', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable()->after('id');
            $table->index('category_id');
        });

        Schema::table('faq_histories', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable()->after('faq_id');
            $table->index('category_id');
        });

        // category1_id -> category_id に戻す
        DB::table('faqs')->update([
            'category_id' => DB::raw('category1_id'),
        ]);

        DB::table('faq_histories')->update([
            'category_id' => DB::raw('category1_id'),
        ]);

        // category1_id / category2_id を削除
        Schema::table('faqs', function (Blueprint $table) {
            $table->dropColumn(['category1_id', 'category2_id']);
        });

        Schema::table('faq_histories', function (Blueprint $table) {
            $table->dropColumn(['category1_id', 'category2_id']);
        });
    }
};