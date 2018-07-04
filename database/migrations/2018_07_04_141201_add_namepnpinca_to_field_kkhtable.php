<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNamepnpincaToFieldKkhtable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table( 'kartu_kredit_histories', function ( Blueprint $table ) {
            $table->text( 'nama_pinca' );
            $table->text('pn_pinca');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kartu_kredit_histories', function (Blueprint $table) {
            $table->dropColumn('nama_pinca');
            $table->dropColumn('pn_pinca');
        });
    }
}
