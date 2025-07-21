<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class CRUDUserController extends Controller
{



    public function index()
    {
        if (!session('token')) {
            return redirect('/login');
        }
        $client = new Client();
        $url = "https://kamal.ricakagus.id/api/users";

        //  $response = $client->request('GET', 'https://kamal.ricakagus.id/api/users');
        $response = $client->request('GET', $url, [
            'headers' => [
                'Authorization' => 'Bearer ' . session('token'),
                'Accept' => 'application/json'
            ]
        ]);

        $result = json_decode($response->getBody()->getContents(), true);

        // fix: ambil ['data'] langsung
        $users = $result['data'] ?? []; // agar di blade bisa langsung foreach ($users as $data)


        return view('user', compact('users'));
    }

    public function create()
    {
        return view('user');
    }

    public function store(Request $request)
    {

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


        $client = new Client();
        $url = "https://kamal.ricakagus.id/api/users";

        try {
            $response = $client->request('POST', $url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . session('token')
                ],
                'body' => json_encode($parameter)
            ]);

            $content = $response->getBody()->getContents(); // Get the response body
            $contentArray = json_decode($content, true); // Decode the JSON response into an array


            if ($contentArray['status'] == true) {
                return redirect()->to('users')->with('success', 'User berhasil ditambahkan');
            } else {
                return redirect()->to('users')->with('error', 'Gagal menambahkan user: ' . $contentArray['message'] ?? 'Unknown error');
            }

        } catch (\Exception $e) {
            return redirect()->to('users')->with('error', 'Gagal menambahkan user: ' . $e->getMessage());
        }
        //     $result = json_decode($response->getBody()->getContents(), true);
        //     return redirect()->to('users')->with('success', $result['message'] ?? 'User berhasil ditambahkan');
        // } catch (\Exception $e) {
        //     return redirect()->to('users')->with('error', 'Gagal menambahkan user: ' . $e->getMessage());
        // }
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