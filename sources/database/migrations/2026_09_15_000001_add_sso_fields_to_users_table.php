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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'sso_id')) {
                $table->uuid('sso_id')->nullable()->unique()->after('id')->comment('ID SSO dari TSU Homebase');
            }
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->nullable()->unique()->after('sso_id')->comment('Berisi NIK atau Username dari Homebase');
            }
            if (!Schema::hasColumn('users', 'name')) {
                $table->string('name')->nullable()->after('username');
            }
            if (!Schema::hasColumn('users', 'avatar_url')) {
                $table->string('avatar_url', 2048)->nullable()->after('photo_profile');
            }
            if (!Schema::hasColumn('users', 'sso_access_token')) {
                $table->text('sso_access_token')->nullable()->after('avatar_url');
            }
            if (!Schema::hasColumn('users', 'sso_refresh_token')) {
                $table->text('sso_refresh_token')->nullable()->after('sso_access_token');
            }
            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('isactive');
            }
            // Make password nullable if not already
            $table->string('password')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columnsToDrop = [];
            foreach (['sso_id', 'username', 'name', 'avatar_url', 'sso_access_token', 'sso_refresh_token', 'last_login_at'] as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $columnsToDrop[] = $col;
                }
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
