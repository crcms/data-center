<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataCenterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_center', function (Blueprint $table) {
            $table->string('channel', 50)->comment('Channel');
            $table->string('key', 50)->comment('Key');
            $table->string('type', 20)->comment('Constant type');
            $table->text('value')->nullable()->comment('Value');
            $table->string('remark', 255)->nullable()->comment('Remark');
            $table->primary(['channel', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_center');
    }
}
