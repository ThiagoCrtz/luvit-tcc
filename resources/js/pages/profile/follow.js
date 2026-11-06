
document.addEventListener("DOMContentLoaded", function () {
    const followButtons = document.querySelectorAll(
        "#followBtn, .mini-follow-btn"
    );

    followButtons.forEach((followBtn) => {
        followBtn.addEventListener("click", async function () {
            const arroba = this.dataset.arroba;

            this.disabled = true; 

            try {
                const response = await fetch(`/profile/${arroba}/follow`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,
                        Accept: "application/json",
                    },
                });

                const data = await response.json();

                if (data.success) {
                    const isFollowing = data.isFollowing;

                    this.dataset.following = isFollowing ? "true" : "false";

                    if (this.classList.contains("mini-follow-btn")) {
                        this.textContent = isFollowing ? "Seguindo" : "Seguir";

                        if (isFollowing) {
                            this.classList.remove(
                                "bg-rose-600",
                                "hover:bg-rose-700"
                            );
                            this.classList.add(
                                "bg-gray-700",
                                "hover:bg-gray-600"
                            );
                        } else {
                            this.classList.remove(
                                "bg-gray-700",
                                "hover:bg-gray-600"
                            );
                            this.classList.add(
                                "bg-rose-600",
                                "hover:bg-rose-700"
                            );
                        }
                    } else {
                        const followText = this.querySelector(".follow-text");
                        if (followText) {
                            followText.textContent = isFollowing
                                ? "Seguindo"
                                : "Seguir";
                        }

                        if (isFollowing) {
                            this.classList.remove(
                                "bg-rose-600",
                                "hover:bg-rose-700"
                            );
                            this.classList.add(
                                "bg-gray-700",
                                "hover:bg-gray-600",
                                "border-gray-600"
                            );
                        } else {
                            this.classList.remove(
                                "bg-gray-700",
                                "hover:bg-gray-600",
                                "border-gray-600"
                            );
                            this.classList.add(
                                "bg-rose-600",
                                "hover:bg-rose-700"
                            );
                        }

                        const followersCount =
                            document.getElementById("followersCount");
                        if (followersCount) {
                            followersCount.textContent = data.followersCount;
                        }
                    }
                }
            } catch (error) {
                console.error("Erro:", error);
                alert("Ocorreu um erro. Tente novamente.");
            } finally {
                this.disabled = false;
            }
        });
    });
});
