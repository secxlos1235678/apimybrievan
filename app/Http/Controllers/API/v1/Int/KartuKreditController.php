<?php

namespace App\Http\Controllers\API\v1\Int;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\KartuKredit;
use App\Models\CustomerDetail;
use GuzzleHttp\Client;
use App\Models\UserServices;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Models\User;
use App\Models\EForm;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

use App\Http\Requests\API\v1\KreditRequest;

use App\Models\KreditEmailGenerator;
use App\Models\KartuKreditHistory;

use RestwsHc;

class KartuKreditController extends Controller{
	
    public $hostLos = '';
    public $tokenLos = '';
	
	// public $tokenLos = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MSwidXNlcm5hbWUiOiJsb3NhcHAiLCJhY2Nlc3MiOlsidGVzIl0sImp0aSI6IjhjNDNlMDNkLTk5YzctNDJhMC1hZDExLTgxODUzNDExMWNjNCIsImlhdCI6MTUxODY2NDUzOCwiZXhwIjoxNjA0OTc4MTM4fQ.ocz_X3duzyRkjriNg0nXtpXDj9vfCX8qUiUwLl1c_Yo';

//     LOS_HOST =172.18.65.52:7334
// LOS_TOKEN = eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MSwidXNlcm5hbWUiOiJsb3NhcHAiLCJhY2Nlc3MiOlsidGVzIl0sImp0aSI6IjEwMTI5Nzg3LWMxNzctNDY5Mi1iZTBjLWM4MTI2MTc2MTc1MSIsImlhdCI6MTUyNjM3NTgyNywiZXhwIjoxNjEyNjg5NDI3fQ.zg1Oat5knSu4TgZ8PrB0rJ5jMfKQccpabFkjPiQ7m4g
	
	public $hostPefindo = '10.35.65.167:6969';

    function __construct(){
        $this->hostLos = env('LOS_HOST','10.107.11.111:9975');
        $this->tokenLos = env('LOS_TOKEN','eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MSwidXNlcm5hbWUiOiJsb3NhcHAiLCJhY2Nlc3MiOlsidGVzIl0sImp0aSI6IjhjNDNlMDNkLTk5YzctNDJhMC1hZDExLTgxODUzNDExMWNjNCIsImlhdCI6MTUxODY2NDUzOCwiZXhwIjoxNjA0OTc4MTM4fQ.ocz_X3duzyRkjriNg0nXtpXDj9vfCX8qUiUwLl1c_Yo');
    }

	public function contohemail(Request $req){
         $requestPost =[
                'app_id' => 'mybriapi',
                'branch_code' => $req['branch_code']
            ];
            
            $list_uker_kanca = RestwsHc::setBody([
                        'request' => json_encode([
                                'requestMethod' => 'get_list_uker_from_cabang',
                                'requestData' => $requestPost
                        ])
                ])
                ->post( 'form_params' );

            return response()->success( [
                    'message' => 'Sukses',
                    'contents' => $list_uker_kanca
            ], 200 );
    }

    
	function checkUser($nik){
        $check = CustomerDetail::where('nik', $nik)->get();
        if(count($check) == 0){
            return response()->json([
            	//user gak ketemu. crate baru dulu
            		'responseCode'=>'01',
                    'message' => 'Data dengan nik tersebut tidak ditemukan'
                    ]);
        }
        return true;
	}

	function checkDedup($nik){

		 $header = ['access_token'=> $this->tokenLos];
			 $client = new Client();
			 try{
                $res = $client->request('POST',$this->hostLos, ['headers' =>  $header,
                        'form_params' => ['nik' => $nik]
                    ]);
            }catch (RequestException $e){
                return response()->error([
                    'responseCode'=>'99',
                    'responseMessage'=> $e->getMessage()
                ],400);
            }


            return true;
	}


