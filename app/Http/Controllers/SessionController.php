<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class SessionController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'userId' => 'required',
            'password' => 'required',
        ]);

        $response = Http::withHeaders([
            'Accept' => 'application/json'
        ])->post("https://kamal.ricakagus.id/api/login", [
                    'userId' => $request->userId,
                    'password' => $request->password
                ]);

        if ($response->successful()) {
            $data = $response->json();
            Session::put('token', $data['access_token']);

            return redirect('/users'); // ✅ Redirect ke /user
        } else {
            return back()->withErrors(['login' => 'Login gagal, periksa kembali informasi akun Anda.']);
        }

    }

    public function register(Request $request)
    {
        $request->validate([
            'userId' => 'required',
            'password' => 'required|min:6',
            'roleName' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            
        ]);

        if ($request->roleName === 'mahasiswa') {
            $request->validate(['thnAngkatan' => 'required']);
        } elseif ($request->roleName === 'dosen') {
            $request->validate(['status' => 'required']);
        } elseif ($request->roleName === 'admin') {
            $request->validate(['divisi' => 'required']);
        }

        $response = Http::post(url('/api/register'), [
            'userId' => $request->userId,
            'password' => $request->password,
            'roleName' => $request->roleName,
            'nama' => $request->name,
            'email' => $request->email,
            'thnAngkatan' => $request->thnAngkatan,
        ]);

        if ($response->successful()) {
            return redirect('/login')->with('success', 'Akun berhasil dibuat. Tunggu verifikasi admin.');
        } else {
            return back()->withErrors($response->json());
        }
    }

    public function logout()
    {
        $token = session('token');

        if ($token) {
            Http::withToken($token)
                ->post('https://kamal.ricakagus.id/api/logout');
        }

        session()->forget('token'); // hapus token dari session
        return redirect('/login')->with('success', 'Anda telah logout.');
    }

}
