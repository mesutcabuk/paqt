<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('rides', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resident_id');
            $table->unsignedBigInteger('taxi_id');
            $table->unsignedBigInteger('pickup_address_id');
            $table->unsignedBigInteger('destination_address_id');
            $table->integer('distance');
            $table->enum('status', ['Pending', 'Cancelled', 'Revoked', 'Completed'])->default('Pending');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('rides');
    }
};

