<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body class="bg-light">
    <main class="container my-4">
        <div class="p-4 bg-white shadow rounded">
            <h2 class="mb-4">Form Input Data User</h2>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <form id="userForm" action="{{ isset($user) ? '/users/' . $user['id'] : '/users' }}" method="POST">
                @csrf
                @if(isset($user))
                    @method('PUT')
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="userId" class="form-label">User ID</label>
                            <input type="text" class="form-control" id="userId" name="userId" required
                                value="{{ $user['userId'] ?? '' }}" {{ isset($user) ? 'readonly' : '' }}>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" {{ isset($user) ? '' : 'required' }}>
                        </div>

                        <div class="mb-3">
                            <label for="roleName" class="form-label">Role</label>
                            <select id="roleName" name="roleName" class="form-select" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="admin" {{ (isset($user) && $user['roleName'] === 'admin') ? 'selected' : '' }}>Admin</option>
                                <option value="dosen" {{ (isset($user) && $user['roleName'] === 'dosen') ? 'selected' : '' }}>Dosen</option>
                                <option value="mahasiswa" {{ (isset($user) && $user['roleName'] === 'mahasiswa') ? 'selected' : '' }}>Mahasiswa</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" required
                                value="{{ $user['nama'] ?? '' }}">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required
                                value="{{ $user['email'] ?? '' }}">
                        </div>

                        <div class="mb-3">
                            <label for="dynamicField" id="dynamicLabel" class="form-label">
                                {{ (isset($user) && $user['roleName'] === 'admin') ? 'Divisi' : 'Status / Angkatan' }}
                            </label>
                            <input type="text" class="form-control" id="dynamicField" name="divisiOrStatus"
                                value="{{ $user['divisiOrStatus'] ?? '' }}" required>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">{{ isset($user) ? 'Update' : 'Simpan' }}</button>
                </div>
            </form>

            <form method="POST" action="/logout" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-danger">Logout</button>
            </form>
        </div>
        <div class="p-4 bg-white shadow rounded mt-4">
            <h4>Daftar User</h4>
            <div class="row mb-3 mt-4">
                <div class="col-md-6">
                    <input type="text" id="searchInput" class="form-control"
                        placeholder="🔍 Cari User ID, Nama, Role, atau Status...">
                </div>
            </div>

            <table class="table table-bordered" id="userTable">
                <thead class="table-light">
                    <tr>

                        <th>User ID</th>
                        <th>Nama</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $data)
                        <tr>

                            <td class="user-id">{{ $data['userId'] }}</td>
                            <td class="user-nama">{{ $data['nama'] }}</td>
                            <td class="user-role">{{ $data['roleName'] }}</td>
                            <td class="user-status">{{ $data['statusLogin'] }}</td>
                            <td class="text-center">
                                <button class="btn btn-warning btn-sm edit-user-btn" data-id="{{ $data['id'] }}"
                                    data-userid="{{ $data['userId'] }}" data-nama="{{ $data['nama'] }}"
                                    data-email="{{ $data['email'] }}" data-role="{{ $data['roleName'] }}"
                                    data-divisi="{{ $data['divisiOrStatus'] }}"
                                    data-status="{{ $data['statusMahasiswa'] ?? '' }}"
                                    data-action="{{ url('/users/' . $data['id']) }}" data-bs-toggle="modal"
                                    data-bs-target="#editUserModal">
                                    Update
                                </button>
                                <button type="button" class="btn btn-sm btn-danger delete-user-btn"
                                    data-id="{{ $data['id'] }}" data-nama="{{ $data['nama'] }}" data-bs-toggle="modal"
                                    data-bs-target="#deleteUserModal">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>



    </main>
    <!-- Modal Edit User -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editUserForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel">Edit Data User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit-id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit-userId" class="form-label">User ID</label>
                                    <input type="text" class="form-control" id="edit-userId" name="userId" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-password" class="form-label">Password (Kosongkan jika tidak
                                        diganti)</label>
                                    <input type="password" class="form-control" id="edit-password" name="password">
                                </div>
                                <div class="mb-3">
                                    <label for="edit-roleName" class="form-label">Role</label>
                                    <select id="edit-roleName" name="roleName" class="form-select" required>
                                        <option value="">-- Pilih Role --</option>
                                        <option value="admin">Admin</option>
                                        <option value="dosen">Dosen</option>
                                        <option value="mahasiswa">Mahasiswa</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit-nama" class="form-label">Nama</label>
                                    <input type="text" class="form-control" id="edit-nama" name="nama" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="edit-email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-dynamicField" id="edit-dynamicLabel"
                                        class="form-label">Keterangan</label>
                                    <input type="text" class="form-control" id="edit-dynamicField" name="divisiOrStatus"
                                        required>
                                </div>
                                <div class="mb-3" id="edit-status-group" style="display: none;">
                                    <label for="edit-status" class="form-label">Status Mahasiswa</label>
                                    <select class="form-select" id="edit-status" name="status">
                                        <option value="">-- Pilih Status --</option>
                                        <option value="aktif">Aktif</option>
                                        <option value="cuti">Cuti</option>
                                        <option value="lulus">Lulus</option>
                                        <option value="drop out">Drop Out</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Hapus -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" id="deleteUserForm">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteUserModalLabel">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <p>Yakin ingin menghapus user <strong id="userToDelete"></strong>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


