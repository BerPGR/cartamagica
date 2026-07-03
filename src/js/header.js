import { jwtDecode } from "jwt-decode";

const btnLogin = document.getElementById('btn-login');
const firstAnchor = document.getElementById('option1')
const secondAnchor = document.getElementById('option2')

document.addEventListener('DOMContentLoaded', () => {
    checkJwtToken()
});

function checkJwtToken() {
    const token = localStorage.getItem('token');
    const publicRoutes = ['/', '/login']
    
    if (!token && window.location.pathname !== '/') {
        window.location.href = '/'
    };
    
    try {
        const decoded = jwtDecode(token);
        const now = Date.now();
        const isValid = decoded.exp * 1000 > now;
    
        if (isValid) {
            btnLogin?.classList.add('hidden');
            if (publicRoutes.includes(window.location.pathname)) {
                window.location.href = '/home';
            }
        } else {
            localStorage.removeItem('token'); 
            if (window.location.pathname !== '/' && window.location.pathname !== '/login') {
                window.location.href = '/';
            }
        }
    } catch (e) {
        localStorage.removeItem('token');
    }
}