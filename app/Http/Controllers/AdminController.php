<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Show the admin overview page.
     */
    public function overview()
    {
        // Return the admin overview view
        return view('admin.overview');
    }

    /**
     * Show the manage users page.
     */
    public function users()
    {
        // Return the manage users view
        return view('admin.users');
    }

    /**
     * Show the admin settings page.
     */
    public function settings()
    {
        // Return the settings view
        return view('admin.settings');
    }
}
