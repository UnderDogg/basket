<?php

namespace App\Http\Controllers;

use App\Http\Requests;

class LandingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('welcome');
    }
}
