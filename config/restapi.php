<?php

return [
	
	/*
    |--------------------------------------------------------------------------
    | CLIENT_ASMX return xml
    |--------------------------------------------------------------------------
    */

    'asmx'      => env('CLIENT_ASMX', 'http://10.35.65.167:6969/service.asmx/'),

	/*
    |--------------------------------------------------------------------------
    | CLIENT_RESTWSHC return json
    |--------------------------------------------------------------------------
    */

	'restwshc'  => env('CLIENT_RESTWSHC', 'http://10.35.65.111/skpp_concept/restws_hc'),

  	'key'  		=> env('CLIENT_KEY', '$2y$10$OoDAS6saH1b3D/nZJ4DXKuOTqVumFTACUZDFkZfepS1h15jDNxdzK'),
];