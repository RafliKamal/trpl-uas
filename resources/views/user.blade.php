<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

</head>

<style>
    #userTable {
        table-layout: fixed;
        width: 100%;
    }

    #userTable th,
    #userTable td {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        text-align: center;
    }

    #userTable th:nth-child(1), #userTable td:nth-child(1) { width: 15%; }  /* User ID */
    #userTable th:nth-child(2), #userTable td:nth-child(2) { width: 25%; }  /* Nama */
    #userTable th:nth-child(3), #userTable td:nth-child(3) { width: 15%; }  /* Role */
    #userTable th:nth-child(4), #userTable td:nth-child(4) { width: 15%; }  /* Status */
    #userTable th:nth-child(5), #userTable td:nth-child(5) { width: 30%; }  /* Aksi */

    .pagination .page-link {
        font-size: 1rem;
        padding: 0.5rem 1rem;
    }


</style>


<body class="bg-light">
    <main class="container my-4">
        <div class="d-flex justify-content-end mb-3">
    <form method="POST" action="/logout">
        @csrf
        <button type="submit" class="btn btn-outline-danger">Logout</button>
    </form>
</div>

        <div class="p-4 bg-white shadow rounded">
            @if (session('roleName') === 'admin')
    <h2 class="mb-4">Form Input Data User</h2>

         
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

@endif
           
        </div>
        <div class="p-4 bg-white shadow rounded mt-4">
            <h4>Daftar User</h4>
     <form id="searchForm" class="mb-3">
    <div class="input-group">
        <input type="text" id="searchInput" class="form-control" placeholder="🔍 Cari...">
    </div>
</form>


            <table class="table table-bordered" id="userTable">
                <thead class="table-light text-center">
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
    {{-- Admin bisa edit semua, user hanya bisa edit dirinya sendiri --}}
    @if (session('roleName') === 'admin' || session('userId') === $data['userId'])
        <button class="btn btn-warning btn-sm edit-user-btn" data-id="{{ $data['id'] }}"
            data-userid="{{ $data['userId'] }}" data-nama="{{ $data['nama'] }}"
            data-email="{{ $data['email'] }}" data-role="{{ $data['roleName'] }}"
            data-divisi="{{ $data['divisiOrStatus'] }}"
            data-status="{{ $data['statusMahasiswa'] ?? '' }}"
            data-action="{{ url('/users/' . $data['id']) }}" data-bs-toggle="modal"
            data-bs-target="#editUserModal">
            Update
        </button>
    @endif

    {{-- Admin bisa hapus semua --}}
    @if (session('roleName') === 'admin')
        <button type="button" class="btn btn-sm btn-danger delete-user-btn"
            data-id="{{ $data['id'] }}" data-nama="{{ $data['nama'] }}" data-bs-toggle="modal"
            data-bs-target="#deleteUserModal">
            Delete
        </button>
    @endif
</td>

                        </tr>
                    @endforeach
                </tbody>
            </table>


        </div>

<div class="p-4 bg-white shadow rounded mt-4">
    <h4 class="mb-3">Daftar User Menunggu Verifikasi</h4>

    @if ($pendingUsers->isEmpty())
        <p class="text-muted">Tidak ada user dengan status <strong>pending</strong> saat ini.</p>
    @else
    <div class="table-responsive">
        <table class="table table-bordered" >
            <thead class="table-warning text-center">
                <tr>
                    <th>User ID</th>
                    <th>Nama</th>
                    <th>Role</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pendingUsers as $user)
                    <tr>
                        <td>{{ $user['userId'] }}</td>
                        <td>{{ $user['nama'] }}</td>
                        <td>
                            <span>{{ $user['roleName'] }}</span>
                        </td>
                        <td>{{ $user['email'] }}</td>
                        <td class="text-center">
                            <span class="badge bg-warning text-dark ">{{ $user['statusLogin'] }}</span>
                        </td>
                        <td class="text-center">
                            @if(session('roleName') === 'admin')
                            <div class="d-flex justify-content-center gap-1">
                               @if($user['statusLogin'] === 'pending')
    <form method="POST" action="{{ url('/users/' . $user['id'] . '/verify') }}">
        @csrf
        <button type="submit" class="btn btn-sm btn-success">Verifikasi</button>
    </form>
