<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function upload (Request $request) {
        $uploades_files = [];

        foreach ($request->file() as $key => $file) {
            $file = $request->file($key)->store($key);
            $tmp = [];
            $tmp['url'] = url('uploads/' . $file);
            $tmp['size'] = $request->file($key)->getSize();
            $tmp['type'] = $ext = pathinfo(storage_path() . $file, PATHINFO_EXTENSION);

            $uploades_files[] = (object)$tmp;
        }

        echo json_encode($uploades_files);
    }

    public function reloadSystem () {}

    public function updateSystem () {}
}
