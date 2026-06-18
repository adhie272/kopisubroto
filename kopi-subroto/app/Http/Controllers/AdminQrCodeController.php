<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminQrCodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $appUrl = config('app.url');

        return view('admin.qrcode', compact('appUrl'));
    }
}
