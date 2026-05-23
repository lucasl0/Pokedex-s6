/**
 * pokemon-mapping-alias.js
 * À fusionner dans src/utils/pokemon-mapping.js
 *
 * Ajoute l'alias getVersionForName à la fin du fichier pokemon-mapping.js existant :
 *
 *   // Alias pour la compatibilité avec les tests unitaires
 *   export const getVersionForName = (name) => FRENCH_GAMES_NAME[name] ?? name;
 */

// Ce fichier est un guide d'intégration.
// Copiez la ligne ci-dessous à la fin de src/utils/pokemon-mapping.js :

// export const getVersionForName = (name) => FRENCH_GAMES_NAME[name] ?? name;
export const getVersionForName = (name) => FRENCH_GAMES_NAME[name] ?? name; 