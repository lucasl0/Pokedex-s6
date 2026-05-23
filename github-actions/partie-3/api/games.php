<?php
// api/games.php — Retourne la liste des jaquettes uploadées en JSON

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");

$uploadDir = __DIR__ . "/../public/jaquettes/";
$publicPath = "/jaquettes/";

if (!is_dir($uploadDir)) {
    echo json_encode([]);
    exit;
}

$allowedExtensions = ["jpg", "jpeg", "png", "webp"];
$files = scandir($uploadDir);
$result = [];

foreach ($files as $file) {
    if ($file === "." || $file === "..") continue;

    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExtensions)) continue;

    $gameName = pathinfo($file, PATHINFO_FILENAME);

    $result[] = [
        "name" => $gameName,
        "url"  => $publicPath . $file,
        "file" => $file,
    ];
}

echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