@endif

                                <button type="button" class="btn btn-sm btn-danger delete-user-btn"
                                    data-id="{{ $user['id'] }}" data-nama="{{ $user['nama'] }}"
                                    data-bs-toggle="modal" data-bs-target="#deleteUserModal">
                                    Delete
                                </button>
                            </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
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

<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
    <div id="toastMessage" class="toast align-items-center text-white bg-success border-0" role="alert"
        aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="toastBody">
                <!-- Pesan akan diisi oleh JavaScript -->
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                aria-label="Close"></button>
        </div>
    </div>
</div>

</body>
<script>

    document.addEventListener('DOMContentLoaded', function () {
        // ======== DYNAMIC FORM LABEL (Form Tambah) ========
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

        // ======== FUNGSI PENDUKUNG UNTUK LABEL DINAMIS DI MODAL EDIT ========
        const statusGroup = document.getElementById('edit-status-group');
        const statusSelect = document.getElementById('edit-status');
        const form = document.getElementById('editUserForm');
        const dynamicLabel = document.getElementById('edit-dynamicLabel');
        const dynamicField = document.getElementById('edit-dynamicField');

        function updateDynamicLabel(role) {
            if (role === 'mahasiswa') {
                dynamicLabel.innerText = 'Tahun Angkatan';
                dynamicField.placeholder = 'Masukkan Tahun Angkatan Mahasiswa';
                statusGroup.style.display = 'block';
            } else {
                statusGroup.style.display = 'none';
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

        // ======== PASANG ULANG LISTENER UPDATE/DELETE SAAT TABLE DIPERBARUI ========
        function attachButtonListeners() {
            // Tombol Edit
            document.querySelectorAll('.edit-user-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const role = this.dataset.role;

                    form.action = this.dataset.action;
                    document.getElementById('edit-id').value = this.dataset.id;
                    document.getElementById('edit-userId').value = this.dataset.userid;
                    document.getElementById('edit-nama').value = this.dataset.nama;
                    document.getElementById('edit-email').value = this.dataset.email;
                    document.getElementById('edit-roleName').value = role;
                    document.getElementById('edit-dynamicField').value = this.dataset.divisi;
                    document.getElementById('edit-password').value = '';

                    // Tampilkan status jika mahasiswa
                    updateDynamicLabel(role);
                    if (role === 'mahasiswa') {
                        statusSelect.value = this.dataset.status || '';
                    } else {
                        statusSelect.value = '';
                    }
                });
            });

            // Tombol Delete
            document.querySelectorAll('.delete-user-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const deleteForm = document.getElementById('deleteUserForm');
                    deleteForm.action = `/users/${this.dataset.id}`;
                    document.getElementById('userToDelete').textContent = this.dataset.nama;
                });
            });
        }

        // ======== PAGINATION & FILTERING ========
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

            attachButtonListeners(); // ⬅️ Pasang ulang setiap render
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

        // Inisialisasi pertama
        filterRows();

        // ======== TOAST NOTIFIKASI ========
        const toastElement = document.getElementById('toastMessage');
        const toastBody = document.getElementById('toastBody');
        const toast = new bootstrap.Toast(toastElement);

        @if (session('success'))
            toastBody.innerText = "{{ session('success') }}";
            toastElement.classList.remove('bg-danger');
            toastElement.classList.add('bg-success');
            toast.show();
        @endif

        @if (session('error'))
            toastBody.innerText = "{{ session('error') }}";
            toastElement.classList.remove('bg-success');
            toastElement.classList.add('bg-danger');
            toast.show();
        @endif
    });
</script>





</html>