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
        Schema::table('exhibition_elements', function (Blueprint $table): void {
            $table->string('page', 30)->default('home')->after('id');
            $table->text('extra_content')->nullable()->after('secondary_content');
            $table->string('image_path')->nullable()->after('extra_content');
            $table->string('image_alt', 180)->nullable()->after('image_path');
            $table->index(['page', 'section']);
            $table->index(['page', 'section', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exhibition_elements', function (Blueprint $table): void {
            $table->dropIndex('exhibition_elements_page_section_index');
            $table->dropIndex('exhibition_elements_page_section_is_active_index');
            $table->dropColumn([
                'page',
                'extra_content',
                'image_path',
                'image_alt',
            ]);
        });
    }
};
