<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>

<div class="mb-6 text-center">
    <h2 class="text-2xl font-bold text-gray-800">Bem-vindo de volta!</h2>
    <p class="text-gray-600 mt-2">Entre com suas credenciais para continuar</p>
</div>

<!-- Mensagem de Feedback (Erro/Sucesso) - Controlada via JavaScript -->
<div id="feedback-message" class="mb-4 hidden">
    <div class="p-4 rounded-lg border-l-4 transition-all duration-300">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg id="feedback-icon" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                    <!-- Ícone será alterado via JS -->
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <p id="feedback-text" class="text-sm font-medium"></p>
            </div>
        </div>
    </div>
</div>

<!-- Formulário de Login Moderno -->
<form id="login-form" method="post">
    <?= csrf_field() ?>

    <!-- Campo de Email -->
    <div class="mb-4">
        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
            Email
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                </svg>
            </div>
            <input
                type="email"
                id="email"
                name="email"
                class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                placeholder="seu@email.com"
                required
                autofocus
            >
        </div>
        <span id="email-error" class="text-red-500 text-xs mt-1 hidden"></span>
    </div>

    <!-- Campo de Senha -->
    <div class="mb-6">
        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
            Senha
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <input
                type="password"
                id="password"
                name="password"
                class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                placeholder="••••••••"
                required
            >
        </div>
        <span id="password-error" class="text-red-500 text-xs mt-1 hidden"></span>
    </div>

    <!-- Lembrar-me -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <input
                type="checkbox"
                id="remember"
                name="remember"
                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded disabled:opacity-50"
            >
            <label for="remember" class="ml-2 block text-sm text-gray-700">
                Lembrar-me
            </label>
        </div>

        <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
            Esqueceu a senha?
        </a>
    </div>

    <!-- Botão de Login com Loading Spinner -->
    <button
        id="submit-btn"
        type="submit"
        class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold py-3 px-4 rounded-lg hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all transform hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none flex items-center justify-center"
    >
        <span id="btn-text">Entrar</span>
        <span id="btn-spinner" class="hidden">
            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="ml-2">Entrando...</span>
        </span>
    </button>
</form>

<!-- Informações de teste (remover em produção) -->
<div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
    <p class="text-xs font-semibold text-blue-800 mb-2">🔑 Credenciais de Teste:</p>
    <div class="text-xs text-blue-700 space-y-1">
        <p><strong>Admin:</strong> admin@tickets.com / 123456</p>
        <p><strong>Agente:</strong> joao.silva@tickets.com / 123456</p>
        <p><strong>Cliente:</strong> ana.costa@cliente.com / 123456</p>
    </div>
</div>

<script>
// ========================================
// Sistema de Login AJAX Moderno - UX 2025
// ========================================

const form = document.getElementById('login-form');
const submitBtn = document.getElementById('submit-btn');
const btnText = document.getElementById('btn-text');
const btnSpinner = document.getElementById('btn-spinner');
const feedbackMessage = document.getElementById('feedback-message');
const feedbackText = document.getElementById('feedback-text');
const feedbackIcon = document.getElementById('feedback-icon');

// Elementos de input
const emailInput = document.getElementById('email');
const passwordInput = document.getElementById('password');
const rememberInput = document.getElementById('remember');

// Elementos de erro
const emailError = document.getElementById('email-error');
const passwordError = document.getElementById('password-error');

// ========================================
// Event Listeners
// ========================================

form.addEventListener('submit', handleLogin);

// Limpar erros ao digitar
emailInput.addEventListener('input', () => clearFieldError(emailInput, emailError));
passwordInput.addEventListener('input', () => clearFieldError(passwordInput, passwordError));

// ========================================
// Função Principal - Login AJAX
// ========================================

async function handleLogin(e) {
    e.preventDefault();

    // Limpar feedback anterior
    hideFeedback();
    clearAllErrors();

    // Validação frontend
    if (!validateForm()) {
        return;
    }

    // IMPORTANTE: Capturar FormData ANTES de desabilitar inputs
    const formData = new FormData(form);

    // Iniciar loading (desabilita inputs)
    setLoading(true);

    try {

        // Fazer requisição AJAX
        const response = await fetch('<?= base_url('login/ajax') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        // Parse da resposta JSON
        const data = await response.json();

        if (data.success) {
            // ✅ Sucesso - Mostrar feedback positivo
            showSuccess(data.message);

            // Aguardar animação antes de redirecionar
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 1000);

        } else {
            // ❌ Erro - Mostrar feedback negativo
            showError(data.message);

            // Se houver erros específicos de campos, mostrar
            if (data.errors) {
                displayFieldErrors(data.errors);
            }

            setLoading(false);
        }

    } catch (error) {
        console.error('Erro na requisição:', error);
        showError('Erro ao conectar com o servidor. Verifique sua conexão.');
        setLoading(false);
    }
}

