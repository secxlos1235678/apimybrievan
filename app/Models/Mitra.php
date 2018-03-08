<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Builder;

use Illuminate\Http\Request;
use Sentinel;
use Asmx;
use RestwsHc;
use Cartalyst\Sentinel\Users\EloquentUser as Authenticatable;

class Mitra extends Authenticatable  {


	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'mitra';
	
	   public function scopeFilter( $query, Request $request )
    {
		
	  $kode= '';
        $mitra = $query->where( function( $mitra ) use( $request, &$user ) {
		
						$key = $request->input('key');
						
						 if( $request->has( 'key' ) ) {
								$BRANCH_CODE = $request->input('key');
								$branchcis ='';
								if(strlen($BRANCH_CODE)=='5'){
									$branchcis = $BRANCH_CODE;
									/* for($i=0;$i<5;$i++){
										$cek = substr($BRANCH_CODE,$i,1);
										if($cek!=0){
											$branchcis = substr($BRANCH_CODE,$i,4);
											$i = 5;
										}
									} */
								}else{								
										$o = strlen($BRANCH_CODE);
										$branchut = '';
										for($y=$o;$y<5;$y++){
											if($y==$o){
												$branchut = '0'.$BRANCH_CODE;
											}else{
												$branchut = '0'.$branchut;
											}
										} 
										$branchcis = $branchut;	
								}
								\Log::info($branchcis);
						 }
						 $mitra->whereRaw('"BRANCH_CODE" IN '.$key);
						 //$mitra->Where('BRANCH_CODE', $key);
        } );
			 if(!$request->has( 'internal' )){
				
				$kode = $request->input('kode');
				$mitra->whereRaw('LOWER("NAMA_INSTANSI") LIKE ? ',["%".trim(strtolower($kode))."%"]);
			} 
				//$mitra->where('LOWER(NAMA_INSTANSI)','like','%LOWER('.$kode.')%');
				$mitra->orderBy('NAMA_INSTANSI', 'ASC');
				$mitra = $mitra->select([
                    '*',
                     \DB::Raw(" case when mitra.kode is not null then 2 else 1 end as new_order ")
                ]);
		
        \Log::info($mitra->toSql());

        return $mitra;
    }

}
