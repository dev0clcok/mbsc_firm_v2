<?php

use App\Support\Phone;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            // Digits only, for searching whatever way the number was typed.
            $table->string('phone_normalized', 30)->nullable()->after('phone')->index();
        });

        DB::table('enquiries')->whereNotNull('phone')->orderBy('id')->each(function (object $enquiry) {
            DB::table('enquiries')->where('id', $enquiry->id)->update([
                'phone_normalized' => Phone::normalize($enquiry->phone),
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->dropIndex(['phone_normalized']);
            $table->dropColumn('phone_normalized');
        });
    }
};
