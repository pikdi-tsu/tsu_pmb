<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Lengkapi tabel users dengan kolom yang dipakai Admin PMB.
     *
     * Di lokal tabel users berasal dari database SIAKAD bersama (kolom sudah lengkap),
     * sedangkan di server tabel users dibuat dari migrasi bawaan Laravel.
     * Harus berjalan sebelum add_sso_fields_to_users_table (butuh photo_profile & isactive).
     */
    public function up(): void
    {
        $columns = [
            'nik'                      => fn (Blueprint $t) => $t->string('nik', 50)->nullable()->after('name'),
            'role_access'              => fn (Blueprint $t) => $t->string('role_access', 10)->nullable()->after('email'),
            'privilege_pmb'            => fn (Blueprint $t) => $t->string('privilege_pmb', 10)->nullable()->after('role_access'),
            'photo_profile_pmb'        => fn (Blueprint $t) => $t->string('photo_profile_pmb')->nullable()->after('password'),
            'photo_profile'            => fn (Blueprint $t) => $t->string('photo_profile')->nullable()->after('photo_profile_pmb'),
            'q1'                       => fn (Blueprint $t) => $t->string('q1', 100)->nullable(),
            'a1'                       => fn (Blueprint $t) => $t->string('a1', 100)->nullable(),
            'q2'                       => fn (Blueprint $t) => $t->string('q2', 100)->nullable(),
            'a2'                       => fn (Blueprint $t) => $t->string('a2', 100)->nullable(),
            'forgotpassword_sendemail' => fn (Blueprint $t) => $t->enum('forgotpassword_sendemail', ['0', '1'])->nullable()->default('0'),
            'created_by'               => fn (Blueprint $t) => $t->string('created_by', 50)->nullable(),
            'updated_by'               => fn (Blueprint $t) => $t->string('updated_by', 50)->nullable(),
            'isactive'                 => fn (Blueprint $t) => $t->tinyInteger('isactive')->nullable()->default(1),
        ];

        foreach ($columns as $name => $define) {
            if (!Schema::hasColumn('users', $name)) {
                Schema::table('users', static fn (Blueprint $table) => $define($table));
            }
        }

        // users bawaan Laravel: name NOT NULL, sedangkan di PMB/SIAKAD boleh kosong
        $nameColumn = collect(Schema::getColumns('users'))->firstWhere('name', 'name');
        if ($nameColumn && !$nameColumn['nullable']) {
            Schema::table('users', static fn (Blueprint $table) => $table->string('name')->nullable()->change());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Sengaja tidak menghapus kolom: di lokal kolom ini milik tabel users SIAKAD bersama
    }
};
