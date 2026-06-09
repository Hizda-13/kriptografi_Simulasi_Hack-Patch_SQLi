// Nama Ryan Juniarto
// Nim 231220046
<?php
    $pesan = "";

    // Cek apakah form telah disubmit
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Sesuaikan parameter koneksi database Anda (host, username, password, nama_database)
        $conn = new mysqli("localhost", "root", "", "keamanan_db");

        // Cek koneksi
        if ($conn->connect_error) {
            die("Koneksi gagal: " . $conn->connect_error);
        }

        // Ambil input dari form
        $user_input = $_POST['username'] ?? '';
        $pass_input = $_POST['password'] ?? '';

        // ✅ KODE AMAN (SECURE) - PREPARED STATEMENTS MYSQLI
        
        // 1. Siapkan cetakan Query dengan tanda tanya (?) sebagai tempat variabel
        $sql_aman = "SELECT * FROM users WHERE username = ? AND password = ?";
        
        // 2. Kirim kerangka query ke MySQL untuk di-prepare (dikunci strukturnya)
        $stmt = $conn->prepare($sql_aman);

        // 3. Binding Parameter: "ss" artinya dua inputan tersebut adalah String
        $stmt->bind_param("ss", $user_input, $pass_input);

        // 4. Eksekusi query dengan data aman yang baru disisipkan
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $pesan = "✅ Berhasil Login! Selamat datang, Role: " . $row['role'];
        } else {
            $pesan = "❌ Gagal Login! Sistem memblokir injeksi.";
        }
        
        $stmt->close();
        $conn->close();
    }
?>
<!-- Lanjutkan ke Form HTML di bawah -->
 <!DOCTYPE html>
<html>
<head><title>SQLi Lab</title></head>
<body style="font-family:sans-serif; padding:20px;">
    <h2>Sistem Login Perusahaan</h2>
    <h3 style="color:blue;"><?= $pesan; ?></h3>
    
    <form method="POST">
        <label>Username:</label><br>
        <input type="text" name="username" style="width:300px; padding:8px;"><br><br>
        
        <label>Password:</label><br>
        <input type="text" name="password" style="width:300px; padding:8px;"><br><br>
        
        <button type="submit">Masuk</button>
    </form>
</body>
</html>
