<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('booking_events', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Event Title
            $table->unsignedBigInteger('fk_customer'); // Foreign key for Customer

            // Price and Discounts
            // $table->decimal('aggregable_price', 10, 2)->default(0);
            // $table->decimal('non_aggregable_price', 10, 2)->default(0);
            $table->decimal('total_price', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->nullable();
            $table->string('discount_percen_flat')->nullable();
            $table->decimal('final_price', 10, 2)->default(0);
            $table->decimal('vat_amount', 10, 2)->default(0);
            $table->decimal('final_price_with_vat', 10, 2)->default(0);
            $table->text('note')->nullable();
            $table->boolean('is_done')->default(0); // 0 means not done, 1 means done
            // User and Timestamp Details
            $table->unsignedBigInteger('created_by');
            $table->timestamp('create_date')->useCurrent();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('update_date')->nullable();
            $table->timestamps();



            // Foreign Key Constraint
            $table->foreign('fk_customer')->references('id')->on('customers')->onDelete('cascade');
        });

        Schema::create('booking_event_slots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_event_id'); // Foreign key to booking_events
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->string('from_time')->nullable(); // Changed from integer to time
            $table->string('to_time')->nullable(); // Changed from integer to time
            $table->decimal('aggregable_price', 10, 2); // Price of the slot calculated
            $table->decimal('non_aggregable_price', 10, 2); // Price of the slot calculated
            $table->decimal('slot_price', 10, 2); // Price of the slot calculated
            $table->timestamps();

            // Foreign Key Constraints
            $table->foreign('booking_event_id')->references('id')->on('booking_events')->onDelete('cascade');
        });

        Schema::create('booking_event_assets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_event_id'); // Foreign key to booking_events
            $table->unsignedBigInteger('fk_asset'); // Foreign key to assets
            $table->integer('asset_qty')->nullable(); // Quantity (only for aggregable assets)
            $table->decimal('asset_price', 10, 2); // Price of the asset
            $table->decimal('total', 10, 2); // Price of the asset
            $table->timestamps();

            // Foreign Key Constraints
            $table->foreign('booking_event_id')->references('id')->on('booking_events')->onDelete('cascade');
            $table->foreign('fk_asset')->references('id')->on('assets')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('booking_event_assets');
        Schema::dropIfExists('booking_event_slots');
        Schema::dropIfExists('booking_events');
    }
};
