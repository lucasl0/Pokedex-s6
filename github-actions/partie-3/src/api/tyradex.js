import axios from "axios";

const api = axios.create({
    baseURL: "/api",
});

export const fetchPokemonForGeneration = async (generation = 1) => {
    let listPokemon = [];
    try {
        const req = await api.get(`/v1/gen/${generation}`);
        listPokemon = req.data;

        const serverErrorStartNumber = 400;
        if (req.data?.status >= serverErrorStartNumber) {
            throw new Error("", { cause: req.data });
        }

        return listPokemon;
    } catch (error) {
        throw new Error("", { cause: error?.cause });
    }
};

export const fetchPokemon = async (pkmnId, region = null) => {
    try {
        const regionName = region ? `/${region}` : "";
        const req = await api.get(`/v1/pokemon/${pkmnId}${regionName}`);

        return req.data;
    } catch (error) {
        throw new Error(error);
    }
};

export const fetchAllTypes = async () => {
    try {
        const req = await api.get("/v1/types");

        return req.data;
    } catch (error) {
        throw new Error(error);
    }
};