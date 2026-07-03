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

  // src/js/header.js
  var btnLogin = document.getElementById("btn-login");
  var firstAnchor = document.getElementById("option1");
  var secondAnchor = document.getElementById("option2");
  document.addEventListener("DOMContentLoaded", () => {
    checkJwtToken();
    loadAnchors();
  });
  function loadAnchors() {
    const location = window.location.pathname;
    if (location !== "/") {
      firstAnchor.textContent = "Home";
      firstAnchor.setAttribute("href", "/home");
      secondAnchor.textContent = "Minhas cartas";
      secondAnchor.setAttribute("href", "/cartas");
    } else {
      firstAnchor.textContent = "Como funciona";
      firstAnchor.setAttribute("href", "#como-funciona");
      secondAnchor.textContent = "Benef\xEDcios";
      secondAnchor.setAttribute("href", "#beneficios");
    }
  }
  function checkJwtToken() {
    const token = localStorage.getItem("token");
    if (!token && window.location.pathname !== "/") {
      window.location.href = "/";
    }
    ;
    try {
      const decoded = jwtDecode(token);
      const now = Date.now();
      const isValid = decoded.exp * 1e3 > now;
      if (isValid) {
        btnLogin?.classList.add("hidden");
        if (window.location.pathname !== "/home") {
          window.location.href = "/home";
        }
      } else {
        localStorage.removeItem("token");
        if (window.location.pathname !== "/" && window.location.pathname !== "/login") {
          window.location.href = "/";
        }
      }
    } catch (e) {
      localStorage.removeItem("token");
    }
  }
})();