	public function getAllInformation(){
		$TOKEN_LOS = $this->tokenLos;
		$client = new Client();
		$host = $this->hostLos;

		try{
			$statusPernikahan = $this->getListStatusPernikahan($TOKEN_LOS,$client,$host);
			$statusTempatTinggal = $this->getListTempatTinggal($TOKEN_LOS,$client,$host);
			$kategoriPekerjaan = $this->getListKategoriPekerjaan($TOKEN_LOS,$client,$host);
			$statusPekerjaan = $this->getListStatusPekerjaan($TOKEN_LOS,$client,$host);
			$jumlahKaryawan = $this->getListJumlahKaryawan($TOKEN_LOS,$client,$host);
			$hubunganKeluarga = $this->getListHubunganKeluarga($TOKEN_LOS,$client,$host);
			$listSubBidangUsaha = $this->getListSubBidangUsaha($TOKEN_LOS,$client,$host);

		}catch (RequestException $e){
			return response()->json([
				'responseCode' => '01',
				'responseMessage' => 'Terjadi Kesalahan. Silahkan Tunggu Beberapa Saat Dan Ulangi',
			]);
		}
		
		return response()->json([
			'responseCode'=>0,
			'responseMessage'=>'success',
			'list_pernikahan'=> $statusPernikahan,
			'list_status_tempat_tinggal' => $statusTempatTinggal,
			'list_kategori_pekerjaan' => $kategoriPekerjaan,
			'list_status_pekerjaan' => $statusPekerjaan,
			'list_jumlah_karyawan' => $jumlahKaryawan,
			'list_hubungan_keluarga' => $hubunganKeluarga,
			'list_sub_bidang_usaha' =>$listSubBidangUsaha
		]);
	}

	function getListStatusPernikahan($token,$client,$host){
		
		$res = $client->request('POST',$host.'/api/listStatusPernikahan', ['headers' =>  ['access_token'=>$token]]);

		$responseCode = $res->getStatusCode();
		if ($responseCode == 200){
			$body = $res->getBody();
			$obj = json_decode($body);
			$data = $obj->responseData;

			return $data;
		}else{
			//error
			return 'error ambil list pernikahan';
		}
	}

	function getListTempatTinggal($token,$client,$host){

		$res = $client->request('POST',$host.'/api/listTempatTinggal', ['headers' =>  ['access_token'=>$token]]);

		$body = $res->getBody();
		$obj = json_decode($body);
		$data = $obj->responseData;

		return $data;
	}

	function getListKategoriPekerjaan($token,$client,$host){

		$res = $client->request('POST',$host.'/api/listKategoriPekerjaan', ['headers' =>  ['access_token'=>$token]]);
		
		$body = $res->getBody();
		$obj = json_decode($body);
		$data = $obj->responseData;

		return $data;
	}

	function getListStatusPekerjaan($token,$client,$host){

		$res = $client->request('POST',$host.'/api/listStatusPekerjaan', ['headers' =>  ['access_token'=>$token]]);
		
		$body = $res->getBody();
		$obj = json_decode($body);
		$data = $obj->responseData;

		return $data;
	}

	function getListJumlahKaryawan($token,$client,$host){

		$res = $client->request('POST',$host.'/api/listJumlahKaryawan', ['headers' =>  ['access_token'=>$token]]);
		
		$body = $res->getBody();
		$obj = json_decode($body);
		$data = $obj->responseData;

		return $data;
	}

	function getListHubunganKeluarga($token,$client,$host){

		$res = $client->request('POST',$host.'/api/listHubunganKeluarga', ['headers' =>  ['access_token'=>$token]]);
		
		$body = $res->getBody();
		$obj = json_decode($body);
		$data = $obj->responseData;

		return $data;
	}

	function getListSubBidangUsaha($token,$client,$host){

		$res = $client->request('POST',$host.'/api/listsubbidangusaha', ['headers' =>  ['access_token'=>$token]]);
		
		$body = $res->getBody();
		$obj = json_decode($body);
		$data = $obj->responseData;

		return $data;
	}

	public function checkNIK(Request $req){
		$client = new Client();
		$host = 'apimybri.bri.co.id/api/v1';

		//body
		$nik = $req['nik'];
		//header
		$pn =  $req->header('pn');
		// $branch = $req->header('branch');
		$auth = $req->header('Authorization');
		
		try{
			$res = $client
			->request('POST',
				$host.'/int/crm/account/customer_nik',
				['form_params'=>['nik' => $nik,],
				'headers'=>['pn'=> $pn,'Authorization'=>$auth]
				]
			);
		}catch(RequestException $e){


      	 	return response()->json([
      	 		'responseCode'=>'99',
				'responseMessage'=>$e->getMessage()
      	 	]);
		}

		$body = $res->getBody();
		$obj = json_decode($body);
		$contents = $obj->contents;

		return response()->json([
			'responseCode' => 00,
			'responseMessage' => 'sukses',
			'contents'=>$contents
		]);

		return $body;
		// return response()->json([
		// 	'nik'=>$nik,
		// 	'pn' =>$pn,
		// 	'Authorization'=>$auth

		// ]);
	}

