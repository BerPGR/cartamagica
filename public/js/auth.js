const form = document.querySelector('form[action="/login"]');
const nameFieldset = document.getElementById('name').closest('.fieldset');
const submitButton = document.querySelector('button[type="submit"]');
const btnRegister = document.getElementById('btn-register');
const titleText = document.querySelector('.card-title');
const descriptionText = document.querySelector('.card-body p');
const nameInput = document.getElementById('name');
const emailInput = document.getElementById('email');
const passwordInput = document.getElementById('password');

let isRegisterMode = false

btnRegister.addEventListener('click', (event) => {
    event.preventDefault();
    isRegisterMode = !isRegisterMode;

    if (isRegisterMode) {
        nameFieldset.classList.remove('hidden');
        submitButton.textContent = 'Registrar';
        titleText.textContent = 'Registrar';
        descriptionText.textContent = 'Crie sua conta para continuar';
        form.setAttribute('action', '/register');
        btnRegister.textContent = 'Já possui uma conta? Entrar';
        nameInput.required = true;
    } else {
        nameFieldset.classList.add('hidden');
        submitButton.textContent = 'Entrar';
        titleText.textContent = 'Entrar';
        descriptionText.textContent = 'Acesse sua conta para continuar';
        form.setAttribute('action', '/login');
        btnRegister.textContent = 'Criar conta';
        nameInput.required = false;
    }
})

form.addEventListener('submit', async (event) => {
    event.preventDefault()

    const payload = {
        email: emailInput.value,
        password: passwordInput.value
    }

    if (isRegisterMode) {
        payload.name = nameInput.value
    }

    submitButton.disabled = true
    const originalText = submitButton.textContent
    submitButton.textContent = isRegisterMode ? 'Registrando...' : 'Entrando...'

    try {
        const response = await fetch(form.getAttribute('action'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        })

        const data = await response.json().catch(() => null)

        if (!response.ok) {
            const message = data?.message || 'Ocorreu um erro. Por favor, tente novamente.'
            throw new Error(message)
        }

        if (data?.token) {
            localStorage.setItem('token', data.token)
        }
        window.location.href = '/home'
    }
    catch (error) {
        console.error(error)
        alert(error.message || 'Ocorreu um erro. Por favor, tente novamente.')
    } finally {
        submitButton.disabled = false
        submitButton.textContent = originalText
    }
})
