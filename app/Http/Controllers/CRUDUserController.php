<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Pagination\LengthAwarePaginator;

class CRUDUserController extends Controller
{
    private function apiClient()
    {
        return new Client([
            'headers' => [
                'Authorization' => 'Bearer ' . session('token'),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ]
        ]);
    }

public function index(Request $request)
{
    $loggedInUser = session('user');

    $client = $this->apiClient();
    $url = "https://kamal.ricakagus.id/api/users";

    try {
        $response = $client->get($url);
        $result = json_decode($response->getBody()->getContents(), true);

        $users = collect();
        $pendingUsers = collect();

        if ($result['status']) {
            $allUsers = collect($result['data'])->map(function ($user) {
                return [
                    'id' => $user['id'],
                    'userId' => $user['userId'],
                    'roleName' => $user['roleName'],
                    'statusLogin' => $user['statusLogin'],
                    'nama' => $user['admin']['nama'] ?? $user['dosen']['nama'] ?? $user['mahasiswa']['nama'] ?? null,
                    'email' => $user['admin']['email'] ?? $user['dosen']['email'] ?? $user['mahasiswa']['email'] ?? null,
                    'divisiOrStatus' => $user['admin']['divisi'] ?? $user['dosen']['status'] ?? $user['mahasiswa']['thnAngkatan'] ?? null,
                    'statusMahasiswa' => $user['mahasiswa']['status'] ?? null,
                ];
            });

            // Tampilkan semua user yang tidak pending
            $users = $allUsers->reject(fn($user) => $user['statusLogin'] === 'pending');

            // Hanya admin yang bisa lihat user pending
            if ($loggedInUser && $loggedInUser['roleName'] === 'admin') {
                $pendingUsers = $allUsers->where('statusLogin', 'pending');
            }
        }
    } catch (\Exception $e) {
        return back()->with('error', 'Gagal mengambil data user: ' . $e->getMessage());
    }

    return view('user', compact('users', 'pendingUsers', 'loggedInUser'));
}


public function verify($id)
{
    $client = $this->apiClient();
    $url = "https://kamal.ricakagus.id/api/users/$id";

    try {
        // Ambil data user lama dulu
        $getResponse = $client->get("https://kamal.ricakagus.id/api/users");
        $allUsers = json_decode($getResponse->getBody()->getContents(), true)['data'];
        $user = collect($allUsers)->firstWhere('id', $id);

        if (!$user) {
            return back()->with('error', 'User tidak ditemukan.');
        }

        $payload = [
            'userId' => $user['userId'],
            'roleName' => $user['roleName'],
            'statusLogin' => 'offline',
            'nama' => $user['admin']['nama'] ?? $user['dosen']['nama'] ?? $user['mahasiswa']['nama'] ?? '',
            'email' => $user['admin']['email'] ?? $user['dosen']['email'] ?? $user['mahasiswa']['email'] ?? '',
        ];

        if ($user['roleName'] === 'admin') {
            $payload['divisi'] = $user['admin']['divisi'] ?? '';
        } elseif ($user['roleName'] === 'dosen') {
            $payload['status'] = $user['dosen']['status'] ?? '';
        } elseif ($user['roleName'] === 'mahasiswa') {
            $payload['thnAngkatan'] = $user['mahasiswa']['thnAngkatan'] ?? '';
            $payload['status'] = $user['mahasiswa']['status'] ?? '';
        }

        $response = $client->put($url, [
            'body' => json_encode($payload)
        ]);

        $result = json_decode($response->getBody()->getContents(), true);

        return redirect()->to('/users')->with('success', $result['message'] ?? 'User berhasil diverifikasi');
    } catch (\Exception $e) {
        return redirect()->to('/users')->with('error', 'Gagal verifikasi user: ' . $e->getMessage());
    }
}



    public function create()
    {
        return view('user');
    }

    public function store(Request $request)
    {
        $request->validate([
            'userId' => 'required',
            'password' => 'required',
            'roleName' => 'required|in:admin,dosen,mahasiswa',
            'nama' => 'required',
            'email' => 'required|email',
            'divisiOrStatus' => 'required'
        ]);

        $parameter = [
            'userId' => $request->userId,
            'password' => $request->password,
            'roleName' => $request->roleName,
            'nama' => $request->nama,
            'email' => $request->email,
        ];

        if ($request->roleName === 'mahasiswa') {
            $parameter['thnAngkatan'] = $request->divisiOrStatus;
        } elseif ($request->roleName === 'dosen') {
            $parameter['status'] = $request->divisiOrStatus;
        } elseif ($request->roleName === 'admin') {
            $parameter['divisi'] = $request->divisiOrStatus;
        }

        $client = $this->apiClient();
        $url = "https://kamal.ricakagus.id/api/users";

        try {
            $response = $client->post($url, [
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
        $client = $this->apiClient();
        $url = "https://kamal.ricakagus.id/api/users/$id";

        if (session('roleName') !== 'admin' && session('userId') !== $request->userId) {
            return redirect()->to('users')->with('error', 'Anda tidak diizinkan mengubah data user lain.');
        }

        $parameter = [
            'userId' => $request->userId,
            'nama' => $request->nama,
            'email' => $request->email,
            'roleName' => $request->roleName,
        ];
        
          // ✅ Tambahkan ini untuk menyertakan password jika diisi
    if (!empty($request->password)) {
        $parameter['password'] = $request->password;
    }

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
        $client = $this->apiClient();
        $url = "https://kamal.ricakagus.id/api/users/$id";

        try {
            $response = $client->delete($url);
            $result = json_decode($response->getBody()->getContents(), true);

            return redirect()->to('users')->with('success', $result['message'] ?? 'User berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->to('users')->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }
}
