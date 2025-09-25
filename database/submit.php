<?php
// ====== STEP 1: Ambil Tenant Access Token ======
$app_id = "cli_a85ffa249c789e1a";
$app_secret = "M7LwDuwSsSoA818xMGmy9gosWplI3PpJ";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://open.larksuite.com/open-apis/auth/v3/tenant_access_token/internal/");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    "app_id" => $app_id,
    "app_secret" => $app_secret
]));

$response = curl_exec($ch);
curl_close($ch);
$data = json_decode($response, true);
$token = $data["tenant_access_token"];

// ====== STEP 2: Insert ke Lark Base ======
$table_id = "tbl6gvO1p1WUUFkw"; 
$base_id = "PgPkbs8gLahOwasWB1UjI5iPjpd"; 

$api_url = "https://open.larksuite.com/open-apis/bitable/v1/apps/$base_id/tables/$table_id/records";

// Ambil dari form
$newRecord = [
    "fields" => [
        "Tenant ID"  => $_POST['tenant_id'],
        "Nama"       => $_POST['nama'],
        "Telepon"    => $_POST['telepon'],
        "Email"      => $_POST['email'],
        "Perusahaan" => $_POST['perusahaan']
    ]
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token",
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(["records" => [$newRecord]]));

$result = curl_exec($ch);
curl_close($ch);

echo $result;
