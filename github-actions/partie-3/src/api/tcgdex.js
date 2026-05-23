// src/api/tcgdex.js
const TCG_BASE_URL = "https://api.tcgdex.net/v2/fr";
const tcgCache = {};

/**
 * Récupère les cartes TCG d'un Pokémon avec cache
 * @param {string} pokemonName - nom EN du Pokémon
 * @returns {Promise<Array>}
 */
export const fetchTCGCards = async (pokemonName) => {
    const key = pokemonName.toLowerCase();

    if (tcgCache[key] !== undefined) {
        return tcgCache[key];
    }

    try {
        const res = await fetch(
            `${TCG_BASE_URL}/cards?name=${encodeURIComponent(pokemonName)}&pagination[page]=1&pagination[itemsPerPage]=20`
        );
        if (!res.ok) {
            tcgCache[key] = [];
            return [];
        }
        const data = await res.json();
        const cards = Array.isArray(data) ? data : [];
        tcgCache[key] = cards;
        return cards;
    } catch {
        tcgCache[key] = [];
        return [];
    }
};