<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use Illuminate\Support\Facades\DB;

class ShortenerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index()
    {
        return 'OK';
    }
}
