<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('code');
            $table->string('name');
            $table->float('purchase_price')->nullable();
            $table->float('selling_price');
            $table->integer('packaging')->nullable();
            $table->integer('margin')->nullable();
            $table->float('discount')->nullable();
            $table->integer('stock');
            
            $table->string('tags')->nullable();
            
            $table->bigInteger('categories_id');
            $table->bigInteger('units_id');
            
            $table->softDeletes();
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
        Schema::dropIfExists('products');
    }
}
