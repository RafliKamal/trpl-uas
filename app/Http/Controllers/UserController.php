<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {

        $data = User::orderBy('id', 'asc')->get();
        
        return response()->json([
                'status' => 'true',
                'message' => 'Data Ditemukan',
                'data' => $data
            ], 200);


        // Admin => semua user
        // if ($user->roleName === 'admin') {
            // return response()->json([
            //     'status' => 'true',
            //     'message' => 'Data Ditemukan',
            //     'data' => $data]);

        // }

        // // Dosen => semua dosen & mahasiswa
        // if ($user->roleName === 'dosen') {
        //     return response()->json(User::whereIn('roleName', ['dosen', 'mahasiswa'])->get());
        // }

        // // Mahasiswa => hanya dirinya sendiri
        // if ($user->roleName === 'mahasiswa') {
        //     return response()->json($user);
        // }

        // return response()->json(['message' => 'Tidak dikenali'], 403);
    }

    public function store(Request $request)
    {
        

        $ruless = [
            'userId' => 'required|unique:users,userId',
            'password' => 'required',
            'roleName' => 'required|in:mahasiswa,dosen,admin',
            'nama' => 'required',
            'email' => 'required|email',
        ];
        
        if ($request->roleName === 'mahasiswa') {
            $ruless['thnAngkatan'] = 'required|string';
        } elseif ($request->roleName === 'dosen') {
            $ruless['status'] = 'required|string';
        } elseif ($request->roleName === 'admin') {
            $ruless['divisi'] = 'required|string';
        }

        $messages = [
            'userId.required' => 'User ID harus diisi',
            'userId.unique' => 'User ID sudah terdaftar',
            'password.required' => 'Password harus diisi',
            'roleName.required' => 'Role harus dipilih',
            'nama.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
        ];

        $validator = Validator::make($request->all(), $ruless, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Proses Validasi gagal',
                'errors' => $validator->errors()
            ], 401);
        }

        User::create([
            'userId' => $request->userId,
            'password' => Hash::make($request->password),
            'roleName' => $request->roleName,
            'statusLogin' => 'offline'
        ]);

        // Tambahkan ke tabel role terkait
        if ($request->roleName === 'mahasiswa') {
            Mahasiswa::create([
                'userId' => $request->userId,
                'nim' => $request->userId,
                'nama' => $request->nama,
                'email' => $request->email,
                'thnAngkatan' => $request->thnAngkatan,
                'status' => 'aktif'
            ]);
        }
        if ($request->roleName === 'dosen') {
            Dosen::create([
                'userId' => $request->userId,
                'nidn' => $request->userId,
                'nama' => $request->nama,
                'email' => $request->email,
                'status' => 'aktif'
            ]);
        }
        if ($request->roleName === 'admin') {
            Admin::create([
                'userId' => $request->userId,
                'nama' => $request->nama,
                'email' => $request->email,
                'divisi' => $request->divisi,
            ]);
        }
        
        // $client = new Client();
        // $url = "http://localhost:8001/api/users";
        // $response = $client->request('POST', $url, [
        //     'headers' => ['Authorization' => 'Bearer ' . session('token')],
        //     'json' => [
        //         'userId' => $request->userId,
        //         'password' => $request->password,
        //         'roleName' => $request->roleName,
        //         'nama' => $request->nama,
        //         'email' => $request->email,
        //         'thnAngkatan' => $request->thnAngkatan,
        //     ]
        // ]);

        return response()->json([
            'status' =>true,
            'message'=>'User berhasil ditambahkan'
        ], 200);

        // return response()->json(['message' => 'User berhasil ditambahkan'], 200);
    }

    public function getMahasiswas()
    {
        return response()->json(Mahasiswa::all());
    }

    public function getDosens()
    {
        return response()->json(Dosen::all());
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        // Update profil berdasarkan role
        if ($user->roleName === 'mahasiswa') {
            $mahasiswa = Mahasiswa::where('userId', $user->userId)->first();
            $mahasiswa->update($request->only(['nama', 'email', 'thnAngkatan', 'status']));
            return response()->json(['message' => 'Profil mahasiswa diperbarui']);
        }

        if ($user->roleName === 'dosen') {
            $dosen = Dosen::where('userId', $user->userId)->first();
            $dosen->update($request->only(['nama', 'email', 'status']));
            return response()->json(['message' => 'Profil dosen diperbarui']);
        }

        if ($user->roleName === 'admin') {
            $admin = Admin::where('userId', $user->userId)->first();
            $admin->update($request->only(['nama', 'email', 'divisi']));
            return response()->json(['message' => 'Profil admin diperbarui']);
        }

        return response()->json(['message' => 'Role tidak dikenali'], 400);
    }

    public function searchUser(Request $request)
    {
        $keyword = $request->input('keyword');

        $results = [];

        // Cari di mahasiswa
        $mhs = Mahasiswa::where('nim', 'like', "%$keyword%")
            ->orWhere('nama', 'like', "%$keyword%")
            ->get();
        if ($mhs->count())
            $results['mahasiswa'] = $mhs;

        // Cari di dosen
        $dsn = Dosen::where('nidn', 'like', "%$keyword%")
            ->orWhere('nama', 'like', "%$keyword%")
            ->get();
        if ($dsn->count())
            $results['dosen'] = $dsn;

        return response()->json($results ?: ['message' => 'Tidak ditemukan']);
    }


}
