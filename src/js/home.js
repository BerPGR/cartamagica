import { jwtDecode } from "jwt-decode"

const cardTitle    = document.querySelector('.card-title')
const btnLogout    = document.getElementById('btn-logout')
const loadingState = document.getElementById('loading-state')
const emptyState   = document.getElementById('empty-state')
const tableWrapper = document.getElementById('table-wrapper')
const cartasTbody  = document.getElementById('cartas-tbody')
const cartasCount  = document.getElementById('cartas-count')

const STATUS_LABELS = {
    aguardando_pagamento: { label: 'Aguardando pagamento', badge: 'badge-warning' },
    pendente:             { label: 'Pendente',             badge: 'badge-warning' },
    pago:                 { label: 'Pago',                 badge: 'badge-success' },
    rejeitado:            { label: 'Rejeitado',            badge: 'badge-error' },
    cancelado:            { label: 'Cancelado',            badge: 'badge-error' },
}

function escapeHtml(str) {
    const div = document.createElement('div')
    div.textContent = str
    return div.innerHTML
}

function formatarData(dataStr) {
    const data = new Date(dataStr.replace(' ', 'T'))
    if (Number.isNaN(data.getTime())) return dataStr
    return data.toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit', year: 'numeric' })
}

function extrairTitulo(texto) {
    const primeiraLinha = (texto.split('\n').find(l => l.trim() !== '') ?? '').trim()
    return primeiraLinha.length > 40 ? primeiraLinha.slice(0, 40) + '...' : primeiraLinha
}

function renderCartas(cartas) {
    loadingState.classList.add('hidden')

    if (!Array.isArray(cartas) || cartas.length === 0) {
        emptyState.classList.remove('hidden')
        emptyState.classList.add('flex')
        cartasCount.textContent = 'Nenhuma carta criada'
        return
    }

    cartasCount.textContent = `${cartas.length} carta${cartas.length > 1 ? 's' : ''}`

    cartasTbody.innerHTML = cartas.map((carta) => {
        const statusInfo = STATUS_LABELS[carta.status] ?? { label: carta.status, badge: 'badge-ghost' }

        const acaoHtml = carta.status === 'pago'
            ? `<a href="/resultado/${carta.id}" class="btn btn-sm btn-outline">Ver carta</a>`
            : `<a href="/pagamento/${carta.id}" class="btn btn-sm btn-secondary">Pagar</a>`

        return `
            <tr>
                <td>${escapeHtml(extrairTitulo(carta.texto_carta))}</td>
                <td><span class="badge ${statusInfo.badge}">${escapeHtml(statusInfo.label)}</span></td>
                <td>${formatarData(carta.criado_em)}</td>
                <td class="text-right">${acaoHtml}</td>
            </tr>
        `
    }).join('')

    tableWrapper.classList.remove('hidden')
}

function mostrarErroCarregamento() {
    loadingState.classList.add('hidden')
    cartasCount.textContent = 'Erro ao carregar suas cartas'
    emptyState.classList.remove('hidden')
    emptyState.classList.add('flex')
    emptyState.querySelector('p').textContent =
        'Não foi possível carregar suas cartas agora. Tente recarregar a página.'
}

document.addEventListener('DOMContentLoaded', async () => {
    const token = localStorage.getItem('token')

    if (!token) {
        window.location.href = '/login'
        return
    }

    let decode
    try {
        decode = jwtDecode(token)
    } catch {
        localStorage.removeItem('token')
        window.location.href = '/login'
        return
    }

    cardTitle.textContent = decode.name

    try {
        const response = await fetch(`/cartas/user/${decode.sub}`, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`,
            },
        })

        if (response.status === 401) {
            localStorage.removeItem('token')
            window.location.href = '/login'
            return
        }

        if (!response.ok) {
            throw new Error(`Falha ao buscar cartas: ${response.status}`)
        }

        const data = await response.json()
        renderCartas(data)
    } catch (error) {
        console.error(error)
        mostrarErroCarregamento()
    }
})

btnLogout.addEventListener('click', (event) => {
    event.preventDefault()
    localStorage.removeItem('token')
    window.location.href = '/'
})