<?php
$server = "localhost";
$user = "root";
$pass = "";
$db = "beasiswabang"; // cek database di "localhost/phpmyadmin", cek juga data sql yang sudah disediakan.

try {
    $conn = mysqli_connect($server, $user, $pass, $db);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
}
catch (Exception $e) {
    echo "Connection failed: " . $e->getMessage();
}

if (isset($_GET['filename'])) {
    $filename = $_GET['filename'];

    // Ambil informasi file dari database
    $query = "SELECT filepath FROM files WHERE filename = '$filename'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $file_path = $row['filepath'];

        if (file_exists($file_path)) {
            // Set header dan lakukan output file
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($file_path));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: private');
            header('Pragma: private');
            header('Content-Length: ' . filesize($file_path));
            ob_clean();
            flush();
            readfile($file_path);

            exit;
        } else {
            echo "File Not Found";
        }
    } else {
        echo "File Not Found in Database";
    }
    
    // Tutup koneksi database setelah digunakan
    $conn->close();
}
?>
