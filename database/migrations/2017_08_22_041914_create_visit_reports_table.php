<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisitReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'visit_reports', function ( Blueprint $table ) {
            $table->increments( 'id' );
            $table->integer( 'eform_id' )->unsigned();
            $table->string( 'purpose_of_visit' )->nullable();
            $table->string( 'result' )->nullable();
            $table->string( 'photo_with_customer' )->nullable();
            $table->text( 'pros' )->nullable();
            $table->text( 'cons' )->nullable();
            $table->string( 'seller_name' )->nullable();
            $table->string( 'seller_address' )->nullable();
            $table->string( 'seller_phone' )->nullable();
            $table->bigInteger( 'selling_price' )->nullable();
            $table->text( 'reason_for_sale' )->nullable();
            $table->string( 'relation_with_seller' )->nullable();
            $table->timestamps();

            $table->foreign( 'eform_id' )
                ->references( 'id' )->on( 'eforms' )
                ->onUpdate( 'cascade' )->onDelete( 'cascade' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'visit_reports' );
    }
}
