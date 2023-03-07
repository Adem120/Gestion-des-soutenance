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
        Schema::create('reclamation', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('idetud')->nullable()->constrained('condidates')->onDelete('cascade'); 
            $table->foreignId('idens')->nullable()->constrained('enseignants')->onDelete('cascade');
            $table->string('sujet');
            $table->string('description');
            $table->string('status');
           
          
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
