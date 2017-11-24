<?php

namespace App\Http\Controllers\API\v1\Int;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\API\v1\CustomerRequest;
use App\Events\Customer\CustomerVerify;
use App\Models\Customer;
use App\Models\CustomerDetail;
use App\Models\EForm;
use App\Models\User;
use Sentinel;
use DB;

class CustomerController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index( Request $request )
	{
		$limit = $request->input( 'limit' ) ?: 10;
		$customers = User::getCustomers( $request )->paginate( $limit );
		return response()->success( [
			'message' => 'Sukses',
			'contents' => $customers
		], 200 );
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \App\Http\Requests\API\v1\CustomerRequest  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store( CustomerRequest $request )
	{
		$data = $request->all();
		$product_leads = '';
		if ( isset($request->product_leads) ){
			$product_leads = $request->product_leads;
		}

		if ( $product_leads == '' || $product_leads == 'kpr' ){
			DB::beginTransaction();
			$customer = Customer::create( $request->all() );

			DB::commit();
			return response()->success( [
				'message' => 'Data nasabah berhasil ditambahkan.',
				'contents' => $customer
			], 201 );

		} elseif ( $product_leads == 'briguna' ) {
			$data['address'] = $data['alamat'].' rt '.$data['rt'].'/rw '.$data['rw'].', kelurahan='.
								$data['kelurahan'].'kecamatan='.$data['kecamatan'].','.$data['kota'].' '.$data['kode_pos'];

			$data['address_domisili'] = $data['alamat_domisili'].' rt '.$data['rt_domisili'].'/rw '.
								$data['rw_domisili'].', kelurahan='.$data['kelurahan_domisili'].'kecamatan='.$data['kecamatan_domisili'].','.$data['kota_domisili'].' '.$data['kode_pos_domisili'];
			DB::beginTransaction();
			$customer = Customer::create( $data );

			DB::commit();
			return response()->success( [
				'message' => 'Data nasabah berhasil ditambahkan.',
				'contents' => $data
			], 201 );

		}
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \App\Http\Requests\API\v1\CustomerRequest  $request
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function update( CustomerRequest $request, $id )
	{
		DB::beginTransaction();
		$customer = Customer::findOrFail( $id );
		$customer->update( $request->all() );

		DB::commit();
		return response()->success( [
			'message' => 'Data nasabah berhasil dirubah.',
			'contents' => $customer
		] );
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  string  $type
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show( $type, $id )
	{
		$customerDetail = CustomerDetail::where( 'nik', '=', $id )->first();

		if (count($customerDetail) > 0) {
			$customer = Customer::findOrFail( $customerDetail->user_id );
		} else {
			$customer = Customer::findOrFail( $id );
		}
		return response()->success( [
			'message' => 'Sukses',
			'contents' => $customer
		], 200 );
	}

	/**
	 * Verify the specified resource in storage.
	 *
	 * @param  \App\Http\Requests\API\v1\CustomerRequest  $request
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function verify( CustomerRequest $request, $id )
	{
		DB::beginTransaction();
		$customer = Customer::findOrFail( $id );
		$customer->verify( $request->except('join_income') );
		$eform = EForm::generateToken( $customer->personal['user_id'] );

		DB::commit();
		if( $request->verify_status == 'verify' ) {
			event( new CustomerVerify( $customer, $eform ) );
			return response()->success( [
				'message' => 'Email telah dikirim kepada nasabah untuk verifikasi data nasabah.',
				'contents' => $customer
			] );
		} else if( $request->verify_status == 'verified' ) {
			return response()->success( [
				'message' => 'Data nasabah telah di verifikasi.',
				'contents' => []
			] );
		}
	}
}
