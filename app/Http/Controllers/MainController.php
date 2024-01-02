<?php

namespace App\Http\Controllers;

use App\Models\RainLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MainController extends Controller
{
    function index()
    {
        $data = [
            'latest' => RainLog::latest()->first(),
            'all' => RainLog::all(),
        ];

        return view('main', $data);
    }

    function changeMode()
    {
        $latest = RainLog::latest()->first();
        if (!$latest)
            return response()->json(['success' => false, 'message' => 'Tunggu data terupdate'], 404);

        $enpoint = "http://192.168.137.36/";

        if ($latest->status_sistem == "Manual")
            $enpoint .= "?mode=Otomatis";
        else if ($latest->status_sistem == "Otomatis")
            $enpoint .= "?mode=Manual";

        $response = Http::get($enpoint);

        return response()->json(['success' => $response->successful(), 'message' => $response->json()], $response->status());
    }
}
