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
        Schema::create('progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained()->restrictOnUpdate()->cascadeOnDelete();
            $table->foreignId('project_id')->constrained()->restrictOnUpdate()->cascadeOnDelete();
            $table->integer('jira')->nullable();
            $table->integer('development')->nullable();
            $table->integer('testing')->nullable();
            $table->integer('overall')->nullable();
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
        Schema::dropIfExists('progress');
    }
};
