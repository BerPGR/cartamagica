import { jwtDecode } from "jwt-decode"

const cardTitle = document.querySelector('card-title')
const btnLogout = document.getElementById('btn-logout')

document.addEventListener('DOMContentLoaded', () => {
    const token = localStorage.getItem('token')

    const decode = jwtDecode(token)
    console.log(decode)

    cardTitle.textContent = decode.name
})

btnLogout.addEventListener('click', (event) => {
    event.preventDefault()

    localStorage.removeItem('token')

    window.location.href = '/'
})