import "./utils";

export * from "./pokeapi";
export * from "./tyradex";

const tcgCache = {};

export const fetchTCGCards = async (pkmnId) => {
    if (tcgCache[pkmnId] !== undefined) return tcgCache[pkmnId];
    try {
        const res = await fetch(`https://api.tcgdex.net/v2/fr/pokemon/${pkmnId}`);
        if (!res.ok) { tcgCache[pkmnId] = []; return []; }
        const data = await res.json();
        tcgCache[pkmnId] = data.cards || [];
        return tcgCache[pkmnId];
    } catch (_e) {
        tcgCache[pkmnId] = [];
        return [];
    }
};
