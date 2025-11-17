<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>

<!-- Mensagens de erro/sucesso -->
<?php if (session('error') !== null) : ?>
    <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm"><?= esc(session('error')) ?></p>
            </div>
        </div>
    </div>
<?php endif ?>

<?php if (session('message') !== null) : ?>
    <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm"><?= esc(session('message')) ?></p>
            </div>
        </div>
    </div>
<?php endif ?>

<div class="mb-6 text-center">
    <h2 class="text-2xl font-bold text-gray-800">Bem-vindo de volta!</h2>
    <p class="text-gray-600 mt-2">Entre com suas credenciais para continuar</p>
</div>

<!-- Formulário de Login -->
<form action="<?= url_to('login') ?>" method="post">
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
                class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                placeholder="seu@email.com"
                value="<?= old('email') ?>"
                required
                autofocus
            >
        </div>
        <?php if (has_error('email')) : ?>
            <p class="mt-1 text-sm text-red-600"><?= error('email') ?></p>
        <?php endif ?>
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
                class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                placeholder="••••••••"
                required
            >
        </div>
        <?php if (has_error('password')) : ?>
            <p class="mt-1 text-sm text-red-600"><?= error('password') ?></p>
        <?php endif ?>
    </div>

    <!-- Lembrar-me -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <input
                type="checkbox"
                id="remember"
                name="remember"
                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                <?php if (old('remember')) : ?>checked<?php endif ?>
            >
            <label for="remember" class="ml-2 block text-sm text-gray-700">
                Lembrar-me
            </label>
        </div>

        <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
            Esqueceu a senha?
        </a>
    </div>

    <!-- Botão de Login -->
    <button
        type="submit"
        class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold py-3 px-4 rounded-lg hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all transform hover:scale-[1.02] active:scale-[0.98]"
    >
        Entrar
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

<?= $this->endSection() ?>
