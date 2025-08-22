<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('obat_rusak', function (Blueprint $table) {
            $table->id();
            $table->foreignId('obat_id')->constrained('obats')->onDelete('cascade');
            $table->integer('jumlah'); // jumlah obat yang rusak
            $table->date('tanggal'); // tanggal dicatat
            $table->text('keterangan')->nullable(); // alasan kerusakan (misal: kadaluarsa, pecah, dll.)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('obat_rusak');
    }
};
