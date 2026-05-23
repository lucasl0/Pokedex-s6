-- Pokédex S6 — Dump de base de données
-- Généré pour le déploiement CI/CD
-- Import : mysql --defaults-extra-file=.my.cnf < database.sql

CREATE TABLE IF NOT EXISTS `jaquettes` (
    `id`          INT AUTO_INCREMENT PRIMARY KEY,
    `game_name`   VARCHAR(100) NOT NULL COMMENT 'Correspond au champ name de PokéAPI (ex: red, blue, gold)',
    `filename`    VARCHAR(255) NOT NULL COMMENT 'Nom de fichier sanitizé (minuscules, sans accents)',
    `uploaded_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
