<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_type'); // Room, Table, Chairs
            $table->string('mode'); // Aggregable, Non-Aggregable
            $table->integer('asset_size')->nullable(); // Only for Aggregable mode
            $table->integer('available_quantity')->nullable()->default(1);
            $table->decimal('rental_value', 10, 2);
            $table->string('fixed_hourly');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('assets');
    }
};
