<?php

namespace App\Http\Controllers;

use App\Models\Test;
use Illuminate\Http\Request;

class JunController extends Controller
{
    public function testAdd()
    {
        $result = Test::firstOrCreate(['name' => 'PS VR2', 'price' => 20000, 'itemNo' => 'C002']);

        return response()->json(['status' => 1, 'msg' => $result]);
    }

    public function testGet()
    {
//        if (Test::where('itemNo', 'C002')->exists()) {
//
//        }

        $data = Test::where('line.itemNo', 'A001')->first();

        return response()->json($data);
    }
}
