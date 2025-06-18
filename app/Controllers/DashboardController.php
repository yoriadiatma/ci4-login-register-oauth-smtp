<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login')->with('error', 'Silakan login terlebih dahulu');
        }
        
        $data = [
            'username' => session()->get('username'),
        ];

        return view('dashboard', $data);
    }
}
