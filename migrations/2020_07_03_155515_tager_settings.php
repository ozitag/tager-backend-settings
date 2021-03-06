<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TagerSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tager_settings', function (Blueprint $table) {
            $table->id();

            $table->string('key');
            $table->string('type');
            $table->string('label')->nullable();
            $table->longText('value')->nullable();
            $table->boolean('changed')->notNull()->defaultValue(false);

            $table->timestamps();

            $table->unique('key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tager_settings');
    }
}
