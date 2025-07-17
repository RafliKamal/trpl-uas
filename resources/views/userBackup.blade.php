<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
</head>

<body class="bg-light">
    <main class="container">
        <div class="my-3 p-3 bg-body rounded shadow-sm">



            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
            @endif
            <h4 class="border-bottom pb-2 mb-4">Form Input Data User</h4>
            <form action="" method="post">
                @csrf
                @if (Route::current()->uri == 'student/{id}')
                    @method('PUT')
                @endif
                <div class="row">
                    <!-- Kolom Kiri -->
                    <div class="col-md-6">
                        <div class="mb-3 row">
                            <label for="judul" class="col-sm-4 col-form-label">ID User</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="userId" name="userId" placeholder="NIM/NIDN"
                                    value="{{ isset($student['userId']) ? $student['userId'] : old('userId') }}" required>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="prodi" class="col-sm-4 col-form-label">password</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="password" name="password" placeholder="password"
                                    value="{{ isset($student['password']) ? $student['password'] : old('password') }}" required>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="prodi" class="col-sm-4 col-form-label">role</label>
                            <div class="col-sm-8">
                                <select class="form-select" id="roleName" name="roleName" required>
                                    <option value="dosen" {{ (isset($student['roleName']) && $student['roleName'] == 'dosen') ? 'selected' : '' }}>Dosen</option>
                                    <option value="mahasiswa" {{ (isset($student['roleName']) && $student['roleName'] == 'mahasiswa') ? 'selected' : '' }}>Mahasiswa</option>
                                    <option value="admin" {{ (isset($student['roleName']) && $student['roleName'] == 'admin') ? 'selected' : '' }}>Admin</option>                                    
                                </select>
                            </div>


                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-md-6">
                        <div class="mb-3 row">
                            <label for="prodi" class="col-sm-4 col-form-label">Nama</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Lengkap"
                                    value="{{ isset($student['nama']) ? $student['nama'] : old('nama') }}" required>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="email" class="col-sm-4 col-form-label">Email</label>
                            <div class="col-sm-8">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email"
                                    value="{{ isset($student['email']) ? $student['email'] : old('email') }}" required>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="alamat" class="col-sm-4 col-form-label">Tahun Angkatan</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="thnAngkatan" name="thnAngkatan" placeholder="Tahun Angkatan Mahasiswa"
                                    value="{{ isset($student['thnAngkatan']) ? $student['thnAngkatan'] : old('thnAngkatan') }}" required>
                            </div>
                        </div>
                    </div>


                </div>

                <div class="mb-3 row">
                    <div class="col-sm-8"></div>
                    <div class="col-sm-4 d-grid gap-2">
                        <button type="submit" class="btn btn-primary" name="submit">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- akhir Form -->

        <!-- Start Data -->
        @if (Route::current()->uri == 'student')


            <div class="my-3 p-3 bg-body rounded shadow-sm">
                <h4 class="border-bottom pb-2 mb-4">Data User</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Id</th>
                            <th scope="col">userId</th>
                            <th scope="col">Role</th>
                            <th scope="col">Status</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = $users['from'];
                        @endphp
                        @foreach ($users['data'] as $data)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $data['id'] }}</td>
                                <td>{{ $data['roleName'] }}</td>
                                <td>{{ $data['statusLogin'] }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ url('student/' . $data['id']) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ url('student/' . $data['id']) }}" method="post"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </div>
                                </td>

                            </tr>
                            @php
                                $i++;
                            @endphp
                        @endforeach
                    </tbody>
                </table>
                @if ($users['links'])    
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            @foreach ($users['links'] as $item)
                             <li class="page-item {{ $item['active'] ? 'active' : '' }}"><a class="page-link" href="{{ $item['url2'] }}">{!! $item['label'] !!}</a></li>
                             @endforeach
                        </ul>
                    </nav>
                @endif
            </div>
        @endif
        <!-- End Data -->
    </main>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybB1a3e5c
q1z5+6e5b5f5e5b5f5e5b5f5e5b5f5e5b5f" crossorigin="anonymous"></script>

</body>

</html>