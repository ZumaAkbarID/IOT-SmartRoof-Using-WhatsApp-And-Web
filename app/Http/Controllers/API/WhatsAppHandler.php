<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WhatsAppHandler extends Controller
{
    public function data(Request $request)
    {
        // $data = $request->all();
        // $formattedData = json_encode($data, JSON_PRETTY_PRINT);

        // $fileName = 'esp.txt';
        // $filePath = storage_path($fileName);

        // file_put_contents($filePath, $formattedData . PHP_EOL, FILE_APPEND);

        // return response()->json(['message' => 'Data berhasil disimpan di dalam file.']);

        $data = $request->json()->all();
        // return response()->json(['status_sistem' => $request->status_sistem, 'status_hujan' => $request->status_hujan]);

        // $formattedData = json_encode($data, JSON_PRETTY_PRINT);

        $msg = explode("/", $data['receivedMessage'])[1];
        $endpoin = 'http://192.168.137.36/';
        
        if(strtolower($msg) == "manual") {
            $endpoin .= '?mode=Manual';
        } else if (strtolower($msg) == "otomatis") {
            $endpoin .= '?mode=Otomatis';
        } else if(strtolower($msg) == "terbuka") {
            $endpoin .= '?roof=Terbuka';
        } else if(strtolower($msg) == "tertutup") {
            $endpoin .= '?roof=Tertutup';
        }

        // Lakukan HTTP GET request
        $response = Http::get($endpoin);

        // Ambil data dari response
        $data = $response->json();

        return response()->json([
            'status' => 'success',
        ]);
    }
}