	public function sendUserDataToLos(Request $req){

		$TOKEN_LOS = $this->tokenLos;

		$host = $this->hostLos;

		$validatedData = $this->validate($req,[
            'PersonalName' => 'required',
            'PersonalNIK' => 'required',
            'PersonalTempatLahir' => 'required',
            'PersonalTanggalLahir' => 'required',
        ]);

		$kk = new KartuKredit();
		$informasiLos = $kk->convertToAddDataLosFormat($req,'add');

		$client = new Client();

		try{
			$res = $client
			->request('POST',
				$host.'/api/addData',
				['headers'=>['access_token'=> $TOKEN_LOS],
				'form_params'=> $informasiLos,
				]
			);
		}catch (RequestException $e){

			return  $e->getMessage();
		}

		$body = $res->getBody();
		$obj = json_decode($body);
		$data = $obj->responseData;
        $rc = $obj->responseCode;
        if($rc != 99){
            $appregno = $data->apRegno;

            $addAppregno = KartuKredit::where('eform_id',$req->eform_id)
            ->update(['appregno'=>$appregno]);
            // $data['apregno'] = $appregno;
            // $data['kodeProses'] = '1';
            // $data['kanwil'] = 

            return response()->json($obj);
        }else{
            return response()->json($obj);
        }
		
    }

    public function updateDataLos(KreditRequest $req){
    	//saat verifikasi
    	$header = ['access_token'=> $this->tokenLos];
    	$host = $this->hostLos.'/api/updateData';
    	$client = new Client();
    	
    	$request = $req->all();
    	$eform_id = $request['eform_id'];
    	$request['appNumber'] = $this->getApregnoFromKKDetails($eform_id);
        \Log::info('appnumber ='.$request['appNumber']);

    	
    	$kk = new KartuKredit();
    	$informasiLos = $kk->convertToAddDataLosFormat($request,'update');

    	try{
			$res = $client
			->request('POST',
				$host,
				['headers'=>$header,
				'form_params'=> $informasiLos,
				]
			);
		}catch (RequestException $e){
			return  $e->getMessage();
		}
        
        $body = $res->getBody();
        $obj = json_decode($body);
        $rc = $obj->responseCode;
        //if gak rollback
        if ($rc != 99){
            //update response status jadi pending
            $updateStatus = EForm::where('id',$eform_id)
            ->update(['response_status'=>'pending']);

            $alamatDom = $request['PersonalAlamatDomisili'].' '.$request['PersonalAlamatDomisili2']
            .' '.
            $request['PersonalAlamatDomisili3'].', RT/RW '.$request['Rt'].'/'.$request['Rw'].', Kecamatan '.$request['Camat'];

            //update data di eform
            $update = EForm::where('id',$eform_id)->update([
                'address'=>$alamatDom
            ]);

            //update email ke users
            //1.get user id from kkdetails
            $kd = KartuKredit::where('eform_id',$eform_id)->first();
            $userId = $kd['user_id'];
            //2. update email
            $updateUser = User::where('id',$userId)->update([
                'email'=> $request['PersonalEmail']
            ]);

            return response()->json($obj);
        }else{
            return response()->json($obj);
        }
		

    }

    function getApregnoFromKKDetails($eform_id){
    	$kk = KartuKredit::where('eform_id',$eform_id)->first();
   		$apRegno = $kk['appregno'];
    	return $apRegno;
    }

    public function cekDataNasabah($apRegno){
    	$host = $this->hostLos.'/api/dataNasabah';
    	$header = ['access_token'=> $this->tokenLos];
    	$client = new Client();

    	try{
    		$res = $client->request('POST',$host, ['headers' =>  $header,
    				'form_params' => ['apRegno' => $apRegno]
    			]);
    	}catch (RequestException $e){
    		return  $e->getMessage();
    	}

    	$body = $res->getBody();
    	$obj = json_decode($body);

    	return response()->json($obj);

    }

    function generateSmsCode(){
    	$code =mt_rand(102030, 999999);
    	return $code;
    }

