<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('residents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->unsignedBigInteger('home_address_id');
            $table->unsignedBigInteger('parcel_id');
            $table->integer('default_budget');
            $table->integer('remaining_budget');
            
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('residents');
    }
};
