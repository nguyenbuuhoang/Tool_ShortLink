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
        Schema::table('short_urls', function (Blueprint $table) {
            $table->string('short_url_link')->nullable()->after('url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('short_urls', function (Blueprint $table) {
            $table->dropColumn('short_url_link');
        });
    }
};
