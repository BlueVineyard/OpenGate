document.querySelectorAll(".ogft-featured-work .fw-card").forEach((card) => {
    const video = card.querySelector(".fw-video");
    const overlayImg = card.querySelector(".fw-overlay-img");

    if (!video) {
        return;
    }

    video.muted = true;

    const playVideo = async () => {
        try {
            await video.play();
            if (overlayImg) {
                overlayImg.style.opacity = "0";
            }
        } catch (e) {
            // Autoplay might be blocked; keep overlay visible.
        }
    };

    const stopVideo = () => {
        video.pause();
        video.currentTime = 0;
        if (overlayImg) {
            overlayImg.style.opacity = "";
        }
    };

    card.addEventListener("mouseenter", playVideo);
    card.addEventListener("mouseleave", stopVideo);
    card.addEventListener("focusin", playVideo);
    card.addEventListener("focusout", stopVideo);
});
