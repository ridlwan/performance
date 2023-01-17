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
        Schema::create('supports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained()->restrictOnUpdate()->cascadeOnDelete();
            $table->integer('waiting_for_support')->nullable();
            $table->integer('waiting_for_customer')->nullable();
            $table->integer('waiting_for_partner')->nullable();
            $table->integer('escalated')->nullable();
            $table->integer('pending')->nullable();
            $table->integer('in_progress')->nullable();
            $table->integer('resolved')->nullable();
            $table->integer('completed')->nullable();
            $table->integer('closed')->nullable();
            $table->integer('canceled')->nullable();
            $table->integer('sla')->nullable();
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
        Schema::dropIfExists('supports');
    }
};
