<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMitLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mit_logs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('ip_address', 50)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('url')->nullable();
            $table->string('description')->nullable();
            $table->text('details')->nullable();
            $table->integer('user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mit_logs');
    }
}
