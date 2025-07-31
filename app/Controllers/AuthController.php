<?php

namespace App\Controllers;

use Google\Client;
use App\Models\UserModel;
use Google\Service\Oauth2;
use CodeIgniter\Controller;

class AuthController extends Controller
{
    public function register()
    {
        if ($this->request->getMethod() == 'POST') {
            $username = $this->request->getPost('username');
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            $confirm = $this->request->getPost('confirm');

            if ($password !== $confirm) {
                return redirect()->back()->with('error', 'Konfirmasi password tidak sesuai');
            }
            $model = new UserModel();

            $token = bin2hex(random_bytes(32)); // Token acak
            $expires = date('Y-m-d H:i:s', strtotime("+1 day")); // Token berlaku 1 hari

            $model->insert([
                'username' => $username,
                'email' => $email,
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                'verified' => false,
                'verify_token' => $token,
                'verify_expires' => $expires,
            ]);

            $verifyLink = base_url("/auth/verify/$token");

            $emailService = \Config\Services::email();
            $emailService->setTo($email);
            $emailService->setSubject('Verifikasi Email Anda');
            $emailService->setMessage("Silakan klik link berikut untuk verifikasi akun Anda: <a href='{$verifyLink}'>{$verifyLink}</a>");

            $emailService->send();

            return redirect()->to('/auth/login')->with('success', 'Silakan cek email Anda untuk verifikasi');
        }

        return view('auth/register'); // tampil view register
    }

    public function login()
    {
        if ($this->request->getMethod() == 'POST') {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            $model = new UserModel();

            $user = $model->where('email', $email)->first();

            if ($user && password_verify($password, $user['password_hash'])) {

                if ($user && password_verify($password, $user['password_hash'])) {

                    if (!$user['verified']) {
                        return redirect()->back()->with('error', 'Akun Anda belum diverifikasi. Silakan cek email Anda.');
                    } else {
                        // Set sesi
                        session()->set([
                            'isLoggedIn' => true,
                            'userId' => $user['id'],
                            'username' => $user['username'],
                            'email' => $user['email'],
                        ]);

                        return redirect()->to('/dashboard'); // Setelah login, nantinya bisa diberika view Dashboard
                    }
                }
            } else {
                return redirect()->back()->with('error', 'Username atau password salah');
            }
        }

        return view('auth/login'); // tampil view login
    }


    public function logout()
    {
        session()->destroy();

        return redirect()->to('/auth/login')->with('success', 'Berhasil logout');
    }

    public function verify($token = null)
    {
        if ($token == null) {
            return redirect()->to('/auth/login')->with('error', 'Token tidak valid');
        }

        $model = new UserModel();

        $user = $model->where('verify_token', $token)->first();

        if ($user && $user['verify_expires'] >= date('Y-m-d H:i:s')) {
            $model->update($user['id'], [
                'verified' => true,
                'verify_token' => null,
                'verify_expires' => null,
            ]);

            return redirect()->to('/auth/login')->with('success', 'Akun Anda berhasil diverifikasi. Silakan login!');
        } else {
            return redirect()->to('/auth/login')->with('error', 'Token tidak valid atau kadaluarsa');
        }
    }

    public function forgot()
    {
        if ($this->request->getMethod() == 'POST') {
            $email = $this->request->getPost('email');
            $model = new UserModel();

            $user = $model->where('email', $email)->first();

            if ($user) {
                $token = bin2hex(random_bytes(32)); // Token acak
                $expires = date('Y-m-d H:i:s', strtotime("+15 minutes"));

                $model->update($user['id'], ['reset_hash' => $token, 'reset_expires' => $expires]);

                $resetLink = base_url("/auth/reset/$token");

                $emailService = \Config\Services::email();
                $emailService->setTo($email);
                $emailService->setSubject('Reset Password Anda');
                $emailService->setMessage("Silakan klik link berikut untuk reset password: <a href='{$resetLink}'>{$resetLink}</a>");
                $emailService->send();

                return redirect()->back()->with('success', 'Silakan cek email Anda.');
            } else {
                return redirect()->back()->with('error', 'Email tidak ditemukan');
            }
        }
        return view('forgot'); // view form untuk meminta email
    }

    public function reset($token = null)
    {
        $model = new UserModel();

        $user = $model->where('reset_hash', $token)->first();

        if ($user && $user['reset_expires'] >= date('Y-m-d H:i:s')) {

            if ($this->request->getMethod() == 'POST') {
                $newPass = $this->request->getPost('new_pass');
                $confPass = $this->request->getPost('conf_pass');

                if ($newPass === $confPass) {

                    $model->update($user['id'], [
                        'password_hash' => password_hash($newPass, PASSWORD_DEFAULT),
                        'reset_hash' => null,
                        'reset_expires' => null,
                    ]);

                    return redirect()->to('/auth/login')->with('success', 'Silakan login dengan password yang baru.');
                } else {
                    return redirect()->back()->with('error', 'Konfirmasi password tidak sesuai');
                }
            }
            return view('reset', ['token' => $token]);
        } else {
            return redirect()->to('/auth/forgot')->with('error', 'Token tidak valid atau kadaluarsa');
        }
    }


    public function google()
    {
        $client = new Client([
            'client_id' => env('GOOGLE_CLIENT_ID'),
            'client_secret' => env('GOOGLE_CLIENT_SECRET'),
            'redirect_uri' => env('GOOGLE_REDIRECT_URI'),
        ]);

        $client->addScope('email');
        $client->addScope('profile');

        $authURL = $client->createAuthUrl();

        return redirect()->to($authURL);
    }

    public function googleCallback()
    {
        $code = $this->request->getGet('code');

        if ($code) {
            $client = new Client([
                'client_id' => env('GOOGLE_CLIENT_ID'),
                'client_secret' => env('GOOGLE_CLIENT_SECRET'),
                'redirect_uri' => env('GOOGLE_REDIRECT_URI'),
            ]);

            $client->addScope('email');
            $client->addScope('profile');
            $token = $client->fetchAccessTokenWithAuthCode($code);
            $client->setAccessToken($token);

            $googleService = new Oauth2($client);
            $googleUser = $googleService->userinfo->get();

            // Setelah dapat info dari Google
            $email = $googleUser->email;
            $name = $googleUser->name;

            $model = new UserModel();

            $user = $model->where('email', $email)->first();

            if ($user) {
                // Update jika perlu
                $model->update($user['id'], ['username' => $name]);
            } else {
                // Buat akun
                $model->insert([
                    'username' => $name,
                    'email' => $email,
                    'password_hash' => password_hash(bin2hex(random_bytes(10)), PASSWORD_DEFAULT),
                    'verified' => true // Langsung verified
                ]);

                $user = $model->where('email', $email)->first();
            }

            // Setelah dapat/user tersedia, set sesi dan redirect
            session()->set([
                'isLoggedIn' => true,
                'userId'       => $user['id'],
                'username'   => $user['username'],
                'email'      => $user['email'],
            ]);

            return redirect()->to('/dashboard');
        } else {
            return redirect()->to('/auth/login')->with('error', 'Login Google Gagal');
        }
    }
}
