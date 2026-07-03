import { jwtDecode } from "jwt-decode";

const btnLogin = document.getElementById('btn-login');
document.addEventListener('DOMContentLoaded', () => {
    const token = localStorage.getItem('token');

    if (!token) return;

    try {
        const decoded = jwtDecode(token);
        const now = Date.now();
        const isValid = decoded.exp * 1000 > now;

        if (isValid) {
            btnLogin?.classList.add('hidden');
            if (window.location.pathname !== '/home') {
                window.location.href = '/home';
            }
        } else {
            localStorage.removeItem('token'); 
            if (window.location.pathname !== '/welcome' && window.location.pathname !== '/login') {
                window.location.href = '/welcome';
            }
        }
    } catch (e) {
        localStorage.removeItem('token');
    }
});
