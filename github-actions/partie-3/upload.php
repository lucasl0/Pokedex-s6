<?php
// upload.php — Formulaire d'upload de jaquettes de jeux

$games = [
    "red" => "Pokémon Rouge",
    "blue" => "Pokémon Bleu",
    "yellow" => "Pokémon Jaune",
    "gold" => "Pokémon Or",
    "silver" => "Pokémon Argent",
    "crystal" => "Pokémon Cristal",
    "ruby" => "Pokémon Rubis",
    "sapphire" => "Pokémon Saphir",
    "emerald" => "Pokémon Émeraude",
    "firered" => "Pokémon Rouge Feu",
    "leafgreen" => "Pokémon Vert Feuille",
    "diamond" => "Pokémon Diamant",
    "pearl" => "Pokémon Perle",
    "platinum" => "Pokémon Platine",
    "heartgold" => "Pokémon Or HeartGold",
    "soulsilver" => "Pokémon Argent SoulSilver",
    "black" => "Pokémon Noir",
    "white" => "Pokémon Blanc",
    "black-2" => "Pokémon Noir 2",
    "white-2" => "Pokémon Blanc 2",
    "x" => "Pokémon X",
    "y" => "Pokémon Y",
    "omega-ruby" => "Pokémon Rubis Oméga",
    "alpha-sapphire" => "Pokémon Saphir Alpha",
    "sun" => "Pokémon Soleil",
    "moon" => "Pokémon Lune",
    "ultra-sun" => "Pokémon Ultra-Soleil",
    "ultra-moon" => "Pokémon Ultra-Lune",
    "lets-go-pikachu" => "Pokémon : Let's Go, Pikachu !",
    "lets-go-eevee" => "Pokémon : Let's Go, Évoli !",
    "sword" => "Pokémon Épée",
    "shield" => "Pokémon Bouclier",
    "brilliant-diamond" => "Pokémon Diamant Étincelant",
    "shining-pearl" => "Pokémon Perle Scintillante",
    "legends-arceus" => "Légendes Pokémon : Arceus",
    "scarlet" => "Pokémon Écarlate",
    "violet" => "Pokémon Violet",
];

$uploadDir = __DIR__ . "/public/jaquettes/";
$maxSize = 5 * 1024 * 1024; // 5 Mo
$success = null;
$error = null;

function sanitizeFilename(string $name): string {
    $name = iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", $name);
    $name = strtolower($name);
    $name = preg_replace('/[\s_\']+/', '-', $name);
    $name = preg_replace('/[^a-z0-9\-.]/', '', $name);
    $name = preg_replace('/-+/', '-', $name);
    return trim($name, '-');
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $gameKey = $_POST["game"] ?? "";
    $file = $_FILES["jaquette"] ?? null;

    if (empty($gameKey) || !array_key_exists($gameKey, $games)) {
        $error = "Jeu invalide sélectionné.";
    } elseif (!$file || $file["error"] !== UPLOAD_ERR_OK) {
        $error = "Erreur lors de l'upload du fichier.";
    } elseif ($file["size"] > $maxSize) {
        $error = "Fichier trop volumineux (max 5 Mo).";
    } else {
        $allowedTypes = ["image/jpeg", "image/png", "image/webp"];
        $mimeType = mime_content_type($file["tmp_name"]);

        if (!in_array($mimeType, $allowedTypes)) {
            $error = "Format non supporté. Utilisez JPG, PNG ou WebP.";
        } else {
            $ext = match ($mimeType) {
                "image/jpeg" => "jpg",
                "image/png"  => "png",
                "image/webp" => "webp",
                default      => "jpg",
            };

            $sanitizedName = sanitizeFilename($gameKey) . "." . $ext;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $destination = $uploadDir . $sanitizedName;

            if (move_uploaded_file($file["tmp_name"], $destination)) {
                $success = "Jaquette uploadée avec succès : <strong>" . htmlspecialchars($sanitizedName) . "</strong>";
            } else {
                $error = "Impossible de déplacer le fichier. Vérifiez les permissions.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Jaquettes — Admin Pokédex</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: system-ui, sans-serif; background: #0f172a; color: #e2e8f0; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem; }
        .card { background: #1e293b; border: 1px solid #334155; border-radius: 1rem; padding: 2rem; max-width: 480px; width: 100%; }
        h1 { font-size: 1.5rem; margin-bottom: 1.5rem; color: #f8fafc; }
        label { display: block; font-size: 0.875rem; color: #94a3b8; margin-bottom: 0.4rem; margin-top: 1rem; }
        select, input[type="file"] { width: 100%; padding: 0.6rem 0.8rem; background: #0f172a; border: 1px solid #334155; border-radius: 0.5rem; color: #e2e8f0; font-size: 0.9rem; }
        button { margin-top: 1.5rem; width: 100%; padding: 0.75rem; background: #3b82f6; color: white; border: none; border-radius: 0.5rem; font-size: 1rem; cursor: pointer; font-weight: 600; transition: background 0.2s; }
        button:hover { background: #2563eb; }
        .alert { margin-top: 1rem; padding: 0.75rem 1rem; border-radius: 0.5rem; font-size: 0.875rem; }
        .alert-success { background: #14532d; color: #86efac; }
        .alert-error { background: #7f1d1d; color: #fca5a5; }
    </style>
</head>
<body>
<main>
    <div class="card">
        <h1>🎮 Upload d'une jaquette</h1>

        <?php if ($success): ?>
            <div class="alert alert-success" role="alert"><?= $success ?></div>
        <?php elseif ($error): ?>
            <div class="alert alert-error" role="alert"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" novalidate>
            <div>
                <label for="game">Sélectionner le jeu</label>
                <select id="game" name="game" required aria-required="true">
                    <option value="" disabled selected>— Choisir un jeu —</option>
                    <?php foreach ($games as $key => $label): ?>
                        <option value="<?= htmlspecialchars($key) ?>"
                            <?= ($_POST["game"] ?? "") === $key ? "selected" : "" ?>>
                            <?= htmlspecialchars($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="jaquette">Fichier image (JPG, PNG, WebP)</label>
                <input
                    type="file"
                    id="jaquette"
                    name="jaquette"
                    accept="image/jpeg,image/png,image/webp"
                    required
                    aria-required="true"
                >
            </div>

            <button type="submit">Uploader la jaquette</button>
        </form>
    </div>
</main>
</body>
</html>
