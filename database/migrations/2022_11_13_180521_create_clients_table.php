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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('address', 255)->nullable();
            $table->string('zip', 20)->nullable();
            $table->string('city', 30)->nullable();
            $table->string('country', 30)->nullable();
            $table->bigInteger('phone')->nullable();
            $table->string('email', 255)->nullable();
            $table->bigInteger('idCard')->nullable();
            $table->date('expiry')->nullable();
            $table->bigInteger('nif')->nullable();
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
        Schema::dropIfExists('clients');
    }
};
