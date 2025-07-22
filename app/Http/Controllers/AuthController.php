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
        'roleName' => 'required|in:mahasiswa,dosen,admin',
        'nama' => 'required',
        'email' => 'required|email',
    ];

    // Validasi tambahan berdasarkan role
    if ($request->roleName === 'mahasiswa') {
        $rules['thnAngkatan'] = 'required';
        $rules['status'] = 'required';
    } elseif ($request->roleName === 'dosen') {
        $rules['status'] = 'required|in:Tetap,Tidak Tetap';
    } elseif ($request->roleName === 'admin') {
        $rules['divisi'] = 'required';
    }

    $validator = \Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Proses Validasi gagal',
            'errors' => $validator->errors()
        ], 401);
    }

    // Simpan ke tabel users
    User::create([
        'userId' => $request->userId,
        'password' => Hash::make($request->password),
        'roleName' => $request->roleName,
        'statusLogin' => 'pending'
    ]);

    // Simpan ke tabel sesuai rolenya
    if ($request->roleName === 'mahasiswa') {
        Mahasiswa::create([
            'userId' => $request->userId,
            'nim' => $request->userId,
            'nama' => $request->nama,
            'email' => $request->email,
            'thnAngkatan' => $request->thnAngkatan,
            'status' => $request->status
        ]);
    }

    if ($request->roleName === 'dosen') {
        Dosen::create([
            'userId' => $request->userId,
            'nidn' => $request->userId,
            'nama' => $request->nama,
            'email' => $request->email,
            'status' => $request->status
        ]);
    }

    if ($request->roleName === 'admin') {
        Admin::create([
            'userId' => $request->userId,
            'nama' => $request->nama,
            'email' => $request->email,
            'divisi' => $request->divisi
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

    $token = $user->createToken('auth_token')->plainTextToken;

    $user->update([
        'statusLogin' => 'online',
        'remember_token' => $token // simpan token ke DB
    ]);

    return response()->json([
        'message' => 'Login berhasil',
        'userId' => $user->userId,
        'roleName' => $user->roleName,
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
    $user = $request->user();

    if (!$user) {
        return response()->json(['message' => 'Token tidak valid atau user belum login'], 401);
    }

    $user->currentAccessToken()->delete();
    $user->update(['statusLogin' => 'offline']);

    return response()->json(['message' => 'Berhasil logout']);
}
}