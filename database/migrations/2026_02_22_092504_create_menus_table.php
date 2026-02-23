<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('order_no')->default(1);
            $table->enum('type', ['Sub(Card)', 'Embedded URL']);
            $table->string('url')->nullable(); // Used if 'Embedded URL' is selected
            $table->string('icon_path')->nullable(); // Path to uploaded icon image
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('menus');
    }
};