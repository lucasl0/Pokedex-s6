/**
 * tcg-wavesurfer-snippet.js
 * Blocs à intégrer dans displayModal() de pokemon-modal.js
 * ─────────────────────────────────────────────────────────
 * IMPORT à ajouter en haut de pokemon-modal.js :
 *   import { fetchTCGCards } from "./api/tcgdex.js";
 *
 * Ces deux blocs doivent être collés dans displayModal(),
 * APRÈS le bloc qui gère listGamesEl.
 */

// ── BLOC 1 : Cartes TCG ───────────────────────────────────
export const renderTCGCards = async (newModalDom, pkmnData) => {
    if (!newModalDom.tcgDetails) return;

    const pkmnNameEN = pkmnData.name?.en?.toLowerCase() ?? pkmnData.name.fr.toLowerCase();
    const tcgCards = await fetchTCGCards(pkmnNameEN);

    if (tcgCards.length === 0) {
        newModalDom.tcgDetails.disabled = true;
        newModalDom.tcgDetails.setAttribute("aria-disabled", "true");
        if (newModalDom.nbTcgCards) newModalDom.nbTcgCards.textContent = "0 carte";
        return;
    }

    newModalDom.tcgDetails.disabled = false;
    newModalDom.tcgDetails.removeAttribute("aria-disabled");
    if (newModalDom.nbTcgCards) {
        newModalDom.nbTcgCards.textContent = `${tcgCards.length} carte${tcgCards.length > 1 ? "s" : ""}`;
    }

    if (!newModalDom.listTcgCards) return;
    newModalDom.listTcgCards.innerHTML = "";

    tcgCards.forEach((card) => {
        const li = document.createElement("li");
        li.className = "tcg-card-item";

        const img = document.createElement("img");
        img.src = card.image ? `${card.image}/low.webp` : "";
        img.alt = `Carte ${card.name ?? pkmnData.name.fr} - ${card.localId ?? ""}`;
        img.loading = "lazy";
        img.width = 100;
        img.height = 140;
        img.className = "tcg-card-img";

        img.addEventListener("click", () => {
            const detail = document.createElement("dialog");
            detail.className = "tcg-card-detail-dialog";
            detail.innerHTML = `
                <button autofocus aria-label="Fermer" class="tcg-dialog-close">✕</button>
                <img src="${card.image ? `${card.image}/high.webp` : ""}"
                     alt="Carte ${card.name ?? pkmnData.name.fr}"
                     width="300" height="420" />
                <p><strong>${card.name ?? ""}</strong> — ${card.localId ?? ""}</p>
            `;
            detail.querySelector(".tcg-dialog-close").addEventListener("click", () => detail.close());
            document.body.appendChild(detail);
            detail.showModal();
            detail.addEventListener("close", () => detail.remove());
        });

        li.appendChild(img);
        newModalDom.listTcgCards.appendChild(li);
    });
};

// ── BLOC 2 : Wavesurfer ───────────────────────────────────
export const renderWavesurfer = (newModalDom, pkmnData, pkmnExtraData, wavesurfer, WaveSurfer) => {
    const cryUrl = pkmnExtraData?.cries?.latest ?? pkmnExtraData?.cries?.legacy ?? null;

    if (!newModalDom.cryContainer) return wavesurfer;

    newModalDom.cryContainer.innerHTML = "";

    if (wavesurfer) {
        wavesurfer.destroy();
        wavesurfer = null;
    }

    if (!cryUrl) {
        newModalDom.cryContainer.innerHTML = `<p class="text-sm text-gray-400">Aucun cri disponible pour ce Pokémon.</p>`;
        if (newModalDom.playCryBtn) newModalDom.playCryBtn.disabled = true;
        return null;
    }

    const waveDiv = document.createElement("div");
    waveDiv.id = "waveform";
    waveDiv.setAttribute("aria-label", `Spectre sonore du cri de ${pkmnData.name.fr}`);
    newModalDom.cryContainer.appendChild(waveDiv);

    wavesurfer = WaveSurfer.create({
        container: "#waveform",
        waveColor: "oklch(0.65 0.15 220)",
        progressColor: "oklch(0.45 0.2 220)",
        height: 60,
        barWidth: 2,
        barGap: 1,
        barRadius: 2,
        url: cryUrl,
        backend: "WebAudio",
    });

    if (newModalDom.playCryBtn) {
        const handler = () => {
            wavesurfer.isPlaying() ? wavesurfer.stop() : wavesurfer.play();
        };
        newModalDom.playCryBtn.replaceWith(newModalDom.playCryBtn.cloneNode(true));
        newModalDom.playCryBtn = document.querySelector("[data-play-cry]");
        newModalDom.playCryBtn.disabled = false;
        newModalDom.playCryBtn.addEventListener("click", handler);
    }

    return wavesurfer;
};
