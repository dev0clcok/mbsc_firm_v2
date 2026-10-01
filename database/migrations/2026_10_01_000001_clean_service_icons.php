<?php

use App\Support\SvgIcon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Icons saved before the sanitiser existed may hold scripts or event
     * handlers; clean every stored icon once.
     */
    public function up(): void
    {
        DB::table('services')->whereNotNull('icon_svg')->orderBy('id')->each(function (object $service) {
            $clean = SvgIcon::clean($service->icon_svg);

            if ($clean !== $service->icon_svg) {
                DB::table('services')->where('id', $service->id)->update(['icon_svg' => $clean]);
            }
        });
    }

    public function down(): void
    {
        // The removed markup is not kept.
    }
};
