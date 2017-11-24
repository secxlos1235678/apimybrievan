<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Mitra extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'mitra', function ( Blueprint $table ) {
            $table->increments( 'kode' );
            $table->text( 'keterangan' )->nullable();
			$table->foreign( 'kode' )
                ->references( 'kode' )->on( 'mitra' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'mitra' );
    }
}
