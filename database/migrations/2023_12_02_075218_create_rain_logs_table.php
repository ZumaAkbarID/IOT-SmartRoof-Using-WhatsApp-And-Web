<?php

use App\Enums\StatusHujan;
use App\Enums\StatusRoof;
use App\Enums\StatusSistem;
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
        Schema::create('rain_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('status_sistem', [StatusSistem::AUTO->value, StatusSistem::MANUAL->value]);
            $table->enum('status_hujan', [StatusHujan::HUJAN->value, StatusHujan::CERAH->value]);
            $table->enum('status_roof', [StatusRoof::OPEN->value, StatusRoof::CLOSED->value]);
            $table->float('intensity');
            $table->dateTime('datetime');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rain_logs');
    }
};
