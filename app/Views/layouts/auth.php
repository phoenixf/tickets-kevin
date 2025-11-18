<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Sistema de Tickets') ?></title>

    <!-- Tailwind CSS (CDN para desenvolvimento) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Flowbite CSS -->
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        /* ========================================
           Animações UX Modernas - 2025
           ======================================== */

        /* Shake Animation - Para erros */
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-10px); }
            20%, 40%, 60%, 80% { transform: translateX(10px); }
        }

        .animate-shake {
            animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both;
        }

        /* Fade In Animation - Para feedbacks de sucesso */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out;
        }

        /* Pulse Animation - Para loading states */
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }

        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        /* Smooth transitions em inputs */
        input:focus {
            transition: all 0.2s ease-in-out;
        }

        /* Disabled state visual feedback */
        input:disabled,
        button:disabled {
            cursor: not-allowed;
            opacity: 0.6;
        }

        /* Loading spinner smoothness */
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <!-- Card de Login -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Header com Logo -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-6 text-center">
                <div class="flex justify-center mb-2">
                    <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-white">Sistema de Tickets</h1>
                <p class="text-indigo-100 text-sm mt-1">Gestão de Suporte</p>
            </div>

            <!-- Conteúdo -->
            <div class="px-8 py-8">
                <?= $this->renderSection('content') ?>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-8 py-4 text-center">
                <p class="text-sm text-gray-600">
                    &copy; <?= date('Y') ?> Sistema de Tickets. Todos os direitos reservados.
                </p>
            </div>
        </div>
    </div>

    <!-- Flowbite JS -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</body>
</html>
