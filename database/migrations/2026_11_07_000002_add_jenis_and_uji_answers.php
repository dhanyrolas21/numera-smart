<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->string('jenis')->default('diagnostik')->after('tingkat'); // diagnostik atau uji
        });

        Schema::table('assessment_logs', function (Blueprint $table) {
            $table->text('uji_answers')->nullable()->after('remediation_status'); // {"idSoal":"opsi"}
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('jenis');
        });

        Schema::table('assessment_logs', function (Blueprint $table) {
            $table->dropColumn('uji_answers');
        });
    }
};