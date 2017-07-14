<?php

namespace App\Http\Controllers;

class LandingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @author MS
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('welcome', ['user' => $this->getAuthenticatedUser()]);
    }
}
