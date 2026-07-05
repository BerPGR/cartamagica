(() => {
  // src/js/chat.js
  var chatBox = document.getElementById("chat");
  var loading = document.getElementById("loading");
  var inputArea = document.getElementById("input-area");
  var input = document.getElementById("msg");
  var btnEnviar = document.getElementById("btn-enviar");
  var progressoBar = document.getElementById("progresso-bar");
  var progressoTexto = document.getElementById("progresso-texto");
  function adicionarMensagem(texto, autor) {
    const lado = autor === "ia" ? "chat-start" : "chat-end";
    const cor = autor === "ia" ? "chat-bubble-primary" : "";
    const bloco = document.createElement("div");
    bloco.className = `chat ${lado}`;
    bloco.innerHTML = `<div class="chat-bubble ${cor}">${escapeHtml(texto)}</div>`;
    chatBox.appendChild(bloco);
    chatBox.scrollTop = chatBox.scrollHeight;
  }
  function escapeHtml(texto) {
    const div = document.createElement("div");
    div.textContent = texto;
    return div.innerHTML;
  }
  function mostrarLoading(mostrar) {
    loading.classList.toggle("hidden", !mostrar);
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
    const resp = await fetch("/chat", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "resposta=" + encodeURIComponent(texto)
    });
    if (!resp.ok) {
      throw new Error("Falha na requisi\xE7\xE3o");
    }
    return await resp.json();
  }
  function processarResposta(data) {
    if (data.tipo === "pergunta") {
      adicionarMensagem(data.texto, "ia");
      atualizarProgresso(data.progresso, data.total);
    } else if (data.tipo === "final") {
      adicionarMensagem(data.texto, "ia");
      inputArea.classList.add("hidden");
      atualizarProgresso(progressoBar.max, progressoBar.max);
    } else if (data.tipo === "erro") {
      adicionarMensagem(data.texto, "ia");
    }
  }
  async function enviarMensagem() {
    const texto = input.value.trim();
    if (!texto) return;
    adicionarMensagem(texto, "usuario");
    input.value = "";
    travarInput(true);
    mostrarLoading(true);
    try {
      const data = await enviarResposta(texto);
      processarResposta(data);
    } catch (e) {
      adicionarMensagem("Algo deu errado. Tente novamente.", "ia");
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
      const data = await enviarResposta("");
      processarResposta(data);
    } catch (e) {
      console.error(e);
      adicionarMensagem("N\xE3o foi poss\xEDvel iniciar a conversa.", "ia");
    } finally {
      mostrarLoading(false);
      travarInput(false);
      input.focus();
    }
  }
  btnEnviar.addEventListener("click", enviarMensagem);
  input.addEventListener("keydown", (e) => {
    if (e.key === "Enter") enviarMensagem();
  });
  document.addEventListener("DOMContentLoaded", iniciarChat);
})();
