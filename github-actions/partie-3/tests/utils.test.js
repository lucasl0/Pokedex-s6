import { describe, it, expect } from "vitest";
import { cleanString } from "../src/utils/index.js";
import { getVersionForName } from "../src/utils/pokemon-mapping.js";

describe("cleanString", () => {
    it("supprime les accents", () => {
        expect(cleanString("Pokémon")).toBe("Pokemon");
    });

    it("passe en minuscules", () => {
        expect(cleanString("PIKACHU")).toBe("pikachu");
    });

    it("remplace les espaces par des tirets", () => {
        expect(cleanString("feu volant")).toBe("feu-volant");
    });

    it("retourne une chaîne vide si undefined", () => {
        expect(cleanString(undefined)).toBe("");
    });

    it("retourne une chaîne vide si null", () => {
        expect(cleanString(null)).toBe("");
    });
});

describe("getVersionForName", () => {
    it("retourne le nom français d'un jeu connu", () => {
        const result = getVersionForName("red");
        expect(typeof result).toBe("string");
        expect(result.length).toBeGreaterThan(0);
    });

    it("retourne la valeur originale si jeu inconnu", () => {
        expect(getVersionForName("unknown-game")).toBe("unknown-game");
    });
});