@extends('layouts.app')
@section('title', 'Editar Perfil - Luvit')

@section('content')

<section class="edit-profile-section">
    <div class="container">
        <div class="header-section">
            <a href="{{ route('profile.show', $user->arroba) }}" class="back-button">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="page-title">Editar Perfil</h1>
                <p class="page-subtitle">Atualize suas informações pessoais</p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <strong>Ops! Corrija os seguintes erros:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update', $user->arroba) }}" enctype="multipart/form-data" id="editProfileForm">
            @csrf
            @method('PUT')

            <div class="profile-photo-section">
                <input type="file" name="profile_picture" id="profile_picture" accept="image/*" class="file-input">
                
                <label for="profile_picture" class="photo-preview">
                    @if($user->profile_picture)
                        <img id="imagePreview" 
                            src="{{ $user->profile_picture_url }}" 
                            alt="Profile Preview" 
                            class="photo-img">
                    @else
                        <div id="imagePreview" class="photo-placeholder">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="photo-overlay">
                        <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>Alterar foto</span>
                        <span class="photo-hint">Máximo 5MB</span>
                    </div>
                </label>
            </div>

            <div class="form-section">
                <div class="form-group">
                    <label for="name" class="form-label">Nome Completo</label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        class="form-input"
                        placeholder="Seu nome completo"
                        value="{{ old('name', $user->name) }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="bio" class="form-label">Sobre Você</label>
                    <textarea
                        name="bio"
                        id="bio"
                        class="form-textarea"
                        placeholder="Conte um pouco sobre você..."
                        rows="4"
                        maxlength="500"
                    >{{ old('bio', $user->bio) }}</textarea>
                    <div class="char-counter" id="bio-counter">{{ strlen($user->bio ?? '') }}/500</div>
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">Alterar Senha</h3>
                <p class="section-hint">Deixe em branco se não quiser alterar</p>

                <div class="form-group">
                    <label for="current_password" class="form-label">Senha Atual</label>
                    <input
                        type="password"
                        name="current_password"
                        id="current_password"
                        class="form-input"
                        placeholder="••••••••"
                    >
                </div>

                <div class="form-group">
                    <label for="new_password" class="form-label">Nova Senha</label>
                    <input
                        type="password"
                        name="new_password"
                        id="new_password"
                        class="form-input"
                        placeholder="••••••••"
                        minlength="8"
                    >
                    <div class="password-strength" id="password-strength">
                        <div class="strength-bar"></div>
                        <div class="strength-bar"></div>
                        <div class="strength-bar"></div>
                        <div class="strength-bar"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="new_password_confirmation" class="form-label">Confirmar Nova Senha</label>
                    <input
                        type="password"
                        name="new_password_confirmation"
                        id="new_password_confirmation"
                        class="form-input"
                        placeholder="••••••••"
                    >
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('profile.show', $user->arroba) }}" class="btn-cancel">
                    Cancelar
                </a>
                <button type="submit" id="submitBtn" class="btn-submit">
                    <span>Salvar Alterações</span>
                </button>
            </div>
        </form>
    </div>
</section>

<style>
* {
    margin: 0;
    box-sizing: border-box;
}

.edit-profile-section {
    min-height: 100vh;
    background: #0f0f0f;
    padding: 40px 20px;
}

.container {
    max-width: 700px;
    margin: 0 auto;
}

.header-section {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 32px;
}

.back-button {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: #1a1a1a;
    border: 1px solid #2a2a2a;
    border-radius: 8px;
    color: #e11d48;
    text-decoration: none;
    transition: all 0.2s;
}

.back-button:hover {
    background: #222;
    border-color: #e11d48;
}

.page-title {
    font-size: 28px;
    font-weight: 600;
    color: #fff;
    margin-bottom: 4px;
}

.page-subtitle {
    font-size: 14px;
    color: #888;
}

.alert {
    display: flex;
    align-items: start;
    gap: 12px;
    padding: 16px;
    border-radius: 8px;
    margin-bottom: 24px;
    font-size: 14px;
}

