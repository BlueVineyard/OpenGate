(function () {
    if (!window.ogftWorkModalData || !ogftWorkModalData.item) return;

    const data = ogftWorkModalData.item;
    const root = document.getElementById("ogft-work-modal-root");
    if (!root) return;

    const createPlayer = () => {
        const mediaWrap = document.createElement("div");
        mediaWrap.className = "ogft-work-modal__media";

        let element;

        if (data.video_type === "youtube" || data.video_type === "vimeo") {
            element = document.createElement("div");
            element.className = "ogft-plyr-embed";
            element.setAttribute("data-plyr-provider", data.video_type);
            element.setAttribute("data-plyr-embed-id", data.embed_id || "");
        } else {
            element = document.createElement("video");
            element.className = "ogft-work-modal__video plyr__video-embed";
            element.playsInline = true;
            element.controls = false;
            element.preload = "metadata";
            if (data.poster_src) element.poster = data.poster_src;
            const source = document.createElement("source");
            source.src = data.video_src || data.embed_src || "";
            source.type = "video/mp4";
            element.appendChild(source);
        }

        mediaWrap.appendChild(element);

        const controls = [
            "play-large",
            "play",
            "progress",
            "current-time",
            "mute",
            "volume",
            "settings",
            "pip",
            "fullscreen",
        ];

        // eslint-disable-next-line no-undef
        new Plyr(element, {
            autoplay: false,
            controls,
            settings: ["quality", "speed", "loop"],
            invertTime: false,
            resetOnEnd: true,
            tooltips: { controls: true, seek: true },
            keyboard: { focused: true, global: false },
            clickToPlay: true,
            ratio: "16:9",
            vimeo: { dnt: true },
            youtube: { rel: 0, showinfo: 0, iv_load_policy: 3, modestbranding: 1 },
        });

        return mediaWrap;
    };

    const clearQueryParam = () => {
        const url = new URL(window.location.href);
        if (!url.searchParams.has("ogft_work")) {
            return false;
        }
        url.searchParams.delete("ogft_work");
        window.history.replaceState({}, "", url.toString());
        return true;
    };

    const render = () => {
        const backdrop = document.createElement("div");
        backdrop.className = "ogft-work-modal-backdrop";

        const modal = document.createElement("div");
        modal.className = "ogft-work-modal";

        const topbar = document.createElement("div");
        topbar.className = "ogft-work-modal__topbar";
        topbar.innerHTML = `
            <div class="ogft-work-modal__brand">${(ogftWorkModalData.strings?.brand || "Open Gate").toUpperCase()}</div>
            <div class="ogft-work-modal__actions"></div>
        `;

        const actions = topbar.querySelector(".ogft-work-modal__actions");
        if (ogftWorkModalData.ctaUrl) {
            const cta = document.createElement("a");
            cta.className = "ogft-btn";
            cta.href = ogftWorkModalData.ctaUrl;
            cta.textContent = ogftWorkModalData.strings?.ctaLabel || "Request a quote";
            actions.appendChild(cta);
        }
        const closeBtn = document.createElement("button");
        closeBtn.type = "button";
        closeBtn.className = "ogft-btn ogft-btn--ghost";
        closeBtn.textContent = "Close";
        actions.appendChild(closeBtn);

        const media = createPlayer();

        const meta = document.createElement("div");
        meta.className = "ogft-work-modal__meta";
        meta.innerHTML = `
            <div>
                ${data.kicker ? `<p class="ogft-work-modal__kicker">${data.kicker}</p>` : ""}
                <h3 class="ogft-work-modal__title">${data.title || ""}</h3>
            </div>
            <p class="ogft-work-modal__description">${data.description || ""}</p>
        `;

        modal.appendChild(topbar);
        modal.appendChild(media);
        modal.appendChild(meta);
        backdrop.appendChild(modal);
        root.appendChild(backdrop);

        const close = () => {
            const removed = clearQueryParam();
            if (window.history.length > 1) {
                window.history.back();
            }
            backdrop.remove();
        };

        closeBtn.addEventListener("click", close);
        backdrop.addEventListener("click", (e) => {
            if (e.target === backdrop) {
                close();
            }
        });
        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape") close();
        });
    };

    render();
})();
