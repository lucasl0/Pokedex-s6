# Devoir noté – Développement Web et dispositif interactif

> ⚠️ Les consignes pourront être modifiées.

Projet réalisé dans le cadre du **devoir noté de S6 – Développement Web et dispositif interactif** (BUT MMI, option développement). Ce travail en groupe (3-4 membres) consiste à améliorer le projet Pokédex issu du cours de CI/CD dans les domaines du **front-end**, **back-end** et **DevOps**.

📦 [Télécharger le projet base (dossier partie 3)](https://github.com/DanYellow/cours/raw/refs/heads/main/s6-developpement-web-et-dispositif-interactif/travaux-pratiques/numero-4/s6-developpement-web-et-dispositif-interactif_travaux-pratiques_numero-4.ressources.zip)

---

## 📋 Rendus attendus

- Lien du projet sur GitHub *(un seul rendu par groupe)*
- La version finale doit être **taguée** (ex : `1.0.0` en semantic versioning)
  - Via `release-it` ou manuellement : `git tag 1.0.0`
  - Ne pas oublier `fetch-depth: 0` dans `actions/checkout@v4` pour générer le `CHANGELOG.md`
- C'est la **version la plus haute** qui sera testée, pas une branche
- Un fichier texte contenant :
  - L'URL du projet sur GitHub
  - L'URL du site déployé

---

## 🏆 Notation

Chaque partie est notée **indépendamment** (3 notes). Critères évalués :
- Qualité du code et accessibilité
- Affichage dans le navigateur
- Bon fonctionnement des fonctionnalités attendues

---

## ✅ Liste des tâches

### Démarrage
- [ ] Lire les consignes
- [ ] Mettre le projet sur GitHub et ajouter les collaborateurs (ne pas oublier le `.gitignore`)
- [ ] Générer un token pour l'API GitHub *(ne pas le commiter – utiliser les secrets et variables d'env)*
- [ ] Remettre un fichier texte avec les URLs du projet et du site déployé
- [ ] Rédiger un `README.md` expliquant la mise en place du projet (outils, schéma BDD, template `.env`, etc.)

---

### 🎨 Front-end

- [ ] **Charger les données du Pokédex** lié au Pokémon affiché (pour afficher précédent/suivant correctement)
  - *Optionnel : afficher le nom du dex en français (ex : Unova → Unys)*
- [ ] **Afficher les noms étrangers** des Pokémon (anglais et japonais) dans la modale
- [ ] **Lien Poképedia** vers la fiche du Pokémon depuis la modale
- [ ] **Changer le favicon** pour le sprite du Pokémon affiché *(réinitialiser au retour sur le Pokédex)*
- [ ] **Afficher les types** du Pokémon en mode liste uniquement *(via CSS container queries, sans JS)*
  - [Documentation CSS container queries](https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_containment/Container_queries)
