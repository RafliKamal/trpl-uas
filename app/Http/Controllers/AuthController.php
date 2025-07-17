<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
       
        $rules = [
            'userId' => 'required|unique:users,userId',
            'password' => 'required',
            'roleName' => 'required|in:mahasiswa,dosen,admin'
        ];

        $validator = \Validator::make($request->all(), $rules);
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
        

        return response()->json([
            'status' => true,
            'message' => 'Registrasi berhasil'
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'userId' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('userId', $request->userId)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Login gagal'], 401);
        }

        $user->update(['statusLogin' => 'online']);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:6|different:old_password'
        ]);

        $user = auth()->user();

        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['message' => 'Password lama salah'], 403);
        }

        $user->update(['password' => Hash::make($request->new_password)]);
        return response()->json(['message' => 'Password berhasil diganti']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'userId' => 'required',
            'new_password' => 'required|min:6'
        ]);

        $user = User::where('userId', $request->userId)->first();

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        $user->update(['password' => Hash::make($request->new_password)]);
        return response()->json(['message' => 'Password user berhasil di-reset']);
    }



    public function updateRole(Request $request)
    {
        $request->validate([
            'userId' => 'required',
            'roleName' => 'required|in:mahasiswa,dosen,admin'
        ]);

        $user = User::where('userId', $request->userId)->firstOrFail();
        $user->update(['roleName' => $request->roleName]);

        return response()->json(['message' => 'Role berhasil diperbarui']);
    }

    public function logout(Request $request)
    {
        // Hapus token yang digunakan saat ini
        $user = $request->user();

        if ($user) {
            $user->currentAccessToken()->delete(); // hapus token saat ini
            $user->update(['statusLogin' => 'offline']);
        }

        return response()->json(['message' => 'Berhasil logout']);
    }
}
