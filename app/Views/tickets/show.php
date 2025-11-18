<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="mb-4 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-900">Ticket #<?= $ticket['id'] ?></h1>
    <div class="flex space-x-2">
        <?php if ($user->funcao !== 'cliente') : ?>
            <a href="/tickets/<?= $ticket['id'] ?>/edit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm">
                Editar
            </a>
        <?php endif ?>
        <a href="/tickets" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm">
            Voltar
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Conteúdo Principal -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Informações do Ticket -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4"><?= esc($ticket['titulo']) ?></h2>
            
            <div class="prose max-w-none">
                <p class="text-gray-700 whitespace-pre-wrap"><?= esc($ticket['descricao']) ?></p>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Detalhes -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Detalhes</h3>
            
            <dl class="space-y-3">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1">
                        <span class="px-2 py-1 text-xs font-semibold rounded bg-gray-100 text-gray-800">
                            <?= esc($ticket['status']) ?>
                        </span>
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Prioridade</dt>
                    <dd class="mt-1">
                        <span class="px-2 py-1 text-xs font-semibold rounded" style="background-color: <?= $ticket['prioridade_cor'] ?>20; color: <?= $ticket['prioridade_cor'] ?>">
                            <?= esc($ticket['prioridade_nome']) ?>
                        </span>
                    </dd>
                </div>

                <?php if (!empty($ticket['categoria_nome'])) : ?>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Categoria</dt>
                        <dd class="mt-1 text-sm text-gray-900"><?= esc($ticket['categoria_nome']) ?></dd>
                    </div>
                <?php endif ?>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Criado por</dt>
                    <dd class="mt-1 text-sm text-gray-900"><?= esc($ticket['usuario_nome']) ?></dd>
                    <dd class="text-xs text-gray-500"><?= esc($ticket['usuario_email']) ?></dd>
                </div>

                <?php if (!empty($ticket['responsavel_nome'])) : ?>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Responsável</dt>
                        <dd class="mt-1 text-sm text-gray-900"><?= esc($ticket['responsavel_nome']) ?></dd>
                    </div>
                <?php endif ?>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Criado em</dt>
                    <dd class="mt-1 text-sm text-gray-900"><?= date('d/m/Y H:i', strtotime($ticket['criado_em'])) ?></dd>
                </div>

                <?php if (!empty($ticket['atualizado_em']) && $ticket['atualizado_em'] != $ticket['criado_em']) : ?>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Atualizado em</dt>
                        <dd class="mt-1 text-sm text-gray-900"><?= date('d/m/Y H:i', strtotime($ticket['atualizado_em'])) ?></dd>
                    </div>
                <?php endif ?>
            </dl>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
