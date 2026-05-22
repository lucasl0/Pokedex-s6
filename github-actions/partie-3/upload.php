<?php
declare(strict_types=1);

$GAMES = [
    "red"             => "Pokémon Rouge",
    "blue"            => "Pokémon Bleue",
    "yellow"          => "Pokémon Jaune",
    "gold"            => "Pokémon Or",
    "silver"          => "Pokémon Argent",
    "crystal"         => "Pokémon Crystal",
    "ruby"            => "Pokémon Rubis",
    "sapphire"        => "Pokémon Saphir",
    "emerald"         => "Pokémon Émeraude",
    "firered"         => "Pokémon Rouge Feu",
    "leafgreen"       => "Pokémon Vert Feuille",
    "diamond"         => "Pokémon Diamant",
    "pearl"           => "Pokémon Perle",
    "platinum"        => "Pokémon Platine",
    "heartgold"       => "Pokémon Or HeartGold",
    "soulsilver"      => "Pokémon Argent SoulSilver",
    "black"           => "Pokémon Noire",
    "white"           => "Pokémon Blanche",
    "black-2"         => "Pokémon Noire 2",
    "white-2"         => "Pokémon Blanche 2",
    "x"               => "Pokémon X",
    "y"               => "Pokémon Y",
    "omega-ruby"      => "Pokémon Rubis Oméga",
    "alpha-sapphire"  => "Pokémon Saphir Alpha",
    "sun"             => "Pokémon Soleil",
    "moon"            => "Pokémon Lune",
    "ultra-sun"       => "Pokémon Ultra-Soleil",
    "ultra-moon"      => "Pokémon Ultra-Lune",
    "sword"           => "Pokémon Épée",
    "shield"          => "Pokémon Bouclier",
    "scarlet"         => "Pokémon Écarlate",
    "violet"          => "Pokémon Violet",
    "lets-go-pikachu" => "Pokémon Let's Go, Pikachu",
    "lets-go-eevee"   => "Pokémon Let's Go, Évoli",
    "legends-arceus"  => "Légendes Pokémon : Arceus",
];

$ALLOWED_TYPES = ["image/png", "image/jpeg", "image/webp", "image/avif", "image/gif"];
$ALLOWED_EXT   = ["png", "jpg", "jpeg", "webp", "avif", "gif"];
$UPLOAD_DIR    = __DIR__ . "/jaquettes/";
$MAX_SIZE      = 5 * 1024 * 1024; // 5 MB

$message = "";
$success = false;

function sanitize_filename(string $name): string {
    $name = mb_strtolower($name, "UTF-8");
    $name = iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", $name);
    $name = preg_replace('/[^a-z0-9\-]/', '-', $name);
    $name = preg_replace('/-+/', '-', $name);
    return trim($name, '-');
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $gameId = $_POST["game"] ?? "";
    $file   = $_FILES["jaquette"] ?? null;

    if (!array_key_exists($gameId, $GAMES)) {
        $message = "Jeu invalide.";
    } elseif (!$file || $file["error"] !== UPLOAD_ERR_OK) {
        $message = "Erreur lors de l'upload (code " . ($file["error"] ?? "?") . ").";
    } elseif ($file["size"] > $MAX_SIZE) {
        $message = "Fichier trop grand (max 5 Mo).";
    } else {
        $mime = mime_content_type($file["tmp_name"]);
        if (!in_array($mime, $ALLOWED_TYPES, true)) {
            $message = "Type de fichier non autorisé ($mime).";
        } else {
            $origExt = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
            if (!in_array($origExt, $ALLOWED_EXT, true)) {
                $message = "Extension non autorisée.";
            } else {
                $ext      = $origExt === "jpeg" ? "jpg" : $origExt;
                $destName = $gameId . "." . $ext;
                $destPath = $UPLOAD_DIR . $destName;

                if (!is_dir($UPLOAD_DIR)) {
                    mkdir($UPLOAD_DIR, 0755, true);
                }

                if (move_uploaded_file($file["tmp_name"], $destPath)) {
                    $success = true;
                    $message = "Jaquette « " . htmlspecialchars($GAMES[$gameId]) . " » enregistrée sous <code>" . htmlspecialchars($destName) . "</code>.";
                } else {
                    $message = "Impossible de déplacer le fichier.";
                }
            }
        }
    }
}

