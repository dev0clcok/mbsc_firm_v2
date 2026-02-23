<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_member_social_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_member_id')->constrained()->cascadeOnDelete();
            $table->string('platform', 50);
            $table->string('url', 500);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['team_member_id', 'sort_order']);
        });

        if (Schema::hasColumn('team_members', 'linkedin_url')) {
            $members = DB::table('team_members')->get(['id', 'linkedin_url', 'twitter_url', 'email']);
            foreach ($members as $m) {
                $order = 0;
                if (! empty($m->linkedin_url)) {
                    DB::table('team_member_social_links')->insert([
                        'team_member_id' => $m->id,
                        'platform' => 'linkedin',
                        'url' => $m->linkedin_url,
                        'sort_order' => $order++,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
                if (! empty($m->twitter_url)) {
                    DB::table('team_member_social_links')->insert([
                        'team_member_id' => $m->id,
                        'platform' => 'twitter',
                        'url' => $m->twitter_url,
                        'sort_order' => $order++,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
                if (! empty($m->email)) {
                    DB::table('team_member_social_links')->insert([
                        'team_member_id' => $m->id,
                        'platform' => 'email',
                        'url' => $m->email,
                        'sort_order' => $order++,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
            Schema::table('team_members', function (Blueprint $table) {
                $table->dropColumn(['linkedin_url', 'twitter_url', 'email']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('team_member_social_links');
    }
};
