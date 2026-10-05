
function togglePassword(id, button) {
    const input = document.getElementById(id);
    if (!input) return;

    const isPassword = input.type === "password";
    input.type = isPassword ? "text" : "password";

    button.innerHTML = isPassword
        ? `<svg viewBox="0 0 24 24" aria-hidden="true">
             <path fill="currentColor" d="M3 4.2 19.8 21 21 19.8l-3-3c2.4-1.4 4-3.5 5-4.8-1.8-2.6-5.6-6-11-6-1.1 0-2.1.2-3 .5L4.2 3 3 4.2zM8.7 7.1C6.3 8.2 4.5 10.2 3 12c1.8 2.6 5.6 6 11 6 1.1 0 2.2-.2 3.2-.5l-2.5-2.5a4.7 4.7 0 0 1-6.7-6.7L8.7 7.1z"/>
           </svg>`
        : `<svg viewBox="0 0 24 24" aria-hidden="true">
             <path fill="currentColor" d="M2 12s3.7-6 10-6 10 6 10 6-3.7 6-10 6S2 12 2 12zm10 4a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm0-2.2A1.8 1.8 0 1 1 12 10a1.8 1.8 0 0 1 0 3.8z"/>
           </svg>`;

    button.setAttribute("aria-label", isPassword ? "Hide password" : "Show password");
}

function socialDemo(provider) {
    alert(provider + " login is a visual demo only for this laboratory activity.");
}

document.addEventListener("DOMContentLoaded", () => {
    const forms = document.querySelectorAll("form");

    forms.forEach(form => {
        form.addEventListener("submit", event => {
            const password = form.querySelector('input[name="password"]');
            const confirmPassword = form.querySelector('input[name="confirm_password"]');

            if (confirmPassword && password && password.value !== confirmPassword.value) {
                event.preventDefault();
                alert("Passwords do not match.");
                confirmPassword.focus();
                return;
            }

            if (password && password.value.length < 8) {
                event.preventDefault();
                alert("Password must be at least 8 characters.");
                password.focus();
            }
        });
    });
});
