<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('parcel_postcode', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parcel_id');
            $table->string('postcode');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('parcel_id')->references('id')->on('parcels')->onDelete('cascade');
        });
    }

    public function down() {
        Schema::dropIfExists('parcel_postcode');
    }
};
