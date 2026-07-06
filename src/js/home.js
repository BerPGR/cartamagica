import { jwtDecode } from "jwt-decode"

const cardTitle = document.querySelector('.card-title')
const btnLogout = document.getElementById('btn-logout')

document.addEventListener('DOMContentLoaded', async () => {
    const token = localStorage.getItem('token')
    const decode = jwtDecode(token)

    cardTitle.textContent = decode.name

    const response = await fetch(`/cartas/user/${decode.sub}`, {
        method: "GET"
    })

    const data = await response.json().catch(() => null)

    console.log(data)
})

btnLogout.addEventListener('click', (event) => {
    event.preventDefault()

    localStorage.removeItem('token')

    window.location.href = '/'
})