    public function sendSMS(KreditRequest $req){
    	$pn = $req['handphone'];
    	$eformid = $req['eform_id'];
		$kk = KartuKredit::where('eform_id',$eformid)->first();
    	$apregno = $kk['appregno'];
    	$code = $this->generateSmsCode();
    	$message = 'Kode unik anda adalah '.$code.' . Periksa dan isi kode verifikasi pada field verifikasi yang kami sediakan pada email';

    	//save code ke kredit details
    	$updateCode = KartuKredit::where('appregno',$apregno)->update([
    		'verification_code'=>$code
    	]);


    	$host = $this->hostLos.'/notif/tosms';
    	$header = ['access_token'=> $this->tokenLos];
    	$client = new Client();

    	try{
    		$res = $client->request('POST',$host, ['headers' =>  $header,
    				'form_params' => ['handphone' => $pn,'message'=>$message]
    			]);
    	}catch (RequestException $e){
    		return  $e->getMessage();
    	}

    	$body = $res->getBody();
    	$obj = json_decode($body);

    	return response()->json([
    		'responseCode' => '00',
    		'contents' =>$obj
    	]);
    }

    public function toEmail(Request $req){
    	//email, subject, message
    	$email = $req['email'];
    	$eformid = $req['eform_id'];

    	$kk = KartuKredit::where('eform_id',$eformid)->first();
    	$apregno = $kk['appregno'];

    	// $dataKredit = KartuKredit::where('appregno',$apregno)->first();
    	$emailGenerator = new KreditEmailGenerator();
    	// $routes = 'apimybri.bri.co.id/api/v1/int/kk/verifyemail';
    	// $routes = 'api.dev.net/api/v1/int/kk/verifyemail';
    	$appEnv = env('KREDIT_EMAIL_GENERATOR_POSITION','prod');
     	if ($appEnv == 'dev'){
         	$routes = 'apimybridev.bri.co.id/api/v1/int/kk/verifyemail';
      	}else{
      		$routes = 'apimybri.bri.co.id/api/v1/int/kk/verifyemail';
      	}
    	$message = $emailGenerator
    	->sendEmailVerification($kk,$apregno,$routes);
    	\Log::info('======== data kredit =========');
   		\Log::info($kk);
   		// $header = ['access_token'=> $this->tokenLos];
    	// $host = '10.107.11.111:9975/api/updateData';
    	// $client = new Client();
    	
    	$host = $this->hostLos.'/notif/toemail';
    	$header = ['access_token'=> $this->tokenLos];
    	$client = new Client();

    	try{
    		$res = $client->request('POST',$host, ['headers' =>  $header,
    				'form_params' => ['email' => $email,'subject'=>'Email Verifikasi Pengajuan Kartu Kredit BRI','message'=>$message]
    			]);
    	}catch (RequestException $e){
    		return  $e->getMessage();
    	}

    	$body = $res->getBody();
    	$obj = json_decode($body);

    	return response()->json([
    		'responseCode' => '00',
    		'contents' =>$obj
    	]);
    }


    function verify($eform_id){
    	$updateStatus = EForm::where('id',$eform_id)
		->update(['response_status'=>'verified']);

		return true;
    }

    function isVerified($eform_id){
    	$ef = EForm::where('id',$eform_id)->first();
    	$ver = $ef['response_status'];
    	if($ver == 'verified'){
    		return true;
    	}else{
    		return false;
    	}
    }

     public function checkEmailVerification(Request $request){
     	$req = $request->all();
    	$codeVerif = $request->code;
    	$apRegno = $request->apregno;
    	$data = KartuKredit::where('appregno',$apRegno)->first();
    	$correctCode = $data['verification_code'];
    	$eformid = $data['eform_id'];

    	if($this->isVerified($eformid)){
    		return redirect('https://mybri.bri.co.id/');
    	}else{
    		if ($codeVerif == $correctCode){
    			//update ke eform
    			$updateEform = $this->verify($eformid);
    			$eform = EForm::where('id',$eformid)->first();
    			$refNumber = $eform['ref_number'];
    			$nik =  $eform['nik'];
    			\Log::info('================');
    			\Log::info($refNumber);
    			\Log::info($nik);
    			\Log::info('================');
    			$qrcode = $this->createQrcode($refNumber,$nik);
    			KartuKredit::where('eform_id',$eformid)->update([
    				'qrcode'=>$qrcode
    			]);
    			$em = new KreditEmailGenerator();
    			$kk = KartuKredit::where('eform_id',$eformid)->first();
    			$apregno = $kk['appregno'];
                $updateTanggalVerifikasi = KartuKredit::where('eform_id',$eformid)->update([
                    'tanggal_verifikasi'=>date("Y-m-d")
                ]);
    			$message = $em->convertToFinishVerificationEmailFormat($kk,$apregno);
    			$host = $this->hostLos.'/notif/toemail';
		    	$header = ['access_token'=> $this->tokenLos];
		    	$client = new Client();
		    	$email = $kk['email'];

		    	try{
		    		$res = $client->request('POST',$host, ['headers' =>  $header,
		    				'form_params' => ['email' => $email,'subject'=>'Laporan Verifikasi Email Pengajuan Kartu Kredit BRI','message'=>$message]
		    			]);
		    	}catch (RequestException $e){
		    		return  $e->getMessage();
		    	}
                return view('kartukredit/verifikasi', ['message' => '<h5> Pengajuan kartu kredit anda berhasil diverifikasi </h5><h4 class="subtitle">Bukti hasil verifikasi akan dikirimkan ke email anda</h4>']);
    			// return view('kartukredit/verifikasi');
    		}else{
    			return view('kartukredit/verifikasi', ['message' => '<h5 class = "wrong">Kode verifikasi yang anda masukkan salah</h5>']);
    		}
    	}
    }

