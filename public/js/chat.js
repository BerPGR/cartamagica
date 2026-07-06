(() => {
  // node_modules/jwt-decode/build/esm/index.js
  var InvalidTokenError = class extends Error {
  };
  InvalidTokenError.prototype.name = "InvalidTokenError";
  function b64DecodeUnicode(str) {
    return decodeURIComponent(atob(str).replace(/(.)/g, (m, p) => {
      let code = p.charCodeAt(0).toString(16).toUpperCase();
      if (code.length < 2) {
        code = "0" + code;
      }
      return "%" + code;
    }));
  }
  function base64UrlDecode(str) {
    let output = str.replace(/-/g, "+").replace(/_/g, "/");
    switch (output.length % 4) {
      case 0:
        break;
      case 2:
        output += "==";
        break;
      case 3:
        output += "=";
        break;
      default:
        throw new Error("base64 string is not of the correct length");
    }
    try {
      return b64DecodeUnicode(output);
    } catch (err) {
      return atob(output);
    }
  }
  function jwtDecode(token, options) {
    if (typeof token !== "string") {
      throw new InvalidTokenError("Invalid token specified: must be a string");
    }
    options || (options = {});
    const pos = options.header === true ? 0 : 1;
    const part = token.split(".")[pos];
    if (typeof part !== "string") {
      throw new InvalidTokenError(`Invalid token specified: missing part #${pos + 1}`);
    }
    let decoded;
    try {
      decoded = base64UrlDecode(part);
    } catch (e) {
      throw new InvalidTokenError(`Invalid token specified: invalid base64 for part #${pos + 1} (${e.message})`);
    }
    try {
      return JSON.parse(decoded);
    } catch (e) {
      throw new InvalidTokenError(`Invalid token specified: invalid json for part #${pos + 1} (${e.message})`);
    }
  }

  // src/js/chat.js
  var chatBox = document.getElementById("chat");
  var loading = document.getElementById("loading");
  var inputArea = document.getElementById("input-area");
  var input = document.getElementById("msg");
  var btnEnviar = document.getElementById("btn-enviar");
  var progressoBar = document.getElementById("progresso-bar");
  var progressoTexto = document.getElementById("progresso-texto");
  var conteudoChat = document.getElementById("conteudo-chat");
  var telaStatus = document.getElementById("tela-status");
  var statusGerando = document.getElementById("status-gerando");
  var statusPronto = document.getElementById("status-pronto");
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
  async function enviarResposta(texto, user_id = null) {
    const params = new URLSearchParams();
    params.append("resposta", texto);
    if (user_id !== null) {
      params.append("user_id", user_id);
    }
    const resp = await fetch("/chat", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: params.toString()
    });
    if (!resp.ok) {
      throw new Error("Falha na requisi\xE7\xE3o");
    }
    return await resp.json();
  }
  function mostrarTelaGerando() {
    conteudoChat.classList.add("hidden");
    telaStatus.classList.remove("hidden");
    statusGerando.classList.remove("hidden");
    statusPronto.classList.add("hidden");
  }
  function mostrarTelaPronto() {
    statusGerando.classList.add("hidden");
    statusPronto.classList.remove("hidden");
  }
  function processarResposta(data) {
    if (data.tipo === "pergunta") {
      adicionarMensagem(data.texto, "ia");
      atualizarProgresso(data.progresso, data.total);
    } else if (data.tipo === "final") {
      mostrarTelaPronto();
      setTimeout(() => {
        window.location.href = "/pagamento/" + data.carta_id;
      }, 2e3);
    } else if (data.tipo === "erro") {
      adicionarMensagem(data.texto, "ia");
    }
  }
  async function enviarMensagem() {
    const texto = input.value.trim();
    if (!texto) return;
    const isUltimaResposta = progressoBar.value === progressoBar.max;
    adicionarMensagem(texto, "usuario");
    input.value = "";
    travarInput(true);
    let user_id = null;
    if (isUltimaResposta) {
      user_id = getUserId();
      mostrarTelaGerando();
    } else {
      mostrarLoading(true);
    }
    try {
      const data = await enviarResposta(texto, user_id);
      processarResposta(data);
    } catch (e) {
      console.error("Erro:", e);
      adicionarMensagem("Algo deu errado. Tente novamente.", "ia");
    } finally {
      if (!isUltimaResposta) {
        mostrarLoading(false);
        travarInput(false);
        input.focus();
      }
    }
  }
  function getUserId() {
    const token = localStorage.getItem("token");
    const decode = jwtDecode(token);
    return decode.sub;
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
