<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

if (isset($_POST['page'])) {
    $page = intval($_POST['page']);
    $base_url = "https://api.hydrax.net/9fb84d1976e10e82aaaf0709e4a49348/list?page=" . $page;
    $response = file_get_contents($base_url);
    $data = json_decode($response, true);
    echo json_encode($data);
} else {
    echo json_encode(["error" => "No page number provided."]);
}
?>