    function createQrcode($refnumber,$nik){
    	$filename = $refnumber.'-qrcode.png';
    	QrCode::format('png')->size(250)->generate($refnumber.$nik, public_path('uploads/'.$nik.'/'.$filename));

    	return $filename;
    }

    public function analisaKK(Request $req){
    	$eformId = $req->eform_id;
    	$dataKredit = KartuKredit::where('eform_id',$eformId)->first();

    	$jenisNasabah = $dataKredit['jenis_nasabah'];

    	$apregno = $dataKredit['appregno'];
    	\Log::info('apregno = '.$apregno);
    	$dataLos = $this->cekDataNasabah($apregno);

    	$npwp = $dataKredit['image_npwp'];
    	$ktp = $dataKredit['image_ktp'];
    	$slipGaji = $dataKredit['image_slip_gaji'];

    	$scoring = $this->getScoring($apregno);

    	if ($jenisNasabah == 'debitur'){
    		
    		return response()->json([
    			'responseCode'=>'00',
    			'responseMessage'=>'sukses',
    			'contents'=>$dataKredit,
    			'images'=>[
    				'npwp'=>$npwp,
    				'ktp'=>$ktp,
    				'slip_gaji'=>$slipGaji
    			],
    			'data_los'=> $dataLos,
    			'score'=>$scoring
    		]);
    	}else{
    		$nametag = $dataKredit['image_nametag'];
    		$kartuBankLain = $dataKredit['image_kartu_bank_lain'];

    		return response()->json([
    			'responseCode'=>'00',
    			'responseMessage'=>'sukses',
    			'contents'=>$dataKredit,
    			'images'=>[
    				'npwp'=>$npwp,
    				'ktp'=>$ktp,
    				'slip_gaji'=>$slipGaji,
    				'nametag'=>$nametag,
    				'kartu_bank_lain'=>$kartuBankLain
    			],
    			'data_los'=> $dataLos,
    			'score'=>$scoring
    		]);
    	}

    	//eror
    	return response()->json([
    		'responseCode'=>'01',
    		'responseMessage'=>'terjadi kesalahan'
    	]);
    }

    function getScoring($apRegno){
    	$TOKEN_LOS = $this->tokenLos;
		$client = new Client();
		$host = $this->hostLos;

		try{
			$res = $client
			->request('POST',
				$host.'/api/scoring',
				['headers'=>['access_token'=> $TOKEN_LOS],
				'form_params'=> ['apRegno'=>$apRegno],
				]
			);
		}catch (RequestException $e){
			return response()->json([
				'responseCode'=>'99',
				'responseMessage'=>$e->getMessage()
			]);
		}
		
		$body = $res->getBody();
		$obj = json_decode($body);
		$data = $obj->responseData;

		return $data;
	}

