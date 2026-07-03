import { jwtDecode } from "jwt-decode";

const btnLogin = document.getElementById('btn-login');
const firstAnchor = document.getElementById('option1')
const secondAnchor = document.getElementById('option2')

document.addEventListener('DOMContentLoaded', () => {
    checkJwtToken()
    loadAnchors()
});

function loadAnchors() {
    const location = window.location.pathname

    if (location !== '/') {
        firstAnchor.textContent = 'Home'
        firstAnchor.setAttribute('href', "/home")
        
        secondAnchor.textContent = 'Minhas cartas'
        secondAnchor.setAttribute('href', '/cartas')

    } else {
        firstAnchor.textContent = 'Como funciona'
        firstAnchor.setAttribute('href', "#como-funciona")
        
        secondAnchor.textContent = 'Benefícios'
        secondAnchor.setAttribute('href', '#beneficios')
    }
}

function checkJwtToken() {
    const token = localStorage.getItem('token');
    
    if (!token && window.location.pathname !== '/') {
        window.location.href = '/'
    };
    
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
            if (window.location.pathname !== '/' && window.location.pathname !== '/login') {
                window.location.href = '/';
            }
        }
    } catch (e) {
        localStorage.removeItem('token');
    }
}