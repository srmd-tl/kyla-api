<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKylaProcessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kyla_processes', function (Blueprint $table) {
            $table->id();
            $table->string("location")->nullable();
            $table->boolean("alert_via_sms")->default(0);
            $table->boolean("alert_via_email")->default(0);

            $table->foreignId("user_id");
            $table->timestamps();
            //Foreign Keys
            $table->foreign("user_id")
                ->references("id")
                ->on("users")
                ->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kyla_processes');
    }
}
