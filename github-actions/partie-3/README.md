# Pokédex – Devoir noté S6

Projet réalisé dans le cadre du **devoir noté de S6 – Développement Web et dispositif interactif** (BUT MMI, option développement).

## Auteurs

| Pseudonyme | Profil GitHub |
|---|---|
| lucasl0 | [github.com/lucasl0](https://github.com/lucasl0) |
| Badiane95 | [github.com/Badiane95](https://github.com/Badiane95) |
| ShaunQ0 | [github.com/ShaunQ0](https://github.com/ShaunQ0) |

## Mise en place du projet

### Prérequis

- Node.js ≥ 20
- npm

### Installation

```bash
npm install
```

### Variables d'environnement

Copier `.env.example` en `.env` et remplir les valeurs :

```
VITE_GITHUB_TOKEN=ghp_xxx   # Token GitHub (ne pas committer)
VITE_GITHUB_OWNER=Badiane95
VITE_GITHUB_REPO=Pokedex-s6
```

> Le fichier `.env` est dans `.gitignore` et ne doit jamais être commité.

### Lancer en développement

```bash
npm run dev
```

### Build

```bash
npm run build
```

### Tests

```bash
npm test          # Tests unitaires (Vitest)
npm run e2e       # Tests end-to-end (Playwright, mode UI)
```

### Lint

```bash
npm run lint
```

## Technologies

| Outil | Rôle |
|---|---|
| Vite + Vituum | Build / générateur de pages statiques |
| TailwindCSS v4 | Styles / responsive |
| Vitest | Tests unitaires |
| Playwright | Tests e2e |
| GitHub Actions | CI/CD |
| wavesurfer.js | Visualisation du cri Pokémon |
| rsync | Déploiement SSH |

## APIs utilisées

- [Tyradex](https://tyradex.vercel.app) – données Pokémon en français
- [PokéAPI](https://pokeapi.co) – données complémentaires (évolutions, stats, cris…)
- [TCGdex](https://tcgdex.net) – cartes Pokémon TCG

## Schéma BDD

Ce projet est une application front-end statique sans base de données relationnelle.  
Les données sont récupérées depuis les APIs tierces listées ci-dessus.  
Les jaquettes de jeux sont stockées dans `public/jaquettes/` et uploadables via `/upload.php`.

## Upload de jaquettes

Accéder à `/upload.php` pour uploader des images de jaquettes de jeux. Les images sont sauvegardées sous `{game-id}.{ext}` (ex : `red.png`, `omega-ruby.avif`).

## CI/CD

Le pipeline GitHub Actions (`.github/workflows/partie-3.yml`) se déclenche sur `pull_request` vers `main` et effectue :

1. **Lint** – ESLint
2. **Tests unitaires** – Vitest (parallèle avec smoke tests)
3. **Smoke tests e2e** – Playwright chromium (@smoke)
4. **Tests e2e complets** – Playwright (après smoke)
5. **Build** – Vite (après tests)
6. **Deploy** – rsync via SSH (après build)
