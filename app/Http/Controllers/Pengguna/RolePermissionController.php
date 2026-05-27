<?php

namespace App\Http\Controllers\Pengguna;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    /**
     * Display the roles & permissions management page.
     */
    public function index()
    {
        return view('pages.pengguna.role-permission.index');
    }
}
