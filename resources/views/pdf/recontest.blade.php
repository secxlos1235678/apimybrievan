<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>E-Recontest PDF</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <style type="text/css" media="all">
            html {
                margin: 0px;
            }
            body {
                background: white;
                margin: 0px;
            }
            @page {
                margin: 0px;
            }
            page[size="A4"] {
              background: white;
              width: 21cm;
              height: 29.7cm;
              display: block;
              margin: 0 auto;
              margin-bottom: 0.5cm;
              box-shadow: 0 0 0.2cm rgba(0,0,0,0.5);
            }
            @media print {
              body, page[size="A4"] {
                margin: 0px;
                box-shadow: 0;
              }
            }
            body {
                font-size: 14px;
            }
            body, h1, h2, h3, h4, h5, h6 {
                font-family: "Open Sans",sans-serif;
            }
            h1, h2, h3, h4, h5, h6 {
                font-weight: 600;
                margin-top: 0px;
            }
            .h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6 {
                line-height: 1.1;
                color: inherit;
            }
            h1 small, h2 small, h3 small, h4 small, h5 small, h6 small {
                font-size: 10px;
                letter-spacing: 0;
                font-weight: 300;
                font-style: italic;
            }
            p {
                margin: 0px;
            }
            .page-content {
                padding-top: 0.5cm;
                padding-left: 1cm;
                padding-right: 1cm;
                padding-bottom: 1cm;
            }
            td {
                padding: 5px 5px 5px 0px;
            }
            td.label {
                width: 170px;
            }
            .full-width {
                width: 100%;
            }
            .half-width {
                width: 50%;
                float: left;
            }
            .logo-mybri>img {
                width: 130px;
                height: auto;
            }
            .logo-bri {
                text-align: right;
            }
            .logo-bri>img {
                width: 80px;
                height: auto;
            }
            .no-ref {
                display: block;
                border: solid 1px #F7941E;
                text-align: center;
                padding: 10px 10px;
                margin: 20px 200px 10px 200px;
            }
            .title {
                background-color: #eee;
                font-weight: bold;
                text-align: center;
                text-transform: uppercase;
            }
            .title {
                background-color: #eee;
                font-weight: bold;
                text-align: center;
                text-transform: uppercase;
                display: block;
                width: auto;
                margin-top: 20px;
                margin-bottom: 5px;
            }
            .term {
                display: block;
                border: solid 1px #cecece;
                padding: 0px 10px;
                margin-top: 20px;
                page-break-before: always;
            }
            .term-02 {
                display: block;
                border: solid 1px #cecece;
                padding: 0px 10px;
                margin-top: 20px;
            }
            .term>ol {
                padding-left: 30px;
            }
            .barcode {
                text-align: center;
                margin-top: 15px;
            }
            .barcode>img {
                height: 120px;
                width: auto;
            }
            .color-orange {
                color: #f7941e;
                margin-bottom: 10px;
                display: inline-block;
            }
            .color-blue {
                color: #00529C;
                margin-bottom: 10px;
                display: inline-block;
            }
            .position-bottom {
                position: absolute;
                bottom: 0;
            }
            .clear {
                clear: both;
            }
            .break-word {
                max-width: 300px;
                word-wrap: break-word;
            }
            .underline {
                width: 200px;
                margin: 0 auto;
                border-bottom: solid 1px black;
            }
        </style>
    </head>
    <body>
        <div class="page-content">

            <table class="full-width">
                <tbody>
                    <tr>
                        <!-- Gambar logo cuma dummy, pake external link -->
                        <td class="logo-mybri full-width">
                            <div class="color-orange">e-Recontest</div>
                            <div class="color-blue">BRI</div>
                            <br/>
                            <img src="{{ asset('img/logo-mybri.png') }}">
                        </td>
                        <td class="logo-bri full-width">
                            <img src="{{ asset('img/logo-bri.png') }}">
                        </td>
                    </tr>
                </tbody>
            </table>

            <hr>

            <table class="full-width">
                <tbody>
                    <tr>
                        <td class="no-ref full-width">No. Reff Aplikasi : {{ $detail->ref_number }}</td>
                    </tr>
                </tbody>
            </table>

            <table class="full-width">
                <tbody>
                    <tr>
                        <td class="title" colspan="2">Data Mutasi Rekening</td>
                    </tr>
                </tbody>
            </table>

            @if( isset($detail->recontest['mutations']) )
                @foreach( $detail->recontest['mutations'] as $mutation )
                    <table>
                        <tbody>
                            <tr>
                                <td class="label">Nama Bank</td>
                                <td class="break-word">: {{ $mutation['bank'] }}</td>
                            </tr>
                            <tr>
                                <td class="label">No. Rekening</td>
                                <td class="break-word">: {{ $mutation['number'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="full-width">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Nominal</th>
                                <th>Jenis Transaksi</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $mutation['tables'] as $bank )
                                <tr>
                                    <td>{{ $bank['date'] }}</td>
                                    <td>Rp. {{ $bank['amount'] > 0 ? number_format(round($bank['amount']), 0, ",", ".") : '' }}</td>
                                    <td class="break-word">{{ $bank['type'] }}</td>
                                    <td class="break-word">{{ $bank['note'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endforeach
            @endif

            <table class="full-width">
                <tbody>
                    <tr>
                        <td class="title" colspan="2">Analisa RM</td>
                    </tr>
                </tbody>
            </table>

            <table>
                <tbody>
                    <tr>
                        <td class="label">Pros (Hal yang mendukung analisa)</td>
                        <td class="break-word">: {{ $detail->recontest['pros'] }}</td>
                    </tr>
                    <tr>
                        <td class="label">Con (Hal yang tidak mendukung analisa)</td>
                        <td class="break-word">: {{ $detail->recontest['cons'] }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="term">
                <br/>Dengan ini saya meyakini kebenaran data nasabah dan merekomendasikan permohonan kredit untuk dapat diproses lebih lanjut :<br/>

                <div class="term-02">
                    <table class="full-width">
                        <tbody>
                            <tr>
                                <td class="title" colspan="2">RM</td>
                            </tr>
                        </tbody>
                    </table>

                    <table>
                        <tbody>
                            <tr>
                                <td class="label">Rekomendasi</td>
                                <td class="break-word">: {{ $detail->recontest['ao_recommended'] == 'yes' ? 'Ya' : 'Tidak' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Catatan</td>
                                <td class="break-word">: {{ $detail->recontest['ao_recommendation'] }}</td>
                            </tr>
                            <tr>
                                <td class="label">Tanggal</td>
                                <td class="break-word">: {{ date('d M Y', strtotime($detail->created_at)) }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="full-width">
                        <div class="barcode">
                            <img src="{{ asset('img/qr-code.png') }}">
                            <p class="underline">{{ $detail->ao_name ? $detail->ao_name : '-' }}</p>
                            <p>{{ $detail->ao_position ? $detail->ao_position : '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="term-02">
                    <table class="full-width">
                        <tbody>
                            <tr>
                                <td class="title" colspan="2">Pinca</td>
                            </tr>
                        </tbody>
                    </table>

                    <table>
                        <tbody>
                            <tr>
                                <td class="label">Rekomendasi</td>
                                <td class="break-word">: {{ $detail->recontest['pinca_recommended'] ? 'Ya' : 'Tidak' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Catatan</td>
                                <td class="break-word">: {{ $detail->recontest['pinca_recommendation'] }}</td>
                            </tr>
                            <tr>
                                <td class="label">Tanggal</td>
                                <td class="break-word">: {{ date('d M Y', strtotime($detail->created_at)) }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="full-width">
                        <div class="barcode">
                            <img src="{{ asset('img/qr-code.png') }}">
                            <p class="underline">{{ $detail->pinca_name ? $detail->pinca_name : '-' }}</p>
                            <p>{{ $detail->pinca_position ? $detail->pinca_position : '-' }}</p>
                        </div>
                    </div>

                </div>
                <br/>
            </div>
        </div>

    </body>

</html>