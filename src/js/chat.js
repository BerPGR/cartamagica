const chatBox = document.getElementById('chat');
const loading = document.getElementById('loading');
const inputArea = document.getElementById('input-area');
const input = document.getElementById('msg');
const btnEnviar = document.getElementById('btn-enviar');
const progressoBar = document.getElementById('progresso-bar');
const progressoTexto = document.getElementById('progresso-texto');

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

async function enviarResposta(texto) {
    const resp = await fetch('/chat', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'resposta=' + encodeURIComponent(texto),
    });

    if (!resp.ok) {
        throw new Error('Falha na requisição');
    }

    return await resp.json();
}

function processarResposta(data) {
    if (data.tipo === 'pergunta') {
        adicionarMensagem(data.texto, 'ia');
        atualizarProgresso(data.progresso, data.total);
    } else if (data.tipo === 'final') {
        adicionarMensagem(data.texto, 'ia');
        inputArea.classList.add('hidden');
        atualizarProgresso(progressoBar.max, progressoBar.max);
    } else if (data.tipo === 'erro') {
        adicionarMensagem(data.texto, 'ia');
    }
}

async function enviarMensagem() {
    const texto = input.value.trim();
    if (!texto) return;

    adicionarMensagem(texto, 'usuario');
    input.value = '';
    travarInput(true);
    mostrarLoading(true);

    try {
        const data = await enviarResposta(texto);
        processarResposta(data);
    } catch (e) {
        adicionarMensagem('Algo deu errado. Tente novamente.', 'ia');
    } finally {
        mostrarLoading(false);
        travarInput(false);
        input.focus();
    }
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