$existingJaquettes = [];
if (is_dir($UPLOAD_DIR)) {
    foreach (glob($UPLOAD_DIR . "*.*") as $f) {
        $base = pathinfo($f, PATHINFO_FILENAME);
        $ext  = pathinfo($f, PATHINFO_EXTENSION);
        if (array_key_exists($base, $GAMES)) {
            $existingJaquettes[$base] = ["ext" => $ext, "label" => $GAMES[$base]];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Upload Jaquettes – Pokédex Admin</title>
    <style>
        body { font-family: system-ui, sans-serif; max-width: 700px; margin: 2rem auto; padding: 0 1rem; color: #1e293b; }
        h1 { font-size: 1.5rem; margin-bottom: 0.5rem; }
        form { display: flex; flex-direction: column; gap: 1rem; margin-top: 1.5rem; }
        label { font-weight: 600; }
        select, input[type="file"] { width: 100%; padding: .4rem; border: 1px solid #cbd5e1; border-radius: .375rem; }
        button { background: #0f172a; color: #fff; border: none; padding: .6rem 1.2rem; border-radius: .375rem; cursor: pointer; }
        button:hover { background: #1e293b; }
        .msg { padding: .75rem 1rem; border-radius: .375rem; margin-top: 1rem; }
        .msg.ok  { background: #dcfce7; border: 1px solid #86efac; color: #166534; }
        .msg.err { background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; }
        table { width: 100%; border-collapse: collapse; margin-top: 2rem; }
        th, td { text-align: left; padding: .5rem .75rem; border-bottom: 1px solid #e2e8f0; font-size: .875rem; }
        th { background: #f8fafc; font-weight: 600; }
        img.thumb { height: 48px; object-fit: contain; }
        .back { display: inline-block; margin-bottom: 1rem; color: #3b82f6; text-decoration: none; }
        .back:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <a href="/" class="back">← Retour au Pokédex</a>
    <h1>Upload de jaquettes</h1>
    <p>Sélectionnez un jeu et uploadez son image de jaquette. Elle sera disponible dans la modale Pokédex.</p>

    <?php if ($message): ?>
    <div class="msg <?= $success ? 'ok' : 'err' ?>"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div>
            <label for="game">Jeu</label>
            <select name="game" id="game" required>
                <option value="">— Choisir un jeu —</option>
                <?php foreach ($GAMES as $id => $label): ?>
                <option value="<?= htmlspecialchars($id) ?>"
                    <?= (($_POST["game"] ?? "") === $id ? "selected" : "") ?>>
                    <?= htmlspecialchars($label) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="jaquette">Image (PNG, JPG, AVIF, WebP – max 5 Mo)</label>
            <input type="file" name="jaquette" id="jaquette" accept="image/png,image/jpeg,image/webp,image/avif" required />
        </div>
        <button type="submit">Uploader</button>
    </form>

    <?php if (!empty($existingJaquettes)): ?>
    <h2 style="margin-top:2rem; font-size:1.1rem;">Jaquettes disponibles (<?= count($existingJaquettes) ?>)</h2>
    <table>
        <thead><tr><th>Aperçu</th><th>Jeu</th><th>Fichier</th></tr></thead>
        <tbody>
        <?php foreach ($existingJaquettes as $id => $info): ?>
            <tr>
                <td><img class="thumb" src="/jaquettes/<?= htmlspecialchars($id . '.' . $info['ext']) ?>" alt="<?= htmlspecialchars($info['label']) ?>" /></td>
                <td><?= htmlspecialchars($info['label']) ?></td>
                <td><code><?= htmlspecialchars($id . '.' . $info['ext']) ?></code></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</body>
</html>
