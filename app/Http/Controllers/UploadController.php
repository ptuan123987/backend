<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{
    //
    public function upload(Request $request) {
        $path = $request->file('file')->store('public/image');
        return response()->json([
            'path' => $path,
            'msg' => 'success'
        ]);
    }
}
