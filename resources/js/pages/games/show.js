import { setupMediaActions } from '../../media-actions.js';
import "../../../css/pages/games/show.css";

document.addEventListener("DOMContentLoaded", () => {
    setupMediaActions();
    if (typeof gsap !== "undefined") {
    gsap.from(".poster-frame", {
        y: 30,
        opacity: 0,
        duration: 0.8,
        ease: "power2.out",
    });
    gsap.from(".luv-card", {
        y: 30,
        opacity: 0,
        duration: 0.8,
        delay: 0.08,
        ease: "power2.out",
    });
    gsap.from("h1", {
        y: 20,
        opacity: 0,
        duration: 0.8,
        delay: 0.15,
        ease: "power2.out",
    });

    }
    document.querySelectorAll(".luv-btn").forEach((btn) => {
        btn.addEventListener("mousemove", (e) => {
            const r = btn.getBoundingClientRect();
            btn.style.setProperty("--x", e.clientX - r.left + "px");
            btn.style.setProperty("--y", e.clientY - r.top + "px");
        });
    });

    const poster = document.querySelector(".poster-image");
    const detailsContainer = document.querySelector(".luv-card");
    if (poster && detailsContainer && window.innerWidth >= 1024) {
        function adjustPosterHeight() {
            const detailsHeight = detailsContainer.offsetHeight + 140;
            const minHeight = 460;
            poster.style.height =
                detailsHeight > minHeight
                    ? `${detailsHeight}px`
                    : `${minHeight}px`;
        }
        setTimeout(adjustPosterHeight, 350);
        window.addEventListener("resize", adjustPosterHeight);
    }

    window.scrollToReviews = function () {
        const reviewsSection = document.getElementById("reviews-section");
        if (reviewsSection) {
            reviewsSection.scrollIntoView({ behavior: "smooth" });
            setTimeout(() => {
                document.getElementById("review")?.focus();
            }, 500);
        }
    };

    if (typeof gsap !== "undefined") {
        const io = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        gsap.to(entry.target, {
                            y: 0,
                            opacity: 1,
                            duration: 0.5,
                            ease: "power2.out",
                        });
                        io.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.15 }
        );

        document.querySelectorAll(".review-item").forEach((el) => {
            gsap.set(el, { y: 20, opacity: 0 });
            io.observe(el);
        });
    }

});
