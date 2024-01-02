<?php

namespace App\Http\Controllers\API;

use App\Events\EspEvent;
use App\Http\Controllers\Controller;
use App\Models\RainLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EspHandler extends Controller
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

        $validator = Validator::make($data, [
            'status_sistem' => 'required',
            'status_hujan' => 'required',
            'intensity' => 'required',
            'status_roof' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'false',
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $this->saveData($data);

        return response()->json([
            'status' => 'success',
        ]);
    }

    private function saveData($data)
    {
        $carbon = new Carbon();
        $new = new RainLog();
        $new->status_sistem = $data['status_sistem'];
        $new->status_hujan = $data['status_hujan'];
        $new->intensity = $data['intensity'];
        $new->status_roof = $data['status_roof'];
        $new->datetime = $carbon->nowWithSameTz();
        if ($new->save()) {
            event(new EspEvent($data['status_sistem'], $data['status_hujan'], $data['intensity'], $data['status_roof']));
        }
    }
}
