<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller;

/**
 * Class CanvasController
 * @package App\Http\Controllers
 */
class CanvasController extends Controller
{
    /**
     * CanvasController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return mixed
     */
    public function index()
    {
        $user = app('auth')->user();
        return var_export($user, true);
    }
}