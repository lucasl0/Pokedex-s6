# GitHub Actions – TP MMI

Ce dépôt contient les ressources et le code réalisés dans le cadre du TP **GitHub Actions** du BUT MMI (option développement).

## 📋 Objectif

Apprendre à configurer et utiliser des pipelines CI/CD avec GitHub Actions, en automatisant des tâches comme le versionnement, le build et le déploiement d'un projet web.

---

## 🗂️ Structure du TP

### Partie 1 – Découverte des GitHub Actions
- Créer un dépôt GitHub
- Ajouter un fichier de workflow `.github/workflows/`
- Comprendre la structure d'un fichier YAML de configuration
- Observer les résultats dans l'onglet **Actions** du dépôt

### Partie 2 – Automatisation du versionnement
- Initialiser un projet npm (`npm init --y`)
- Installer et configurer [`release-it`](https://www.npmjs.com/package/release-it) et [`@release-it/conventional-changelog`](https://www.npmjs.com/package/@release-it/conventional-changelog)
- Respecter la [convention de commits Angular](https://github.com/angular/angular/blob/main/CONTRIBUTING.md#type) (`feat:`, `fix:`, etc.) pour générer automatiquement un `CHANGELOG.md`
- Créer une pipeline déclenchée **manuellement** (`workflow_dispatch`) qui :
  - Clone le projet (`actions/checkout@v4`)
  - Installe les dépendances (`npm ci`)
  - Configure l'utilisateur git
  - Installe Node.js ≥ 20 (`actions/setup-node@v4`)
  - Lance la release (`npm run release --ci`)

> ⚠️ Penser à activer **Read and write permissions** dans `Settings > Actions > General > Workflow permissions`.

### Partie 3 – Build et déploiement SSH
- Reprendre un projet Vite (HTML/Tailwind/JS) du dossier `partie-3`
- Définir une pipeline CI/CD complète avec les jobs suivants :
  - **Lint** du code JavaScript
  - **Tests unitaires** (via Vitest)
  - **Tests e2e** (via Playwright en mode headless CI, avec `--grep` sur les *smoke tests*)
  - **Build** du projet (`npm run build`)
  - **Déploiement SSH** via `rsync` vers un serveur (ex. Alwaysdata)
- Exposer des variables d'environnement Vite :
  - Nom du compte GitHub de la personne ayant déclenché la pipeline (`github.actor`)
  - Date et heure du build

---

## ⚙️ Configuration requise

- Node.js ≥ 20
- npm
- Un serveur avec accès SSH (ex. [Alwaysdata](https://www.alwaysdata.com/))
- Une clé SSH configurée dans les secrets du dépôt (`Settings > Secrets and variables > Actions`)

---

## 🔑 Secrets GitHub à définir

| Nom du secret | Description |
|---|---|
| `SSH_KEY` | Clé SSH privée pour le déploiement |
| `SSH_USER` | Nom d'utilisateur SSH du serveur |
| `SSH_SERVER` | Adresse du serveur de production |

---

## 🛠️ Technologies utilisées

- [GitHub Actions](https://docs.github.com/en/actions)
- [release-it](https://github.com/release-it/release-it)
- [Vite](https://vitejs.dev/)
- [Vitest](https://vitest.dev/)
- [Playwright](https://playwright.dev/)
- `rsync` (déploiement SSH)

---

## 📚 Ressources utiles

- [Syntaxe des workflows GitHub Actions](https://docs.github.com/en/actions/writing-workflows/workflow-syntax-for-github-actions)
- [Variables de contexte GitHub](https://docs.github.com/en/actions/writing-workflows/choosing-what-your-workflow-does/accessing-contextual-information-about-workflow-runs#github-context)
- [Curation d'actions GitHub (awesome-actions)](https://github.com/sdras/awesome-actions)
- [Cours rapide YAML](https://learnxinyminutes.com/yaml)
- [ExplainShell – comprendre les commandes Linux](https://explainshell.com)

---

## 👤 Auteur

[lucasl0](https://github.com/lucasl0) – BUT MMI, option développement
