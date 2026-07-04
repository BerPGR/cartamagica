(() => {
  // src/js/auth.js
  var form = document.querySelector('form[action="/login"]');
  var nameFieldset = document.getElementById("name").closest(".fieldset");
  var submitButton = document.querySelector('button[type="submit"]');
  var btnRegister = document.getElementById("btn-register");
  var titleText = document.querySelector(".card-title");
  var descriptionText = document.querySelector(".card-body p");
  var nameInput = document.getElementById("name");
  var emailInput = document.getElementById("email");
  var passwordInput = document.getElementById("password");
  var isRegisterMode = false;
  btnRegister.addEventListener("click", (event) => {
    event.preventDefault();
    isRegisterMode = !isRegisterMode;
    if (isRegisterMode) {
      nameFieldset.classList.remove("hidden");
      submitButton.textContent = "Registrar";
      titleText.textContent = "Registrar";
      descriptionText.textContent = "Crie sua conta para continuar";
      form.setAttribute("action", "/register");
      btnRegister.textContent = "J\xE1 possui uma conta? Entrar";
      nameInput.required = true;
    } else {
      nameFieldset.classList.add("hidden");
      submitButton.textContent = "Entrar";
      titleText.textContent = "Entrar";
      descriptionText.textContent = "Acesse sua conta para continuar";
      form.setAttribute("action", "/login");
      btnRegister.textContent = "Criar conta";
      nameInput.required = false;
    }
  });
  form.addEventListener("submit", async (event) => {
    event.preventDefault();
    const payload = {
      email: emailInput.value,
      password: passwordInput.value
    };
    if (isRegisterMode) {
      payload.name = nameInput.value;
    }
    submitButton.disabled = true;
    const originalText = submitButton.textContent;
    submitButton.textContent = isRegisterMode ? "Registrando..." : "Entrando...";
    const loading = document.createElement("span");
    loading.className = "loading loading-md";
    submitButton.appendChild(loading);
    try {
      const response = await fetch(form.getAttribute("action"), {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify(payload)
      });
      const data = await response.json().catch(() => null);
      if (!response.ok) {
        const message = data?.message || "Ocorreu um erro. Por favor, tente novamente.";
        throw new Error(message);
      }
      if (data?.token) {
        localStorage.setItem("token", data.token);
      }
      window.location.href = "/home";
    } catch (error) {
      console.error(error);
      alert(error.message || "Ocorreu um erro. Por favor, tente novamente.");
    } finally {
      submitButton.disabled = false;
      submitButton.textContent = originalText;
      submitButton.removeChild();
    }
  });
})();
