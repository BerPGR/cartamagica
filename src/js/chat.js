import { jwtDecode } from "jwt-decode";

const chatBox = document.getElementById('chat');
const loading = document.getElementById('loading');
const inputArea = document.getElementById('input-area');
const input = document.getElementById('msg');
const btnEnviar = document.getElementById('btn-enviar');
const progressoBar = document.getElementById('progresso-bar');
const progressoTexto = document.getElementById('progresso-texto');
const conteudoChat = document.getElementById('conteudo-chat');
const telaStatus = document.getElementById('tela-status');
const statusGerando = document.getElementById('status-gerando');
const statusPronto = document.getElementById('status-pronto');

function adicionarMensagem(texto, autor) {
    const lado = autor === 'ia' ? 'chat-start' : 'chat-end';
    const cor = autor === 'ia' ? 'chat-bubble-primary' : '';

    const bloco = document.createElement('div');
    bloco.className = `chat ${lado}`;
    bloco.innerHTML = `<div class="chat-bubble ${cor}">${escapeHtml(texto)}</div>`;

    chatBox.appendChild(bloco);
    chatBox.scrollTop = chatBox.scrollHeight;
}

function escapeHtml(texto) {
    const div = document.createElement('div');
    div.textContent = texto;
    return div.innerHTML;
}

function mostrarLoading(mostrar) {
    loading.classList.toggle('hidden', !mostrar);
    if (mostrar) chatBox.scrollTop = chatBox.scrollHeight;
}

function travarInput(travar) {
    input.disabled = travar;
    btnEnviar.disabled = travar;
}

function atualizarProgresso(atual, total) {
    progressoBar.value = atual;
    progressoBar.max = total;
    progressoTexto.textContent = `${atual}/${total}`;
}

async function enviarResposta(texto, user_id = null) {
    const params = new URLSearchParams()
    params.append('resposta', texto)

    if (user_id !== null) {
        params.append('user_id', user_id)
    }

    const resp = await fetch('/chat', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: params.toString()
    });

    if (!resp.ok) {
        throw new Error('Falha na requisição');
    }

    return await resp.json();
}

function mostrarTelaGerando() {
    conteudoChat.classList.add('hidden');
    telaStatus.classList.remove('hidden');
    statusGerando.classList.remove('hidden');
    statusPronto.classList.add('hidden');
}

function mostrarTelaPronto() {
    statusGerando.classList.add('hidden');
    statusPronto.classList.remove('hidden');
}

function processarResposta(data) {
    if (data.tipo === 'pergunta') {
        adicionarMensagem(data.texto, 'ia');
        atualizarProgresso(data.progresso, data.total);
    } else if (data.tipo === 'final') {
        mostrarTelaPronto();
        setTimeout(() => {
            window.location.href = "/pagamento/" + data.carta_id
        }, 2000);
    } else if (data.tipo === 'erro') {
        adicionarMensagem(data.texto, 'ia');
    }
}

async function enviarMensagem() {
    const texto = input.value.trim();
    if (!texto) return;

    const isUltimaResposta = progressoBar.value === progressoBar.max

    adicionarMensagem(texto, 'usuario');
    input.value = '';
    travarInput(true);
    
    let user_id = null
    if (isUltimaResposta) {
        user_id = getUserId()
        mostrarTelaGerando();
    } else {
        mostrarLoading(true);
    }

    try {
        const data = await enviarResposta(texto, user_id);
        processarResposta(data);
    } catch (e) {
        console.error('Erro:', e);
        adicionarMensagem('Algo deu errado. Tente novamente.', 'ia');
    } finally {
        if (!isUltimaResposta) {
            mostrarLoading(false);
            travarInput(false);
            input.focus();
        }
    }
}

function getUserId() {
    const token = localStorage.getItem('token')
    const decode = jwtDecode(token)
    return decode.sub
}

async function iniciarChat() {
    travarInput(true);
    mostrarLoading(true);

    try {
        const data = await enviarResposta('');
        processarResposta(data);
    } catch (e) {
        console.error(e)
        adicionarMensagem('Não foi possível iniciar a conversa.', 'ia');
    } finally {
        mostrarLoading(false);
        travarInput(false);
        input.focus();
    }
}

btnEnviar.addEventListener('click', enviarMensagem);
input.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') enviarMensagem();
});

document.addEventListener('DOMContentLoaded', iniciarChat);