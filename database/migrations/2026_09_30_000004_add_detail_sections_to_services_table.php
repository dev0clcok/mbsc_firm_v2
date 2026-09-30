<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->json('process_steps')->nullable()->after('features');
            $table->json('documents')->nullable()->after('process_steps');
            $table->text('timeline')->nullable()->after('documents');
            $table->text('fees')->nullable()->after('timeline');
            $table->string('image_alt')->nullable()->after('image_height');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['process_steps', 'documents', 'timeline', 'fees', 'image_alt']);
        });
    }
};