.alert-success {
    background: rgba(16, 185, 129, 0.1);
    border: 1px solid rgba(16, 185, 129, 0.3);
    color: #10b981;
}

.alert-error {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #ef4444;
}

.alert ul {
    list-style: none;
    margin-top: 8px;
}

.alert li {
    margin-top: 4px;
}

.profile-photo-section {
    display: flex;
    justify-content: center;
    margin-bottom: 40px;
}

.file-input {
    display: none;
}

.photo-preview {
    position: relative;
    width: 160px;
    height: 160px;
    border-radius: 50%;
    overflow: hidden;
    cursor: pointer;
    border: 3px solid #1a1a1a;
    transition: all 0.3s;
}

.photo-preview:hover {
    border-color: #e11d48;
}

.photo-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.photo-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #e11d48, #fb7185);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 48px;
    font-weight: 600;
    color: white;
}

.photo-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.8);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s;
    color: white;
    gap: 4px;
}

.photo-preview:hover .photo-overlay {
    opacity: 1;
}

.photo-overlay span:first-of-type {
    font-size: 14px;
    font-weight: 500;
}

.photo-hint {
    font-size: 12px;
    color: #aaa;
}

.form-section {
    background: #1a1a1a;
    border: 1px solid #2a2a2a;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 20px;
}

.section-title {
    font-size: 16px;
    font-weight: 600;
    color: #fff;
    margin-bottom: 8px;
}

.section-hint {
    font-size: 13px;
    color: #666;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group:last-child {
    margin-bottom: 0;
}

.form-label {
    display: block;
    font-size: 13px;
    font-weight: 500;
    color: #ccc;
    margin-bottom: 8px;
}

.form-input,
.form-textarea {
    width: 100%;
    padding: 12px 16px;
    background: #0f0f0f;
    border: 1px solid #2a2a2a;
    border-radius: 8px;
    color: #fff;
    font-size: 14px;
    font-family: inherit;
    transition: all 0.2s;
    outline: none;
}

.form-input:focus,
.form-textarea:focus {
    background: #1a1a1a;
    border-color: #e11d48;
}

.form-input.valid {
    border-color: #10b981;
}

.form-input.invalid {
    border-color: #ef4444;
}

.form-textarea {
    min-height: 100px;
    resize: vertical;
}

.char-counter {
    font-size: 12px;
    color: #666;
    text-align: right;
    margin-top: 6px;
}

.char-counter.warning {
    color: #f59e0b;
}

.char-counter.error {
    color: #ef4444;
}

.password-strength {
    display: flex;
    gap: 6px;
    margin-top: 8px;
}

.strength-bar {
    flex: 1;
    height: 3px;
    background: #2a2a2a;
    border-radius: 2px;
    transition: all 0.3s;
}

.strength-bar.weak {
    background: #ef4444;
}

.strength-bar.medium {
    background: #f59e0b;
}

.strength-bar.strong {
    background: #10b981;
}

.form-actions {
    display: flex;
    gap: 12px;
    margin-top: 24px;
}