- [ ] **Afficher les numéros de Pokédex** par région *(utiliser la constante `POKEDEX` fournie)*
- [ ] **Corriger les tests unitaires** *(ne pas supprimer les tests qui échouent)*
- [ ] **Changer la couleur `theme-color`** en fonction du premier type du Pokémon affiché *(visible sur smartphone)*
- [ ] **Afficher les cartes TCG** (via [tcgdex.net](https://tcgdex.dev/rest/filtering-sorting-pagination)) dans la modale
  - Mettre la réponse en cache (système de cache déjà présent)
  - Désactiver la balise `<details>` si aucune carte n'existe
  - *Optionnel : afficher les détails d'une carte au clic*
- [ ] **Spectre sonore du cri** du Pokémon via [wavesurfer.js](https://www.npmjs.com/package/wavesurfer.js) *(avec possibilité de rejouer)*
- [ ] **Créditer les ressources externes** :
  - APIs : [tyradex](https://tyradex.vercel.app/), [pokeapi](https://pokeapi.co/), [tcgdex](https://tcgdex.dev/)
  - Icônes : [pokemon-type-icons](https://github.com/partywhale/pokemon-type-icons)
  - Logo de l'université + année universitaire ([logos disponibles ici](https://github.com/DanYellow/cours/tree/main/logos))
- [ ] **Lister les membres du groupe** via l'API GitHub
  - Afficher nom/prénom, pseudonyme → redirection vers le profil GitHub
  - [API Collaborators](https://docs.github.com/fr/rest/collaborators/collaborators?apiVersion=2022-11-28#list-repository-collaborators) / [API Users](https://docs.github.com/fr/rest/users/users?apiVersion=2022-11-28#get-a-user)
  - Ne pas exposer le token : passer par PHP/Python côté serveur, ou injecter via Vite (fichier de config ou `.env` généré en CI/CD)

> Le site est responsive et doit le rester. Les styles sont gérés via **TailwindCSS v4**.

---

### 🗄️ Back-end / Administration

- [ ] **Formulaire d'upload de jaquettes** de jeux
  - Liste déroulante des jeux disponibles (`src/utils.js`)
  - Noms de fichiers sanitizés (minuscules, sans accents/espaces)
    - Ex : `Let's_Go Évoli.jpg` → `let-s-go-evoli.jpg`
  - [Télécharger les jaquettes](https://github.com/DanYellow/cours/raw/refs/heads/main/s6-developpement-web-et-dispositif-interactif/s6-developpement-web-et-dispositif-interactif.devoir.zip)
- [ ] **Afficher les jaquettes** dans la modale (section "Apparitions")
  - Associer l'image au champ `name` retourné par pokeapi (ex : `red`, `blue`, `gold`...)

---

### ⚙️ CI/CD

> Les workflows doivent être dans `.github/workflows/`. [Voir correction TP](https://github.com/DanYellow/cours/blob/main/s6-developpement-web-et-dispositif-interactif/travaux-pratiques/numero-4/ressources/github-actions/correction/partie-3/.github/workflows/release.yml)

Pipeline sur la branche `main` (déclenchée automatiquement sur `pull_request`) :

- [ ] Déployer le projet en production
- [ ] Exécuter les tests e2e de façon optimale
- [ ] Linter le code avec ESLint
- [ ] Exécuter les tests unitaires
- [ ] Migrer la base de données *(si applicable)*
- [ ] Rendre les fichiers `.env` inaccessibles au public
  - Via `.htaccess` ou en ne mettant pas les `.env` à la racine
- [ ] Afficher le nom du dernier déployeur + date/heure *(via `github.actor`)*

---

### 🗃️ Migration base de données (MySQL)

**Exporter :**
```bash
mysqldump -u {USER} -p{PASSWORD} {DATABASE} > dump-file.sql
# --no-data : exporte uniquement le schéma
# --no-create-db : évite l'erreur CREATE DATABASE sur certains hébergeurs
```

**Importer (méthode sécurisée via `.my.cnf`) :**
```bash
cat > .my.cnf << EOF
[client]
user=$MYSQL_USER
password=$MYSQL_PASSWORD
database=$MYSQL_DATABASE
host=$MYSQL_SERVER
EOF

chmod 400 .my.cnf
mysql --defaults-extra-file=.my.cnf < database.sql
```

---

## 🔑 Secrets GitHub à définir

| Nom du secret | Description |
|---|---|
| `SSH_KEY` | Clé SSH privée pour le déploiement |
| `SSH_USER` | Nom d'utilisateur SSH du serveur |
| `SSH_SERVER` | Adresse du serveur de production |
| `MYSQL_USER` | Utilisateur base de données |
| `MYSQL_PASSWORD` | Mot de passe base de données |
| `MYSQL_SERVER` | Serveur base de données |
| `MYSQL_DATABASE` | Nom de la base de données |
| `GITHUB_TOKEN` | Token API GitHub *(ne pas commiter)* |

---

## 🛠️ Technologies utilisées

- [Vite](https://vitejs.dev/) + [TailwindCSS v4](https://grafikart.fr/tutoriels/tailwindcss-v4-2265)
- [Vitest](https://vitest.dev/) – tests unitaires
- [Playwright](https://playwright.dev/) – tests e2e
- [GitHub Actions](https://docs.github.com/en/actions) – CI/CD
- [release-it](https://github.com/release-it/release-it) – versionnement
- [wavesurfer.js](https://www.npmjs.com/package/wavesurfer.js) – spectre sonore
- `rsync` – déploiement SSH

## 📡 APIs utilisées

- [Tyradex](https://tyradex.vercel.app/)
- [PokéAPI](https://pokeapi.co/)
- [TCGdex](https://tcgdex.dev/)

---

## 🚀 Pour aller plus loin

### Front-end
- Système de comparaison entre deux Pokémon
- Historique des fiches via [l'API Navigation](https://developer.mozilla.org/en-US/docs/Web/API/Navigation) *(non supportée sur Firefox/Safari)*
- Mode sombre

### Back-office
- Générer une image non-retina et utiliser l'attribut `srcset`

### CI/CD
- Générer un artefact du rapport HTML Playwright **uniquement si les tests échouent**
- Générer un artefact du rapport HTML Vitest ([documentation](https://vitest.dev/guide/reporters#html-reporter))
  - Ne pas commiter le rapport (l'ajouter au `.gitignore`)

---

## 👥 Auteurs

Projet réalisé par le groupe – BUT MMI S6, option développement  
[lucasl0](https://github.com/lucasl0)
