<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <main class="container my-4">
        <div class="p-4 bg-white shadow rounded">
            <h2 class="mb-4">Form Input Data User</h2>
            <form id="userForm" action="" method="post" >
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="userId" class="form-label">User ID</label>
                            <input type="text" class="form-control" id="userId" name="userId" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="roleName" class="form-label">Role</label>
                            <select class="form-select" id="roleName" name="roleName" required>
                                <option value="">Pilih Role</option>
                                <option value="admin">Admin</option>
                                <option value="dosen">Dosen</option>
                                <option value="mahasiswa">Mahasiswa</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="divisiOrStatus" class="form-label">Angkatan / Divisi / Status</label>
                            <input type="text" class="form-control" id="divisiOrStatus" name="divisiOrStatus">
                        </div>
                    </div>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
            <form method="POST" action="/logout" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-danger">Logout</button>
            </form>
        </div>
        <div class="p-4 bg-white shadow rounded mt-4">
            <h4>Daftar User</h4>
            <table class="table table-bordered" id="userTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User ID</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                   @php
                           
                        @endphp
                        @foreach ($users as $data)  
                        <tr>    
                            <td>{{ $data['id'] }}</td>
                            <td>{{ $data['userId'] }}</td>
                            <td>{{ $data['roleName'] }}</td>
                            <td>{{ $data['statusLogin'] }}</td>
                            <td>
                                <a href="/users/{{ $data['id'] }}/edit" class="btn btn-sm btn-warning">Edit</a>
                                <form method="POST" action="/users/{{ $data['id'] }}" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </main>
    
</body>

</html>