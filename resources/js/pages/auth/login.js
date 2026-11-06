import "../../../css/pages/auth/login.css"; 


document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    const submitBtn = document.getElementById('submitBtn');
    const inputs = document.querySelectorAll('.enhanced-input');

    form.addEventListener('submit', function(e) {
        submitBtn.classList.add('loading');
        submitBtn.disabled = true;

        setTimeout(() => {
            submitBtn.classList.remove('loading');
            submitBtn.disabled = false;
        }, 5000);
    });

    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'translateY(-1px)';
        });

        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'translateY(0)';
        });

        input.addEventListener('input', function() {
            if (this.type === 'email' && this.value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (emailRegex.test(this.value)) {
                    this.style.borderColor = 'rgba(34, 197, 94, 0.6)';
                } else {
                    this.style.borderColor = 'rgba(239, 68, 68, 0.6)';
                }
            }

            if (this.type === 'password' && this.value) {
                if (this.value.length >= 6) {
                    this.style.borderColor = 'rgba(34, 197, 94, 0.6)';
                } else {
                    this.style.borderColor = 'rgba(239, 68, 68, 0.6)';
                }
            }
        });
    });

    console.log('Login page');
});


function togglePassword() {
    const passwordInput = document.getElementById("password");
    const eyeOpen = document.getElementById("eye-open");
    const eyeClosed = document.getElementById("eye-closed");

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