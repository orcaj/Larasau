<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

class UploaderController extends Controller
{
    public function open(){
        return view('admin.uploader.upload');
    }
}
