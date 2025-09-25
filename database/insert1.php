<?php
$nama = $_POST['nama'];
$telepon = $_POST['telepon'];
$email = $_POST['email'];
$perusahaan = $_POST['perusahaan'];

// Tenant ID ditentukan otomatis, bukan dari input user
$tenant_id = $_POST['tenant_id'];; // bisa kamu ubah per form/tenant

if (!empty($nama) && !empty($telepon) && !empty($email) && !empty($perusahaan)) {
    $host = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "exporttani";

    $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

    if(mysqli_connect_error()) {
        die('Connect Error('. mysqli_connect_errno().')'. mysqli_connect_error());
    } else {
        // Simpan ke database
        $INSERT = "INSERT INTO astratenant (tenant_id, nama, telepon, email, perusahaan) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($INSERT);
        $stmt->bind_param("sssss", $tenant_id, $nama, $telepon, $email, $perusahaan);
        $stmt->execute();

        // Simpan ke CSV
        $filePath = __DIR__ . "/astratenant.csv";
        $isNewFile = !file_exists($filePath);

        $file = fopen($filePath, "a"); // append mode
        if ($isNewFile) {
            fputcsv($file, ["Tenant ID", "Nama", "Telepon", "Email", "Perusahaan"]); // header
        }
        fputcsv($file, [$tenant_id, $nama, $telepon, $email, $perusahaan]);
        fclose($file);

        $stmt->close();
        $conn->close();

        // Redirect ke PDF atau halaman sukses
        header("Location: companyprofile.pdf");
        exit();
    }
} else {
    echo "All fields are required";
    die();
}
