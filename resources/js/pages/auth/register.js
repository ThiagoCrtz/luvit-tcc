import "../../../css/pages/auth/register.css";

document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("registerForm");
    const step1 = document.getElementById("step1");
    const step2 = document.getElementById("step2");
    const nextStepBtn = document.getElementById("nextStepBtn");
    const prevStepBtn = document.getElementById("prevStepBtn");
    const submitBtn = document.getElementById("submitBtn");

    const currentStepDisplay = document.getElementById("currentStepDisplay");
    const stepIndicator1 = document.getElementById("stepIndicator1");
    const stepIndicator2 = document.getElementById("stepIndicator2");

    const bioTextarea = document.getElementById("bio");
    const bioCharCount = document.getElementById("bioCharCount");

    let currentStep = 1;

    const nameInput = document.getElementById("name");
    const arrobaInput = document.getElementById("username");
    const emailInput = document.getElementById("email");
    const passwordInput = document.getElementById("password");
    const confirmPasswordInput = document.getElementById(
        "password_confirmation"
    );

    function updateStepUI() {

        step1.style.display = currentStep === 1 ? "flex" : "none";
        step2.style.display = currentStep === 2 ? "flex" : "none";

        currentStepDisplay.textContent = currentStep;
        stepIndicator1.classList.toggle("active", currentStep >= 1);
        stepIndicator2.classList.toggle("active", currentStep === 2);

        const subtitle = document.querySelector(".welcome-subtitle");
        if (currentStep === 1) {
            subtitle.innerHTML =
                'Passo <span id="currentStepDisplay">1</span> de 2: Dados de acesso.';
        } else {
            subtitle.innerHTML =
                'Passo <span id="currentStepDisplay">2</span> de 2: Detalhes do perfil (Opcional).';
        }
    }

    nextStepBtn.addEventListener("click", function () {
        if (validateStep1()) {
            currentStep = 2;
            updateStepUI();
        } else {
            alert(
                "Por favor, preencha todos os campos obrigatórios corretamente para avançar."
            );
        }
    });

    prevStepBtn.addEventListener("click", function () {
        currentStep = 1;
        updateStepUI();
    });

    /**
     * 
     * @returns {boolean} 
     */
    function validateStep1() {
        let isValid = true;
        const inputsToValidate = [
            nameInput,
            arrobaInput,
            emailInput,
            passwordInput,
            confirmPasswordInput,
        ];

        inputsToValidate.forEach((input) => {
            if (!validateInput(input)) {
                isValid = false;
            }
        });

        return isValid;
    }

    /**
     * 
     * @param {HTMLElement} input
     * @returns {boolean}
     */
    function validateInput(input) {
        const greenBorder = "rgba(34, 197, 94, 0.6)";
        const redBorder = "rgba(239, 68, 68, 0.6)";
        let passes = true;

        if (input.id === "name") {
            passes = input.value.trim().length >= 2;
        } else if (input.id === "username") {
            const userRegex = /^[a-zA-Z0-9_]{3,}$/;
            passes = userRegex.test(input.value);
        } else if (input.id === "email") {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            passes = emailRegex.test(input.value);
        } else if (input.id === "password") {
            passes = input.value.length >= 8;
        } else if (input.id === "password_confirmation") {
            passes = input.value && input.value === passwordInput.value;
        }

        if (input.required || input.value.length > 0) {
            input.style.borderColor = passes ? greenBorder : redBorder;
        } else if (!input.required && input.value.length === 0) {

            input.style.borderColor = "";
        }

        return passes;
    }

    document.querySelectorAll(".enhanced-input").forEach((input) => {

        input.addEventListener("focus", function () {
            this.parentElement.style.transform = "translateY(-1px)";
        });

        input.addEventListener("blur", function () {
            this.parentElement.style.transform = "translateY(0)";
            validateInput(this); 

            if (this.id === "password" || this.id === "password_confirmation") {
                validateInput(passwordInput);
                validateInput(confirmPasswordInput);
            }
        });

        input.addEventListener("input", function () {
            validateInput(this);
            if (this.id === "password" || this.id === "password_confirmation") {
                validateInput(passwordInput);
                validateInput(confirmPasswordInput);
            }
        });
    });

    bioTextarea.addEventListener("input", function () {
        const length = this.value.length;
        bioCharCount.textContent = `${length}/255`;
    });

    form.addEventListener("submit", function (e) {
        if (currentStep === 1) {
            e.preventDefault(); 
            nextStepBtn.click();
            return;
        }

        if (!validateStep1()) {
            e.preventDefault();
            currentStep = 1;
            updateStepUI();
            alert(
                "Houve um erro de validação nos dados de acesso. Por favor, revise o Passo 1."
            );
            return;
        }

        submitBtn.classList.add("loading");
        submitBtn.disabled = true;
        submitBtn.innerText = "Criando...";

    });
    updateStepUI();
    console.log("Register Page Enhanced - Multi-Step Enabled!");
});

function togglePassword(button) {
    const inputGroup = button.parentElement;
    const passwordInput = inputGroup.querySelector("input");
    const eyeOpen = button.querySelector(".eye-open");
    const eyeClosed = button.querySelector(".eye-closed");

    if (!passwordInput || !eyeOpen || !eyeClosed) return;

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        eyeOpen.style.display = "none";
        eyeClosed.style.display = "block";
    } else {
        passwordInput.type = "password";
        eyeOpen.style.display = "block";
        eyeClosed.style.display = "none";
    }
}
window.togglePassword = togglePassword;