// ========================================
// Validação Frontend
// ========================================

function validateForm() {
    let isValid = true;

    // Validar email
    const email = emailInput.value.trim();
    if (!email) {
        showFieldError(emailInput, emailError, 'O email é obrigatório');
        isValid = false;
    } else if (!isValidEmail(email)) {
        showFieldError(emailInput, emailError, 'Forneça um email válido');
        isValid = false;
    }

    // Validar senha
    const password = passwordInput.value;
    if (!password) {
        showFieldError(passwordInput, passwordError, 'A senha é obrigatória');
        isValid = false;
    }

    // Se inválido, mostrar feedback geral
    if (!isValid) {
        showError('Corrija os erros antes de continuar');
    }

    return isValid;
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// ========================================
// Gerenciamento de Loading State
// ========================================

function setLoading(loading) {
    if (loading) {
        // Desabilitar formulário
        submitBtn.disabled = true;
        emailInput.disabled = true;
        passwordInput.disabled = true;
        rememberInput.disabled = true;

        // Mostrar spinner
        btnText.classList.add('hidden');
        btnSpinner.classList.remove('hidden');

        // Adicionar cursor wait
        document.body.style.cursor = 'wait';
    } else {
        // Habilitar formulário
        submitBtn.disabled = false;
        emailInput.disabled = false;
        passwordInput.disabled = false;
        rememberInput.disabled = false;

        // Esconder spinner
        btnText.classList.remove('hidden');
        btnSpinner.classList.add('hidden');

        // Remover cursor wait
        document.body.style.cursor = 'default';
    }
}

// ========================================
// Feedback Visual (Sucesso/Erro)
// ========================================

function showSuccess(message) {
    const messageDiv = feedbackMessage.firstElementChild;

    // Remover classes de erro
    messageDiv.classList.remove('bg-red-50', 'border-red-500', 'text-red-700');

    // Adicionar classes de sucesso
    messageDiv.classList.add('bg-green-50', 'border-green-500', 'text-green-700');

    // Ícone de sucesso (checkmark)
    feedbackIcon.innerHTML = `
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
    `;
    feedbackIcon.classList.remove('text-red-400');
    feedbackIcon.classList.add('text-green-400');

    // Texto
    feedbackText.textContent = message;

    // Mostrar com animação
    feedbackMessage.classList.remove('hidden');
    feedbackMessage.classList.add('animate-fadeIn');
}

function showError(message) {
    const messageDiv = feedbackMessage.firstElementChild;

    // Remover classes de sucesso
    messageDiv.classList.remove('bg-green-50', 'border-green-500', 'text-green-700');

    // Adicionar classes de erro
    messageDiv.classList.add('bg-red-50', 'border-red-500', 'text-red-700');

    // Ícone de erro (X)
    feedbackIcon.innerHTML = `
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
    `;
    feedbackIcon.classList.remove('text-green-400');
    feedbackIcon.classList.add('text-red-400');

    // Texto
    feedbackText.textContent = message;

    // Mostrar com animação shake
    feedbackMessage.classList.remove('hidden');
    feedbackMessage.classList.add('animate-shake');

    setTimeout(() => {
        feedbackMessage.classList.remove('animate-shake');
    }, 500);
}

function hideFeedback() {
    feedbackMessage.classList.add('hidden');
}

// ========================================
// Erros de Campos Específicos
// ========================================

function showFieldError(input, errorElement, message) {
    input.classList.add('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
    errorElement.textContent = message;
    errorElement.classList.remove('hidden');

    // Shake no campo
    input.classList.add('animate-shake');
    setTimeout(() => {
        input.classList.remove('animate-shake');
    }, 500);
}

function clearFieldError(input, errorElement) {
    input.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
    errorElement.classList.add('hidden');
}

function clearAllErrors() {
    clearFieldError(emailInput, emailError);
    clearFieldError(passwordInput, passwordError);
}

function displayFieldErrors(errors) {
    if (errors.email) {
        showFieldError(emailInput, emailError, errors.email);
    }
    if (errors.password) {
        showFieldError(passwordInput, passwordError, errors.password);
    }
}
</script>

<?= $this->endSection() ?>
