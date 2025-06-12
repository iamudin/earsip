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
        Schema::table('pejabats', function (Blueprint $table) {
            $table->foreignUuid('atasan_id')->nullable();
            });

    }
    public function down()
    {

    }
};
