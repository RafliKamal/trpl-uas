<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Login Mahasiswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 30px;
        }
        .container {
            width: 400px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            margin-top: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login User</h2>
        <input type="text" id="userId" placeholder="User ID">
        <input type="password" id="password" placeholder="Password">
        <button onclick="login()">Login</button>

        <h2 style="margin-top: 40px">Registrasi</h2>
        <input type="text" id="regUserId" placeholder="User ID">
        <input type="text" id="regNama" placeholder="Nama Lengkap">
        <input type="email" id="regEmail" placeholder="Email">
        <input type="text" id="regThnAngkatan" placeholder="Tahun Angkatan (untuk mahasiswa)">
        <input type="password" id="regPassword" placeholder="Password">
        <select id="regRole">
            <option value="mahasiswa">Mahasiswa</option>
            <option value="dosen">Dosen</option>
            <option value="admin">Admin</option>
        </select>
        <button onclick="register()">Register</button>

        <div class="message" id="message"></div>
    </div>

    <script>
        const api = 'https://kamal.ricakagus.id/api';

        function showMessage(text, isError = false) {
            const msg = document.getElementById('message');
            msg.textContent = text;
            msg.style.color = isError ? 'red' : 'green';
        }

        function login() {
            const userId = document.getElementById('userId').value;
            const password = document.getElementById('password').value;

            fetch(`${api}/login`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ userId, password })
            })
            .then(res => res.json())
            .then(data => {
                if (data.access_token) {
                    showMessage('Login berhasil! Token disimpan di LocalStorage.');
                    localStorage.setItem('token', data.access_token);
                } else {
                    showMessage(data.message || 'Login gagal', true);
                }
            });
        }

        function register() {
            const userId = document.getElementById('regUserId').value;
            const password = document.getElementById('regPassword').value;
            const roleName = document.getElementById('regRole').value;
            const nama = document.getElementById('regNama').value;
            const email = document.getElementById('regEmail').value;
            const thnAngkatan = document.getElementById('regThnAngkatan').value;

            fetch(`${api}/register`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ userId, password, roleName, nama, email, thnAngkatan })
            })
            .then(res => res.json())
            .then(data => {
                showMessage(data.message || 'Registrasi gagal', !data.message);
            });
        }
    </script>
</body>
</html>
