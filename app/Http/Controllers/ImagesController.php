<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Image;

class ImagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($file)
    {
       // dd(substr($file, -3));
        $cekpdf = substr($file, -3);
        if($cekpdf == 'pdf'){
            return response()->error([
                'message' => "you can't access this site !",
            ]);
        }else{
        $storagePath = public_path('uploads/'.$file);
        //dd($storagePath);
        return Image::make($storagePath)->response();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show2($folder, $file)
    {
       // dd(substr($file, -3));
        $cekpdf = substr($file, -3);
        if($cekpdf == 'pdf'){
            return response()->error([
                'message' => "you can't access this site !",
            ]);
        }else{
        $storagePath = public_path('uploads/'.$folder.'/'.$file);
        //dd($storagePath);
        return Image::make($storagePath)->response();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show3(Request $request, $folder, $file)
    {
       // dd(substr($file, -3));
        // $cekpdf = substr($file, -3);
        // if($cekpdf == 'pdf'){
        //     return response()->error([
        //         'message' => "you can't access this site !",
        //     ]);
        // }else{
        $header = $request->ip();
        $server = $request->server();
        // $ip = env('ACCESS_CLAS_IP', '127.0.0.1');
        // \Log::info("====client : ".$header);
        // \Log::info("====ENV-IP : ".$ip);
        // \Log::info("====================SERVER==================");
        // \Log::info($server);

        // \Log::info("====host : ".$request->getSchemeAndHttpHost());
        //if($header == $ip ){
        $storagePath = public_path('uploads/'.$folder.'/'.$file);
        //dd($storagePath);
        return Image::make($storagePath)->response();
        //}else{
            // return response()->error([
            //     'message' => "you can't access this site !",
          //  ]);
        // }
       // }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
