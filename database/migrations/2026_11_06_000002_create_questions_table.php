<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('dunia');
            $table->text('context_text');
            $table->json('options');
            $table->char('correct_option', 1);
            $table->unsignedBigInteger('correct_value');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('questions'); }
};