	public function finishAnalisa(KreditRequest $req){
		$apregno = $req->apRegno;
		$eformId= $req->eform_id;
		$catRekAo = $req->catatanRekomendasiAO;
		$rekLimitKartu = $req->rekomendasiLimitKartu;
		$rangeLimit =  $req->range_limit;
		$losScore = $req->los_score;
		$losResult = $this->losScoreResult($losScore);

		if ($losResult == 'proceed'){
			$anStatus = 'analyzed';
		}else{
			$anStatus = 'rejected';
		}

		$dataKK = KartuKredit::where('appregno',$apregno)->first();
		$updateKK = KartuKredit::where('appregno',$apregno)->update([
			'is_analyzed'=>true,
			'catatan_rekomendasi_ao'=>$catRekAo,
			'rekomendasi_limit_kartu'=>$rekLimitKartu,
			'pilihan_kartu'=>$req->cardType,
			'range_limit'=>$rangeLimit,
			'los_score' =>$losScore,
			'analyzed_status'=>$anStatus
		]);

		 //lengkapi data kredit di eform
		$newData = [
			'range_limit'=>$rangeLimit,
			'is_analyzed'=> 'true',
			'los_score' =>$losScore,
			'analyzed_status'=>$anStatus
		];
		$jsonData = json_encode($newData);
        $eform = EForm::where('id',$eformId)->update([
            'kk_details'=>$jsonData
        ]);

		return response()->json([
			'responseCode'=>'00',
			'responseMessage'=>'analisa berhasil',
			'contents'=>$dataKK
		]);

	}

	function losScoreResult($score){
		if ($score >= '550'){
			return 'proceed';
		}else{
			return 'end';
		}
	}

	public function putusanPinca(KreditRequest $req){
		
		$apregno = $req->apRegno;
		$msg = $req->msg;
		$putusan = $req->putusan;
		$limit = $req->limit;

		$kk = new KartuKredit();
		$req = $req->all();
		
		if ($putusan == 'approved'){
			$host = $this->hostLos.'/api/approval';
			$data = $kk->createApprovedRequirements($req);
			$updateLimit = KartuKredit::where('appregno',$apregno)->update([
			'rekomendasi_limit_kartu'=>$limit
		]);

		}else{
			$host = $this->hostLos.'/api/reject';
			$data = $kk->createRejectedRequirements($req);
		}
		
		//kirim ke los.
		$client = new Client();
		try{
			$res = $client->request('POST',$host,
			['headers' => ['access_token'=>$this->tokenLos],
			'form_params'=> $data
			]
			);

		}catch(RequestException $e){
			return response()->json([
				'responseCode'=>'99',
				'responseMessage'=>$e->getMessage()
			]);	
		}

		$eformId= $req['eform_id'];
		//kirim ke db mybri
		$updateKK = KartuKredit::where('appregno',$apregno)->update([
			'approval'=>$putusan,
			'catatan_rekomendasi_pinca'=>$msg
		]);
		//update isfinish eform
		$updateEform = EForm::where('id',$eformId)->update([
			'IsFinish'=>'true'
		]);
		//tampilin ke eform
		$dataKK = KartuKredit::where('appregno',$apregno)->first();
		$rangeLimit =  $dataKK['range_limit'];
		$losScore = $dataKK['los_score'];
		$anStatus = $dataKK['analyzed_status'];


		$newData = [
			'range_limit'=>$rangeLimit,
			'is_analyzed'=> 'true',
			'los_score' =>$losScore,
			'analyzed_status'=>$anStatus,
			'approval'=>$putusan
		];

		$jsonData = json_encode($newData);
        $eform = EForm::where('id',$eformId)->update([
            'kk_details'=>$jsonData
        ]);

		$eformId = $req['eform_id'];
		$updateEform = EForm::where('id',$eformId)->update([
			'is_approved'=>true
		]);

		

		$body = $res->getBody();
    	$obj = json_decode($body);

    	return response()->json([
    		'responseCode'=>'00',
    		'responseMessage'=>'Success',
    		'contents'=>$obj
    	]);


	}

    public function listReject(){
    	$header = ['access_token'=> $this->tokenLos];
    	$client = new Client();
			 try{
                $res = $client->request('POST',$this->hostLos.'/api/listreject', 
                	['headers' =>  $header
                    ]);
            }catch (RequestException $e){
                return response()->json([
                    'responseCode'=>'99',
                    'responseMessage'=> $e->getMessage()
                ]);
            }

            $body = $res->getBody();
			$obj = json_decode($body);
            $con = $obj->responseData;
            // $con = $resp[0];
            // $con  = $con['RJ_CODE'];
            \Log::info('CON =');
            \Log::info($con[0]);

			if ($obj->responseCode == 0){
				$data = $obj->responseData;
				return response()->json([
					'responseCode'=>'00',
					'responseMessage'=>'Success',
					'contents' => $data
				]);
			}
			
    }

  

}

