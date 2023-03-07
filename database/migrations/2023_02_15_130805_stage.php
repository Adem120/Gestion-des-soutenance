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
        schema::create('stage',function (Blueprint $table) {
            $table->id('id');
           
            $table->foreignId('idetud')->nullable()->constrained('condidates')->onDelete('cascade'); 
            $table->foreignId('idens')->nullable()->constrained('enseignants')->onDelete('cascade');
            $table->foreignId('idens2')->nullable()->constrained('enseignants')->onDelete('cascade');
            $table->foreignId('idsal')->nullable()->constrained('salles')->onDelete('cascade');
           $table->integer('stage');
           $table->foreignId('iddate')->nullable()->constrained('sessiondate')->onDelete('cascade');
           $table->time('heuredebut');


        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
