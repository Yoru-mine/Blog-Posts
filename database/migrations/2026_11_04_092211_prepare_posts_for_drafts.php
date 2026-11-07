<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('posts', 'is_published')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->boolean('is_published')->default(true)->index();
            });
        }

        Schema::table('posts', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('posts', 'is_published')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->dropIndex(['is_published']);
                $table->dropColumn('is_published');
            });
        }
    }
};