.btn-cancel,
.btn-submit {
    flex: 1;
    padding: 14px 24px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    text-align: center;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-cancel {
    background: #1a1a1a;
    border: 1px solid #2a2a2a;
    color: #fff;
}

.btn-cancel:hover {
    background: #222;
}

.btn-submit {
    background-color: #e11d48;
    color: white;
    border: none;
}

.btn-submit:hover {
    transform: translateY(-1px);
}

.btn-submit.loading {
    position: relative;
    color: transparent;
}

.btn-submit.loading::after {
    content: "";
    position: absolute;
    width: 18px;
    height: 18px;
    top: 50%;
    left: 50%;
    margin-left: -9px;
    margin-top: -9px;
    border: 2px solid white;
    border-radius: 50%;
    border-top-color: transparent;
    animation: spin 0.6s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

@media (max-width: 640px) {
    .edit-profile-section {
        padding: 20px 16px;
    }

    .page-title {
        font-size: 24px;
    }

    .form-section {
        padding: 20px;
    }

    .photo-preview {
        width: 140px;
        height: 140px;
    }

    .photo-placeholder {
        font-size: 40px;
    }

    .form-actions {
        flex-direction: column;
    }
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("editProfileForm");
    const nameInput = document.getElementById("name");
    const bioTextarea = document.getElementById("bio");
    const bioCounter = document.getElementById("bio-counter");
    const profileInput = document.getElementById("profile_picture");
    const submitBtn = document.getElementById("submitBtn");
    const currentPasswordInput = document.getElementById("current_password");
    const newPasswordInput = document.getElementById("new_password");
    const newPasswordConfirmInput = document.getElementById("new_password_confirmation");

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
                bioCounter.textContent = `${length}/500`;

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

                    if (newPasswordConfirmInput && newPasswordConfirmInput.value) {
                        if (this.value === newPasswordConfirmInput.value) {
                            newPasswordConfirmInput.classList.remove("invalid");
                            newPasswordConfirmInput.classList.add("valid");
                        } else {
                            newPasswordConfirmInput.classList.remove("valid");
                            newPasswordConfirmInput.classList.add("invalid");
                        }
                    }
                } else {
                    document.querySelectorAll(".strength-bar").forEach((bar) => {
                        bar.className = "strength-bar";
                    });
                    this.classList.remove("valid", "invalid");
                }
            });
        }

        if (newPasswordConfirmInput && newPasswordInput) {
            newPasswordConfirmInput.addEventListener("input", function () {
                if (newPasswordInput.value.length > 0) {
                    if (this.value === newPasswordInput.value && this.value.length >= 8) {
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

            if (nameInput && nameInput.value.trim().length < 2) {
                errors.push("Nome deve ter pelo menos 2 caracteres");
                isValid = false;
            }

            if (newPasswordInput && newPasswordInput.value.length > 0) {
                if (currentPasswordInput && !currentPasswordInput.value) {
                    errors.push("Informe a senha atual para alterar a senha");
                    isValid = false;
                }

                if (newPasswordInput.value.length < 8) {
                    errors.push("Nova senha deve ter pelo menos 8 caracteres");
                    isValid = false;
                }

                if (newPasswordConfirmInput && newPasswordInput.value !== newPasswordConfirmInput.value) {
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
        const existingNotifications = document.querySelectorAll(".notification-toast");
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
        notification.className = `notification-toast fixed ${bgColor}`;
        notification.style.cssText = "position: fixed; top: 1rem; right: 1rem; background: " + (type === "error" ? "#ef4444" : type === "success" ? "#10b981" : "#3b82f6") + "; color: white; padding: 1rem 1.5rem; border-radius: 0.75rem; box-shadow: 0 10px 40px rgba(0,0,0,0.3); z-index: 9999; max-width: 24rem; transform: translateX(calc(100% + 2rem)); transition: transform 0.3s ease;";
        
        notification.innerHTML = `
            <div style="display: flex; align-items: start; gap: 0.75rem;">
                <div style="flex-shrink: 0; margin-top: 0.125rem;">
                    ${icon}
                </div>
                <div style="flex: 1;">
                    <p style="font-size: 0.875rem; font-weight: 500; line-height: 1.5;">${message}</p>
                </div>
                <button style="flex-shrink: 0; margin-left: 0.5rem; background: rgba(255,255,255,0.2); border: none; border-radius: 0.5rem; padding: 0.25rem; cursor: pointer; transition: background 0.2s;" onclick="this.parentElement.parentElement.remove()">
                    <svg style="width: 1rem; height: 1rem;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.transform = "translateX(0)";
        }, 100);

        setTimeout(() => {
            notification.style.transform = "translateX(calc(100% + 2rem))";
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }

    setupRealTimeValidation();
});
</script>

@endsection