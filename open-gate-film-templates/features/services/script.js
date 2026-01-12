document.querySelectorAll(".ogft-services").forEach((section) => {
    if (typeof gsap === "undefined" || typeof ScrollTrigger === "undefined") return;

    const list = section.querySelector(".services__list");
    const cards = Array.from(section.querySelectorAll(".service-card"));
    if (!list || cards.length === 0) return;

    const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    if (reduceMotion) return;

    gsap.registerPlugin(ScrollTrigger);

    const tl = gsap.timeline({
        scrollTrigger: {
            trigger: section,
            start: "top center",
            end: "+=150%",
            scrub: true,
            pin: true,
            anticipatePin: 1,
        },
    });

    cards.forEach((card, i) => {
        tl.from(card, {
            opacity: 0,
            y: 40,
            duration: 0.6,
        }, i * 0.3);
    });
});
