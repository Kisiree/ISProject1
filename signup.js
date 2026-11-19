const password = document.getElementById('new-password');
const toggle = document.getElementById('show-password');
const guidance = document.getElementById('password-guidance');

toggle.hidden = false;
toggle.addEventListener('click', () => {
    const isHidden = password.type === 'password';
    password.type = isHidden ? 'text' : 'password';
    toggle.textContent = isHidden ? 'Hide password' : 'Show password';
});

password.addEventListener('input', () => {
    const missing = [];
    if (Array.from(password.value).length < 8) missing.push('at least 8 characters');
    if (new TextEncoder().encode(password.value).length > 72) missing.push('at most 72 bytes');
    if (!/[A-Z]/.test(password.value)) missing.push('an uppercase letter');
    if (!/[0-9]/.test(password.value)) missing.push('a number');
    guidance.textContent = missing.length
        ? 'Still needed: ' + missing.join(', ') + '.'
        : 'Password meets the requirements.';
});
