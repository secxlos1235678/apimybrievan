<?php

namespace App\Http\Controllers\API\v1\Int;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\API\v1\Int\VerificationRequest;
use App\Models\EForm;
use RestwsHc;

class VerificationController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Http\Requests\API\v1\Int\VerificationRequest  $request
     * @param  int  $eform_id
     * @return \Illuminate\Http\Response
     */
    public function show( VerificationRequest $request, $eform_id )
    {
        $eform = EForm::findOrFail( $eform_id );
        $customer = $eform->customer;
        $kemendagri = RestwsHc::setBody( [
            'request' => json_encode( [
                'requestMethod' => 'get_kemendagri_profile_nik',
                'requestData' => [
                    'nik'     => $eform->nik,
                    'id_user' => $request->header( 'pn' )
                ],
            ])
        ] )->setHeaders( [
            'Authorization' => $request->header( 'Authorization' )
        ] )->post( 'form_params' );
        if( $kemendagri[ 'responseCode' ] == '00' ) {
            $kemendagri_result = [
                'name' => $kemendagri[ 'responseData' ][ 0 ][ 'namaLengkap' ],
                'gender' => $kemendagri[ 'responseData' ][ 0 ][ 'jenisKelamin' ],
                'birth_place' => $kemendagri[ 'responseData' ][ 0 ][ 'tempatLahir' ],
                'birth_date' => $kemendagri[ 'responseData' ][ 0 ][ 'tanggalLahir' ],
                'phone' => '',
                'mobile_phone' => '',
                'address' => $kemendagri[ 'responseData' ][ 0 ][ 'alamat' ],
                'citizenship' => '',
                'status' => $kemendagri[ 'responseData' ][ 0 ][ 'statusKawin' ],
                'address_status' => '',
                'mother_name' => $kemendagri[ 'responseData' ][ 0 ][ 'namaIbu' ]
            ];
        } else {
            $kemendagri_result = [
                'name' => '',
                'gender' => '',
                'birth_place' => '',
                'birth_date' => '',
                'phone' => '',
                'mobile_phone' => '',
                'address' => '',
                'citizenship' => '',
                'status' => '',
                'address_status' => '',
                'mother_name' => ''
            ];
        }

        $cif = RestwsHc::setBody( [
            'request' => json_encode( [
                'requestMethod' => 'get_customer_profile_nik',
                'requestData'   => [ 'nik' => $request->nik ],
            ] )
        ] )->post('form_params');
        if( $cif[ 'responseCode' ] == '00' ) {
            $cif_result = [
                'cif_number' => $cif[ 'responseData' ][ 'info' ][ 0 ][ 'cifno' ],
                'name' => $cif[ 'responseData' ][ 'info' ][ 0 ][ 'nama_sesuai_id' ],
                'gender' => $cif[ 'responseData' ][ 'info' ][ 0 ][ 'jenis_kelamin' ],
                'birth_place' => $cif[ 'responseData' ][ 'info' ][ 0 ][ 'tempat_lahir' ],
                'birth_date' => $cif[ 'responseData' ][ 'info' ][ 0 ][ 'tanggal_lahir' ],
                'phone' => $cif[ 'responseData' ][ 'info' ][ 0 ][ 'telp_rumah' ],
                'mobile_phone' => $cif[ 'responseData' ][ 'info' ][ 0 ][ 'handphone' ],
                'address' => $cif[ 'responseData' ][ 'info' ][ 0 ][ 'alamat_id1' ],
                'citizenship' => $cif[ 'responseData' ][ 'info' ][ 0 ][ 'kewarganegaraan' ],
                'status' => $cif[ 'responseData' ][ 'info' ][ 0 ][ 'status_nikah' ],
                'address_status' => '',
                'mother_name' => $cif[ 'responseData' ][ 'info' ][ 0 ][ 'nama_ibu_kandung' ]
            ];
        } else {
            $cif_result = [
                'cif_number' => '',
                'name' => '',
                'gender' => '',
                'birth_place' => '',
                'birth_date' => '',
                'phone' => '',
                'mobile_phone' => '',
                'address' => '',
                'citizenship' => '',
                'status' => '',
                'address_status' => '',
                'mother_name' => ''
            ];
        }

        return response()->success( [
            'message' => 'Sukses',
            'contents' => [
                'customer' => [
                    'id' => $customer->id,
                    'is_completed' => $customer->is_completed,
                    'name' => $customer->full_name,
                    'gender' => $customer->gender,
                    'birth_place_id' => $customer->detail->birth_place_id,
                    'birth_place' => $customer->detail->birth_place_id,
                    'birth_date' => $customer->detail->birth_date,
                    'phone' => $customer->phone,
                    'mobile_phone' => $customer->mobile_phone,
                    'address' => $customer->detail->address,
                    'citizenship_id' => $customer->detail->citizenship_id,
                    'citizenship' => $customer->detail->citizenship_id,
                    'status' => $customer->detail->status,
                    'address_status' => $customer->detail->address_status,
                    'mother_name' => $customer->detail->mother_name
                ],
                'kemendagri' => $kemendagri_result,
                'cif' => $cif_result
            ]
        ], 200 );
    }
}
