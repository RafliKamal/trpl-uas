<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class CRUDUserController extends Controller
{
    public function index()
    {
        $users = User::all()->map(function ($user) {
            $userData = [
                'id' => $user->id,
                'userId' => $user->userId,
                'roleName' => $user->roleName,
                'statusLogin' => $user->statusLogin,
                'nama' => null,
                'email' => null,
                'divisiOrStatus' => null,
            ];

            if ($user->roleName === 'admin' && $user->admin) {
                $userData['nama'] = $user->admin->nama;
                $userData['email'] = $user->admin->email;
                $userData['divisiOrStatus'] = $user->admin->divisi;
            } elseif ($user->roleName === 'dosen' && $user->dosen) {
                $userData['nama'] = $user->dosen->nama;
                $userData['email'] = $user->dosen->email;
                $userData['divisiOrStatus'] = $user->dosen->status;
            } elseif ($user->roleName === 'mahasiswa' && $user->mahasiswa) {
                $userData['nama'] = $user->mahasiswa->nama;
                $userData['email'] = $user->mahasiswa->email;
                $userData['divisiOrStatus'] = $user->mahasiswa->thnAngkatan;
                $userData['statusMahasiswa'] = $user->mahasiswa->status; // ← Tambahan
            }

            return $userData;
        });

        return view('user', compact('users'));
    }

    public function create()
    {
        return view('user');
    }

    public function store(Request $request)
    {
        // Validasi dasar
        $request->validate([
            'userId' => 'required',
            'password' => 'required',
            'roleName' => 'required|in:admin,dosen,mahasiswa',
            'nama' => 'required',
            'email' => 'required|email',
            'divisiOrStatus' => 'required'
        ]);

        // Persiapkan data berdasarkan role
        $parameter = [
            'userId' => $request->userId,
            'password' => $request->password,
            'roleName' => $request->roleName,
            'nama' => $request->nama,
            'email' => $request->email,
        ];

        // Sesuaikan field sesuai backend controller
        if ($request->roleName === 'mahasiswa') {
            $parameter['thnAngkatan'] = $request->divisiOrStatus;
        } elseif ($request->roleName === 'dosen') {
            $parameter['status'] = $request->divisiOrStatus;
        } elseif ($request->roleName === 'admin') {
            $parameter['divisi'] = $request->divisiOrStatus;
        }

        // Kirim ke endpoint register
        $client = new Client();
        $url = "https://kamal.ricakagus.id/api/users"; // Endpoint ini harus mengarah ke AuthController@register

        try {
            $response = $client->request('POST', $url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . session('token'), // Jika tidak pakai middleware, bisa kosongkan
                ],
                'body' => json_encode($parameter)
            ]);

            $contentArray = json_decode($response->getBody()->getContents(), true);

            if ($contentArray['status'] == true) {
                return redirect()->to('users')->with('success', 'User berhasil ditambahkan');
            } else {
                return redirect()->to('users')->with('error', 'Gagal menambahkan user: ' . ($contentArray['message'] ?? 'Unknown error'));
            }
        } catch (\Exception $e) {
            return redirect()->to('users')->with('error', 'Gagal menambahkan user: ' . $e->getMessage());
        }
    }



    public function show(string $id)
    {
        // Optional jika diperlukan
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('user', compact('user'));
    }


    public function update(Request $request, string $id)
    {
        $client = new Client();
        $url = "https://kamal.ricakagus.id/api/users/$id";

        $parameter = [
            'userId' => $request->userId,
            'nama' => $request->nama,
            'email' => $request->email,
            'roleName' => $request->roleName,
        ];

        if ($request->roleName === 'mahasiswa') {
            $parameter['thnAngkatan'] = $request->divisiOrStatus;
            $parameter['status'] = $request->status;
        } elseif ($request->roleName === 'dosen') {
            $parameter['status'] = $request->divisiOrStatus;
        } elseif ($request->roleName === 'admin') {
            $parameter['divisi'] = $request->divisiOrStatus;
        }


        try {
            $response = $client->put($url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . session('token')
                ],
                'body' => json_encode($parameter)
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            return redirect()->to('users')->with('success', $result['message'] ?? 'User berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->to('users')->with('error', 'Gagal memperbarui user: ' . $e->getMessage());
        }

    }

    public function destroy(string $id)
    {
        $client = new Client();
        $url = "https://kamal.ricakagus.id/api/users" . '/' . $id;

        $response = $client->delete($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . session('token')
            ]
        ]);

        $result = json_decode($response->getBody()->getContents(), true);

        if (isset($result['message'])) {
            return redirect()->to('users')->with('success', $result['message']);
        }

        return redirect()->to('users')->with('error', 'Gagal menghapus data');
    }
}