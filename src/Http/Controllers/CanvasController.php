<?php

namespace App\Http\Controllers;

use App\Http\Middleware\SocialAuthMiddleware;
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
        $this->middleware(SocialAuthMiddleware::class);
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
