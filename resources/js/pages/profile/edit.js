document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("editProfileForm");
    const nameInput = document.getElementById("name");
    const bioTextarea = document.getElementById("bio");
    const bioCounter = document.getElementById("bio-counter");
    const profileInput = document.getElementById("profile_picture");
    const submitBtn = document.getElementById("submitBtn");

    const currentPasswordInput = document.getElementById("current_password");
    const newPasswordInput = document.getElementById("new_password");
    const newPasswordConfirmInput = document.getElementById(
        "new_password_confirmation"
    );

    if (profileInput) {
        profileInput.addEventListener("change", function () {
            const file = this.files[0];

            if (!file) return;

            if (file.size > 5 * 1024 * 1024) {
                showNotification("Imagem muito grande. Máximo 5MB.", "error");
                this.value = "";
                return;
            }

            if (!file.type.startsWith("image/")) {
                showNotification("Selecione apenas imagens.", "error");
                this.value = "";
                return;
            }

            const reader = new FileReader();

            reader.onload = function (e) {
                const profileLabel = document.querySelector(".photo-preview");
                const oldPreview = document.getElementById("imagePreview");

                if (oldPreview) oldPreview.remove();

                const newImg = document.createElement("img");
                newImg.id = "imagePreview";
                newImg.src = e.target.result;
                newImg.alt = "Preview";
                newImg.className = "photo-img";
                newImg.style.opacity = "0";

                const overlay = profileLabel.querySelector(".photo-overlay");
                profileLabel.insertBefore(newImg, overlay);

                setTimeout(() => {
                    newImg.style.transition = "opacity 0.3s";
                    newImg.style.opacity = "1";
                }, 50);
            };

            reader.readAsDataURL(file);
        });
    }

    function setupRealTimeValidation() {
        if (nameInput) {
            nameInput.addEventListener("input", function () {
                if (this.value.trim().length >= 2) {
                    this.classList.remove("invalid");
                    this.classList.add("valid");
                } else {
                    this.classList.remove("valid");
                    this.classList.add("invalid");
                }
            });
        }
        if (bioTextarea && bioCounter) {
            bioTextarea.addEventListener("input", function () {
                const length = this.value.length;
                bioCounter.textContent = `${length}/500 caracteres`;

                bioCounter.className = "char-counter";
                if (length > 450) {
                    bioCounter.classList.add("warning");
                }
                if (length >= 500) {
                    bioCounter.classList.add("error");
                }
            });
        }

        if (newPasswordInput) {
            newPasswordInput.addEventListener("input", function () {
                if (this.value.length > 0) {
                    updatePasswordStrength(this.value);

                    if (this.value.length >= 8) {
                        this.classList.remove("invalid");
                        this.classList.add("valid");
                    } else {
                        this.classList.remove("valid");
                        this.classList.add("invalid");
                    }

                    if (
                        newPasswordConfirmInput &&
                        newPasswordConfirmInput.value
                    ) {
                        if (this.value === newPasswordConfirmInput.value) {
                            newPasswordConfirmInput.classList.remove("invalid");
                            newPasswordConfirmInput.classList.add("valid");
                        } else {
                            newPasswordConfirmInput.classList.remove("valid");
                            newPasswordConfirmInput.classList.add("invalid");
                        }
                    }
                } else {
                    document
                        .querySelectorAll(".strength-bar")
                        .forEach((bar) => {
                            bar.className = "strength-bar";
                        });
                    this.classList.remove("valid", "invalid");
                }
            });
        }

        if (newPasswordConfirmInput && newPasswordInput) {
            newPasswordConfirmInput.addEventListener("input", function () {
                if (newPasswordInput.value.length > 0) {
                    if (
                        this.value === newPasswordInput.value &&
                        this.value.length >= 8
                    ) {
                        this.classList.remove("invalid");
                        this.classList.add("valid");
                    } else {
                        this.classList.remove("valid");
                        this.classList.add("invalid");
                    }
                }
            });
        }
    }

    function updatePasswordStrength(password) {
        const strengthBars = document.querySelectorAll(".strength-bar");
        let strength = 0;

        strengthBars.forEach((bar) => {
            bar.className = "strength-bar";
        });

        if (password.length >= 8) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password) || /[^A-Za-z0-9]/.test(password)) strength++;

        for (let i = 0; i < strength; i++) {
            if (strength <= 1) {
                strengthBars[i].classList.add("weak");
            } else if (strength <= 2) {
                strengthBars[i].classList.add("medium");
            } else {
                strengthBars[i].classList.add("strong");
            }
        }
    }

    if (form) {
        form.addEventListener("submit", function (e) {
            let isValid = true;
            let errors = [];

            // Validar nome
            if (nameInput && nameInput.value.trim().length < 2) {
                errors.push("Nome deve ter pelo menos 2 caracteres");
                isValid = false;
            }

            // Validar senha
            if (newPasswordInput && newPasswordInput.value.length > 0) {
                if (currentPasswordInput && !currentPasswordInput.value) {
                    errors.push("Informe a senha atual para alterar a senha");
                    isValid = false;
                }

                if (newPasswordInput.value.length < 8) {
                    errors.push("Nova senha deve ter pelo menos 8 caracteres");
                    isValid = false;
                }

                if (
                    newPasswordConfirmInput &&
                    newPasswordInput.value !== newPasswordConfirmInput.value
                ) {
                    errors.push("As senhas não conferem");
                    isValid = false;
                }
            }

            if (!isValid) {
                e.preventDefault();
                showNotification(errors.join(" • "), "error");
                return false;
            }

            if (submitBtn) {
                submitBtn.classList.add("loading");
                submitBtn.disabled = true;
                setTimeout(() => {
                    if (submitBtn) {
                        submitBtn.classList.remove("loading");
                        submitBtn.disabled = false;
                    }
                }, 10000); 
            }
        });
    }

    function showNotification(message, type = "info") {
        const existingNotifications = document.querySelectorAll(
            ".notification-toast"
        );
        existingNotifications.forEach((notif) => notif.remove());

        let bgColor = "bg-blue-500";
        let icon = `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>`;

        if (type === "error") {
            bgColor = "bg-red-500";
            icon = `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>`;
        } else if (type === "success") {
            bgColor = "bg-green-500";
            icon = `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>`;
        }

        const notification = document.createElement("div");
        notification.className = `notification-toast fixed top-4 right-4 ${bgColor} text-white px-6 py-4 rounded-xl shadow-2xl z-50 max-w-sm transition-all duration-300 transform translate-x-full`;
        notification.innerHTML = `
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 mt-0.5">
                    ${icon}
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium leading-relaxed">${message}</p>
                </div>
                <button class="flex-shrink-0 ml-2 hover:bg-white/20 rounded-lg p-1 transition-colors" onclick="this.parentElement.parentElement.remove()">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.classList.remove("translate-x-full");
        }, 100);

        setTimeout(() => {
            notification.classList.add("translate-x-full");
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }
    setupRealTimeValidation();

});
