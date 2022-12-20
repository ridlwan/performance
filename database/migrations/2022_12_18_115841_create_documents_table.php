<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('sender_nopeg');
            $table->string('sender_name');
            $table->string('sender_unit');
            $table->date('sender_date');
            $table->string('receiver_nopeg')->nullable();
            $table->string('receiver_name')->nullable();
            $table->string('receiver_unit')->nullable();
            $table->date('receiver_date')->nullable();
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documents');
    }
};
