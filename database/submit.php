<?php
// ---------------- CONFIG ----------------
define('LARK_APP_ID',     'cli_a85ffa249c789e1a'); // ganti sesuai App ID
define('LARK_APP_SECRET', 'M7LwDuwSsSoA818xMGmy9gosWplI3PpJ'); // ganti sesuai App Secret
define('LARK_BASE_ID',    'PgPkbs8gLahOwasWB1UjI5iPjpd'); // dari URL base
define('LARK_TABLE_ID',   'tbl6gvO1p1WUUFkw'); // dari URL table

// ---------------- STEP 1: Ambil Tenant Token ----------------
function getTenantAccessToken() {
    $url = 'https://open.larksuite.com/open-apis/auth/v3/tenant_access_token/internal';
    $payload = json_encode([
        'app_id' => LARK_APP_ID,
        'app_secret' => LARK_APP_SECRET
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    $resp = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    echo "<pre>Token Response ($httpCode):\n$resp\n</pre>";

    $j = json_decode($resp, true);
    return $j['tenant_access_token'] ?? false;
}

// ---------------- STEP 2: Insert Record ----------------
function createRecord($token, $fieldsArray) {
    $url = "https://open.larksuite.com/open-apis/bitable/v1/apps/" . LARK_BASE_ID . "/tables/" . LARK_TABLE_ID . "/records";
    $payload = json_encode([
        'fields' => $fieldsArray
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $token",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    $resp = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    echo "<pre>Create Record Response ($httpCode):\n$resp\n</pre>";
}

// ---------------- MAIN ----------------
$nama       = $_POST['nama'] ?? '';
$telepon    = $_POST['telepon'] ?? '';
$email      = $_POST['email'] ?? '';
$perusahaan = $_POST['perusahaan'] ?? '';
$tenant_id  = $_POST['tenant_id'] ?? '';

if (empty($nama) || empty($telepon) || empty($email) || empty($perusahaan)) {
    die("⚠️ All fields are required!");
}

// STEP 1: ambil token
$token = getTenantAccessToken();
if (!$token) {
    die("❌ Gagal ambil tenant_access_token. Cek App ID & App Secret.");
}

// STEP 2: buat record di Lark Bitable
$fields = [
    "Tenant ID" => $tenant_id,
    "Nama"      => $nama,
    "Telepon"   => $telepon,
    "Email"     => $email,
    "Perusahaan"=> $perusahaan
];

createRecord($token, $fields);

echo "<p>✅ Script selesai dieksekusi.</p>";
?>
