<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class EmailController extends BaseController
{
    public function index()
    {
        $email = \Config\Services::email();

        $email->setTo('yoriadiatma@gmail.com'); // Ganti dengan email penerima
        $email->setSubject('Tes Email dari CodeIgniter 4');
        $email->setMessage('<h1>Halo!</h1><p>Ini adalah pesan uji coba.</p>');

        if ($email->send()) {
            echo 'Email berhasil dikirim.';
        } else {
            echo 'Gagal mengirim email.';
            echo $email->printDebugger(['headers']); // Debugging
        }
    }
}
