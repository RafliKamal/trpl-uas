@extends('layouts.user_type.guest')

@section('content')

  <section class="min-vh-100 mb-8">
    <div class="page-header align-items-start min-vh-50 pt-5 pb-11 mx-3 border-radius-lg"
    style="background-image: url('../assets/img/curved-images/curved14.jpg');">
    <span class="mask bg-gradient-dark opacity-6"></span>
    <div class="container">
      <div class="row justify-content-center">
      <div class="col-lg-5 text-center mx-auto">
        <h1 class="text-white mb-2 mt-5">Welcome!</h1>
        <p class="text-lead text-white">Use these awesome forms to login or create new account in your project for
        free.</p>
      </div>
      </div>
    </div>
    </div>
    <div class="container">
    <div class="row mt-lg-n10 mt-md-n11 mt-n10">
      <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
      <div class="card z-index-0">

        <div class="card-body">
        <form role="form text-left" method="POST" action="/register">
          @csrf
          <div class="mb-3">
          <input type="text" class="form-control" placeholder="Name" name="name" id="name" aria-label="Name"
            aria-describedby="name" value="{{ old('name') }}">
          @error('name')
        <p class="text-danger text-xs mt-2">{{ $message }}</p>
      @enderror
          </div>
          <div class="mb-3">
          <input type="text" class="form-control" placeholder="NIM/NIDN" name="userId" id="userId"
            aria-label="UserId" aria-describedby="name" value="{{ old('user_id') }}">
          @error('userId')
        <p class="text-danger text-xs mt-2">{{ $message }}</p>
      @enderror
          </div>
          <div class="mb-3">
          <input type="email" class="form-control" placeholder="Email" name="email" id="email" aria-label="Email"
            aria-describedby="email-addon" value="{{ old('email') }}">
          @error('email')
        <p class="text-danger text-xs mt-2">{{ $message }}</p>
      @enderror
          </div>
          <div class="mb-3">
          <input type="password" class="form-control" placeholder="Password" name="password" id="password"
            aria-label="Password" aria-describedby="password-addon">
          @error('password')
        <p class="text-danger text-xs mt-2">{{ $message }}</p>
      @enderror
          </div>
          <div class="mb-3">
          <select class="form-select" id="roleName" name="roleName" required>
            <option value="dosen" {{ (isset($student['roleName']) && $student['roleName'] == 'dosen') ? 'selected' : '' }}>Dosen</option>
            <option value="mahasiswa" {{ (isset($student['roleName']) && $student['roleName'] == 'mahasiswa') ? 'selected' : '' }}>Mahasiswa</option>
            <option value="admin" {{ (isset($student['roleName']) && $student['roleName'] == 'admin') ? 'selected' : '' }}>Admin</option>
          </select>
          @error('roleName')
        <p class="text-danger text-xs mt-2">{{ $message }}</p>
      @enderror
          </div>
          <div class="mb-3">
          <input type="text" class="form-control" placeholder="Tahun Angkatan" name="thnAngkatan" id="thnAngkatan"
            aria-label="thnAngkatan" aria-describedby="thnAngkatan-addon" value="{{ old('thnAngkatan') }}">
          @error('thnAngkatan')
        <p class="text-danger text-xs mt-2">{{ $message }}</p>
      @enderror
          </div>
          <div class="mb-3" id="divisiGroup" style="display: none;">
  <input type="text" class="form-control" placeholder="Divisi" name="divisi" id="divisi"
    aria-label="Divisi" value="{{ old('divisi') }}">
  @error('divisi')
    <p class="text-danger text-xs mt-2">{{ $message }}</p>
  @enderror
</div>

<div class="mb-3" id="statusGroup" style="display: none;">
  <select class="form-select" id="status" name="status">
    <option value="">-- Pilih Status Mahasiswa --</option>
    <option value="aktif">Aktif</option>
    <option value="cuti">Cuti</option>
    <option value="lulus">Lulus</option>
    <option value="drop out">Drop Out</option>
  </select>
  @error('status')
    <p class="text-danger text-xs mt-2">{{ $message }}</p>
  @enderror
</div>
<div class="mb-3" id="statusDosenGroup" style="display: none;">
  <select class="form-select" id="statusDosen" name="status">
    <option value="">-- Pilih Status Dosen --</option>
    <option value="Tetap">Tetap</option>
    <option value="Tidak Tetap">Tidak Tetap</option>
  </select>
  @error('status')
    <p class="text-danger text-xs mt-2">{{ $message }}</p>
  @enderror
</div>


          <div class="text-center">
          <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2">Sign up</button>
          </div>
          <p class="text-sm mt-3 mb-0">Already have an account? <a href="login"
            class="text-dark font-weight-bolder">Sign in</a></p>
        </form>
        @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
      @endif

        </div>
      </div>
      </div>
    </div>
    </div>
  </section>

@endsectio
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const roleSelect = document.getElementById('roleName');
    const thnAngkatan = document.getElementById('thnAngkatan').closest('.mb-3');
    const divisiGroup = document.getElementById('divisiGroup');
    const statusGroup = document.getElementById('statusGroup');
    const statusDosenGroup = document.getElementById('statusDosenGroup');

    function updateRoleFields(role) {
      // Sembunyikan semua field dinamis
      thnAngkatan.style.display = 'none';
      divisiGroup.style.display = 'none';
      statusGroup.style.display = 'none';
      statusDosenGroup.style.display = 'none';

      if (role === 'mahasiswa') {
        thnAngkatan.style.display = 'block';
        statusGroup.style.display = 'block';
      } else if (role === 'admin') {
        divisiGroup.style.display = 'block';
      } else if (role === 'dosen') {
        statusDosenGroup.style.display = 'block';
      }
    }

    // Jalankan saat halaman pertama kali dimuat
    updateRoleFields(roleSelect.value);

    // Jalankan saat role dipilih ulang
    roleSelect.addEventListener('change', function () {
      updateRoleFields(this.value);
    });
  });
</script>