</body>
<script>
    const roleSelect = document.getElementById('roleName');
    const input = document.getElementById('dynamicField');
    const label = document.getElementById('dynamicLabel');

    roleSelect.addEventListener('change', function () {
        const role = this.value;

        if (role === 'mahasiswa') {
            label.innerText = 'Tahun Angkatan';
            input.placeholder = 'Masukkan Tahun Angkatan Mahasiswa';
        } else if (role === 'dosen') {
            label.innerText = 'Status Dosen';
            input.placeholder = 'Masukkan Status Dosen: Tetap/Tidak Tetap';
        } else if (role === 'admin') {
            label.innerText = 'Divisi';
            input.placeholder = 'Masukkan Divisi Proyek: A/B/C...';
        } else {
            label.innerText = 'Keterangan';
            input.placeholder = '';
        }
    });


    document.addEventListener('DOMContentLoaded', function () {
        const editButtons = document.querySelectorAll('.edit-user-btn');
        const form = document.getElementById('editUserForm');

        const roleSelect = document.getElementById('edit-roleName');
        const dynamicLabel = document.getElementById('edit-dynamicLabel');
        const dynamicField = document.getElementById('edit-dynamicField');

        function updateDynamicLabel(role) {
            if (role === 'mahasiswa') {
                dynamicLabel.innerText = 'Tahun Angkatan';
                dynamicField.placeholder = 'Masukkan Tahun Angkatan Mahasiswa';
            } else if (role === 'dosen') {
                dynamicLabel.innerText = 'Status Dosen';
                dynamicField.placeholder = 'Masukkan Status Dosen: Tetap/Tidak Tetap';
            } else if (role === 'admin') {
                dynamicLabel.innerText = 'Divisi';
                dynamicField.placeholder = 'Masukkan Divisi Proyek: A/B/C...';
            } else {
                dynamicLabel.innerText = 'Keterangan';
                dynamicField.placeholder = '';
            }
        }

        // Saat tombol edit diklik
        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                const id = this.dataset.id;
                const userId = this.dataset.userid;
                const nama = this.dataset.nama;
                const email = this.dataset.email;
                const role = this.dataset.role;
                const divisi = this.dataset.divisi;
                const action = this.dataset.action;

                form.action = action;

                document.getElementById('edit-id').value = id;
                document.getElementById('edit-userId').value = userId;
                document.getElementById('edit-nama').value = nama;
                document.getElementById('edit-email').value = email;
                document.getElementById('edit-roleName').value = role;
                document.getElementById('edit-dynamicField').value = divisi;
                document.getElementById('edit-password').value = '';

                updateDynamicLabel(role); // ⬅️ Ubah label sesuai role saat modal dibuka
            });
        });

        // Saat dropdown role diubah
        roleSelect.addEventListener('change', function () {
            const selectedRole = this.value;
            updateDynamicLabel(selectedRole); // ⬅️ Ubah label saat role diganti di modal
            if (selectedRole === 'mahasiswa') {
                dynamicField.value = ''; // Kosongkan field jika role mahasiswa
            } else if (selectedRole === 'dosen') {
                dynamicField.value = ''; // Kosongkan field jika role dosen
            } else if (selectedRole === 'admin') {
                dynamicField.value = ''; // Kosongkan field jika role admin
            }
        });
    });

    const statusGroup = document.getElementById('edit-status-group');
    const statusSelect = document.getElementById('edit-status');

    function updateDynamicLabel(role) {
        if (role === 'mahasiswa') {
            dynamicLabel.innerText = 'Tahun Angkatan';
            dynamicField.placeholder = 'Masukkan Tahun Angkatan Mahasiswa';
            statusGroup.style.display = 'block'; // ✅ Tampilkan kolom status
        } else {
            statusGroup.style.display = 'none'; // ✅ Sembunyikan jika bukan mahasiswa
        }

        if (role === 'dosen') {
            dynamicLabel.innerText = 'Status Dosen';
            dynamicField.placeholder = 'Masukkan Status Dosen: Tetap/Tidak Tetap';
        } else if (role === 'admin') {
            dynamicLabel.innerText = 'Divisi';
            dynamicField.placeholder = 'Masukkan Divisi Proyek: A/B/C...';
        } else if (role !== 'mahasiswa') {
            dynamicLabel.innerText = 'Keterangan';
            dynamicField.placeholder = '';
        }
    }

    // Saat tombol edit ditekan
    document.querySelectorAll('.edit-user-btn').forEach(button => {
        button.addEventListener('click', function () {
            const role = this.dataset.role;
            updateDynamicLabel(role);

            // Isikan nilai status jika mahasiswa
            if (role === 'mahasiswa') {
                statusSelect.value = this.dataset.status || '';
            } else {
                statusSelect.value = '';
            }
        });
    });


    //modal Hapus
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.delete-user-btn');
        const deleteForm = document.getElementById('deleteUserForm');
        const userToDelete = document.getElementById('userToDelete');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const userId = this.dataset.id;
                const nama = this.dataset.nama;
                deleteForm.action = `/users/${userId}`;
                userToDelete.textContent = nama;
            });
        });
    });



    //Filtering
    document.addEventListener('DOMContentLoaded', function () {
        const rowsPerPage = 10;
        const table = document.getElementById('userTable');
        const tbody = table.querySelector('tbody');
        const searchInput = document.getElementById('searchInput');
        const allRows = Array.from(tbody.querySelectorAll('tr'));
        const paginationContainer = document.createElement('div');
        paginationContainer.className = 'd-flex justify-content-center mt-3';
        table.parentElement.appendChild(paginationContainer);

        let currentPage = 1;

        function renderTable(filteredRows) {
            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            tbody.innerHTML = '';
            filteredRows.slice(start, end).forEach(row => tbody.appendChild(row));
        }

        function updatePagination(filteredRows) {
            const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
            paginationContainer.innerHTML = '';

            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.textContent = i;
                btn.className = `btn btn-sm mx-1 ${i === currentPage ? 'btn-primary' : 'btn-outline-primary'}`;
                btn.addEventListener('click', () => {
                    currentPage = i;
                    renderTable(filteredRows);
                    updatePagination(filteredRows);
                });
                paginationContainer.appendChild(btn);
            }
        }

        function filterRows() {
            const keyword = searchInput.value.toLowerCase();
            const filtered = allRows.filter(row => {
                const userId = row.querySelector('.user-id').innerText.toLowerCase();
                const nama = row.querySelector('.user-nama').innerText.toLowerCase();
                const role = row.querySelector('.user-role').innerText.toLowerCase();
                const status = row.querySelector('.user-status').innerText.toLowerCase();
                return userId.includes(keyword) || nama.includes(keyword) || role.includes(keyword) || status.includes(keyword);
            });
            currentPage = 1;
            renderTable(filtered);
            updatePagination(filtered);
        }

        searchInput.addEventListener('keyup', filterRows);

        // Inisialisasi
        filterRows();
    });

</script>




</html>