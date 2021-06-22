<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParkingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parkings', function (Blueprint $table) {
            $table->id();
            $table->timestamp('entrance')->useCurrent();
            $table->timestamp('exit')->nullable();
            $table->double('total_minutes', 15, 2)->nullable();
            $table->double('cost', 15, 2)->nullable();
            $table->string('vehicle_id', 9)
                  ->references('license_plate')
                  ->on('vehicles')
                  ->onDelete('cascade');
            
                  
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
        Schema::dropIfExists('parkings');
    }
}
