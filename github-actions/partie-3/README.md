# 🎮 Pokédex S6 – BUT MMI Développement Web & Dispositif Interactif

> Projet réalisé dans le cadre du devoir noté de S6 – Développement Web et dispositif interactif (BUT MMI, option développement).

## 👥 Équipe

| Pseudonyme | Profil GitHub |
|------------|---------------|
| lucasl0 | [github.com/lucasl0](https://github.com/lucasl0) |
| Badiane95 | [github.com/Badiane95](https://github.com/Badiane95) |
| ShaunQ0 | [github.com/ShaunQ0](https://github.com/ShaunQ0) |

## 🛠️ Outils à installer

- [Node.js](https://nodejs.org/) v20+
- [npm](https://www.npmjs.com/) v9+
- PHP 8.1+ (pour `upload.php` – back-end jaquettes)
- Un serveur SSH (pour le déploiement via rsync)
- Un serveur MySQL/MariaDB (si migration BDD activée)

---

## 🚀 Installation

```bash
# Cloner le dépôt
git clone https://github.com/lucasl0/Pokedex-s6.git
cd Pokedex-s6/github-actions/partie-3

# Installer les dépendances
npm install

# Installer les navigateurs Playwright
npx playwright install

# Copier le fichier d'environnement
cp .env.example .env
# Remplir les variables dans .env

# Lancer le serveur de développement
npm run dev
```

---

## ⚙️ Template fichier `.env`

Copier `.env.example` → `.env` et renseigner les valeurs :

```env
# Token GitHub pour l'API (membres du groupe)
# Ne jamais commiter ce fichier !
VITE_GITHUB_TOKEN=ghp_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
VITE_GITHUB_OWNER=lucasl0
VITE_GITHUB_REPO=Pokedex-s6

# Base de données (si applicable)
MYSQL_USER=votre_utilisateur
MYSQL_PASSWORD=votre_mot_de_passe
MYSQL_SERVER=localhost
MYSQL_DATABASE=pokedex

# Informations de déploiement (injectées automatiquement par la CI)
VITE_ACTOR=
VITE_BUILD_DATE=
```

> ⚠️ Le fichier `.env` ne doit **jamais** être commité. Utiliser les **GitHub Secrets** en production.
> Utiliser [singleuse.link](https://singleuse.link/create) pour partager les tokens entre membres.

---

## 🗄️ Schéma base de données

La base de données est utilisée uniquement pour la gestion des **jaquettes de jeux** uploadées via `upload.php`.

```sql
CREATE TABLE IF NOT EXISTS `jaquettes` (
    `id`          INT AUTO_INCREMENT PRIMARY KEY,
    `game_name`   VARCHAR(100) NOT NULL COMMENT 'Correspond au champ name de PokéAPI (ex: red, blue, gold)',
    `filename`    VARCHAR(255) NOT NULL COMMENT 'Nom de fichier sanitizé (minuscules, sans accents)',
    `uploaded_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### Exporter la base de données

```bash
mysqldump -u {USER} -p{PASSWORD} --no-create-db {DATABASE} > database.sql
```

### Importer la base de données

```bash
mysql -u {USER} -p{PASSWORD} -h {SERVER} {DATABASE} < database.sql
```

> Si vous utilisez uniquement le système de fichiers (sans BDD), cette table est optionnelle.

---

## 📦 Scripts disponibles

```bash
npm run dev       # Serveur de développement (Vite)
npm run build     # Build de production
npm run lint      # Linter ESLint
npm run test      # Tests unitaires (Vitest)
npm run e2e       # Tests end-to-end (Playwright)
```

---

## 🔑 Secrets GitHub à configurer

Dans **Settings → Secrets and variables → Actions** :

| Nom du secret | Description |
|---|---|
| `VITE_GITHUB_TOKEN` | Token GitHub pour l'API contributors |
| `SSH_KEY` | Clé SSH privée pour le déploiement rsync |
| `SSH_USER` | Nom d'utilisateur SSH du serveur |
| `SSH_SERVER` | Adresse IP/domaine du serveur |
| `MYSQL_USER` | Utilisateur base de données |
| `MYSQL_PASSWORD` | Mot de passe base de données |
| `MYSQL_SERVER` | Serveur base de données |
| `MYSQL_DATABASE` | Nom de la base de données |

> `GITHUB_TOKEN` est automatiquement fourni par GitHub Actions, pas besoin de le définir manuellement.

---

## 🔄 CI/CD – Pipeline GitHub Actions

Le fichier `.github/workflows/release.yml` (à la racine du dépôt) définit la pipeline :

```
[push/PR → main]
       ↓
   🔍 Lint (ESLint)
       ↓
   🧪 Tests unitaires (Vitest)  ← rapport HTML généré always()
       ↓
   🏗️  Build + .htaccess (.env protégé)
       ↓
   🎭 Tests E2E (Playwright)    ← rapport HTML si failure()
       ↓
   🚀 Deploy rsync SSH (main push uniquement)
         + Migration BDD MySQL
```

Le workflow se déclenche :
- Sur chaque **push** sur `main`
- Sur chaque **pull_request** vers `main`

---

## 🏷️ Versionnement

Le projet utilise les tags git pour le semantic versioning :

```bash
# Tag manuel
git tag 1.0.0
git push origin --tags
```

> C'est la version la plus haute qui sera testée par le correcteur.

---

## 🌐 APIs utilisées

| API | Usage |
|---|---|
| [Tyradex](https://tyradex.app/) | Données Pokémon en français |
| [PokéAPI](https://pokeapi.co/) | Noms étrangers, cris, jeux, numéros |
| [TCGdex](https://tcgdex.dev/) | Cartes TCG du Pokémon |
| [API GitHub](https://docs.github.com/fr/rest) | Liste des membres du groupe |
