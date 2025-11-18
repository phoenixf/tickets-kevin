<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="mb-4 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200">Ticket #<?= $ticket['id'] ?></h1>
    <div class="flex space-x-2">
        <?php if ($user->funcao !== 'cliente') : ?>
            <a href="/tickets/<?= $ticket['id'] ?>/edit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm">
                Editar
            </a>
        <?php endif ?>
        <a href="/tickets" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-white rounded-lg text-sm transition-colors duration-200">
            Voltar
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Conteúdo Principal -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Informações do Ticket -->
        <div class="bg-white shadow-md rounded-lg p-6 dark:bg-gray-800 transition-colors duration-200">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 transition-colors duration-200"><?= esc($ticket['titulo']) ?></h2>

            <div class="prose max-w-none">
                <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap transition-colors duration-200"><?= esc($ticket['descricao']) ?></p>
            </div>
        </div>

        <!-- Comentários -->
        <div class="bg-white shadow-md rounded-lg p-6 dark:bg-gray-800 transition-colors duration-200">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 transition-colors duration-200">
                Comentários (<?= count($comentarios) ?>)
            </h3>

            <!-- Lista de Comentários -->
            <div class="space-y-4 mb-6">
                <?php if (empty($comentarios)) : ?>
                    <p class="text-gray-500 dark:text-gray-400 text-sm transition-colors duration-200">Nenhum comentário ainda.</p>
                <?php else : ?>
                    <?php foreach ($comentarios as $comentario) : ?>
                        <div class="border-l-4 <?= $comentario['eh_interno'] ? 'border-yellow-400 bg-yellow-50 dark:bg-yellow-900/20 dark:border-yellow-600' : 'border-gray-300 bg-gray-50 dark:bg-gray-700 dark:border-gray-600' ?> p-4 rounded transition-colors duration-200">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <span class="font-semibold text-gray-900 dark:text-white transition-colors duration-200"><?= esc($comentario['usuario_nome']) ?></span>
                                    <?php if ($comentario['eh_interno']) : ?>
                                        <span class="ml-2 px-2 py-1 text-xs font-semibold rounded bg-yellow-200 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 transition-colors duration-200">
                                            Interno
                                        </span>
                                    <?php endif ?>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs text-gray-500 dark:text-gray-400 transition-colors duration-200">
                                        <?= date('d/m/Y H:i', strtotime($comentario['criado_em'])) ?>
                                    </span>
                                    <?php if ($comentario['usuario_id'] == $user->id || $user->funcao === 'admin') : ?>
                                        <form action="/comments/<?= $comentario['id'] ?>" method="POST" class="inline"
                                              onsubmit="return confirm('Tem certeza que deseja deletar este comentário?')">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 text-xs transition-colors duration-200">
                                                Deletar
                                            </button>
                                        </form>
                                    <?php endif ?>
                                </div>
                            </div>
                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap transition-colors duration-200"><?= esc($comentario['conteudo']) ?></p>
                        </div>
                    <?php endforeach ?>
                <?php endif ?>
            </div>

            <!-- Formulário Adicionar Comentário -->
            <form action="/tickets/<?= $ticket['id'] ?>/comments" method="POST" class="border-t dark:border-gray-700 pt-4 transition-colors duration-200">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="conteudo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 transition-colors duration-200">
                        Adicionar Comentário
                    </label>
                    <textarea id="conteudo"
                              name="conteudo"
                              rows="3"
                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:focus:ring-indigo-400 dark:focus:border-indigo-400 transition-colors duration-200"
                              placeholder="Digite seu comentário..."
                              required></textarea>
                </div>

                <?php if ($user->funcao !== 'cliente') : ?>
                    <div class="mb-3">
                        <label class="flex items-center">
                            <input type="checkbox" name="eh_interno" value="1" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-indigo-600 focus:ring-indigo-500 transition-colors duration-200">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300 transition-colors duration-200">Comentário interno (não visível para clientes)</span>
                        </label>
                    </div>
                <?php endif ?>

                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm rounded-lg">
                    Adicionar Comentário
                </button>
            </form>
        </div>

        <!-- Anexos -->
        <div class="bg-white shadow-md rounded-lg p-6 dark:bg-gray-800 transition-colors duration-200">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 transition-colors duration-200">
                Anexos (<?= count($anexos) ?>)
            </h3>

            <!-- Lista de Anexos -->
            <div class="space-y-2 mb-6">
                <?php if (empty($anexos)) : ?>
                    <p class="text-gray-500 dark:text-gray-400 text-sm transition-colors duration-200">Nenhum anexo.</p>
                <?php else : ?>
                    <?php foreach ($anexos as $anexo) : ?>
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200">
                            <div class="flex items-center space-x-3">
                                <svg class="w-8 h-8 text-gray-400 dark:text-gray-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white transition-colors duration-200"><?= esc($anexo['nome_arquivo']) ?></p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 transition-colors duration-200">
                                        <?php
                                        $bytes = $anexo['tamanho_arquivo'];
                                        $units = ['B', 'KB', 'MB', 'GB'];
                                        $i = 0;
                                        while ($bytes >= 1024 && $i < count($units) - 1) {
                                            $bytes /= 1024;
                                            $i++;
                                        }
                                        echo round($bytes, 2) . ' ' . $units[$i];
                                        ?> •
                                        Enviado por <?= esc($anexo['enviado_por_nome']) ?> em
                                        <?= date('d/m/Y H:i', strtotime($anexo['criado_em'])) ?>
                                    </p>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <a href="/attachments/<?= $anexo['id'] ?>/download"
                                   class="px-3 py-1 text-sm bg-indigo-600 hover:bg-indigo-700 text-white rounded">
                                    Download
                                </a>
                                <?php if ($anexo['enviado_por'] == $user->id || $user->funcao === 'admin') : ?>
                                    <form action="/attachments/<?= $anexo['id'] ?>" method="POST" class="inline"
                                          onsubmit="return confirm('Tem certeza que deseja deletar este anexo?')">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="px-3 py-1 text-sm bg-red-600 hover:bg-red-700 text-white rounded">
                                            Deletar
                                        </button>
                                    </form>
                                <?php endif ?>
                            </div>
                        </div>
                    <?php endforeach ?>
                <?php endif ?>
            </div>

            <!-- Formulário Upload Anexo -->
            <form action="/tickets/<?= $ticket['id'] ?>/attachments" method="POST" enctype="multipart/form-data" class="border-t dark:border-gray-700 pt-4 transition-colors duration-200">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="arquivo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 transition-colors duration-200">
                        Adicionar Anexo
                    </label>
                    <input type="file"
                           id="arquivo"
                           name="arquivo"
                           class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 dark:file:bg-indigo-900/30 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-900/50 transition-colors duration-200"
                           required>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 transition-colors duration-200">
                        Máximo 10MB. Formatos: PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG, GIF, ZIP, TXT
                    </p>
                </div>

                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm rounded-lg">
                    Enviar Arquivo
                </button>
            </form>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Detalhes -->
        <div class="bg-white shadow-md rounded-lg p-6 dark:bg-gray-800 transition-colors duration-200">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 transition-colors duration-200">Detalhes</h3>
            
            <dl class="space-y-3">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 transition-colors duration-200">Status</dt>
                    <dd class="mt-1">
                        <?php
                        helper('ticket');
                        $statusCor = corStatus($ticket['status']);
                        ?>
                        <span class="px-2 py-1 text-xs font-semibold rounded <?= $statusCor['bg'] ?> <?= $statusCor['text'] ?>">
                            <?= traduzirStatus($ticket['status']) ?>
                        </span>
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 transition-colors duration-200">Prioridade</dt>
                    <dd class="mt-1">
                        <span class="px-2 py-1 text-xs font-semibold rounded" style="background-color: <?= $ticket['prioridade_cor'] ?>20; color: <?= $ticket['prioridade_cor'] ?>">
                            <?= esc($ticket['prioridade_nome']) ?>
                        </span>
                    </dd>
                </div>

                <?php if (!empty($ticket['categoria_nome'])) : ?>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 transition-colors duration-200">Categoria</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white transition-colors duration-200"><?= esc($ticket['categoria_nome']) ?></dd>
                    </div>
                <?php endif ?>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 transition-colors duration-200">Criado por</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white transition-colors duration-200"><?= esc($ticket['usuario_nome']) ?></dd>
                    <dd class="text-xs text-gray-500 dark:text-gray-400 transition-colors duration-200"><?= esc($ticket['usuario_email']) ?></dd>
                </div>

                <?php if (!empty($ticket['responsavel_nome'])) : ?>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 transition-colors duration-200">Responsável</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white transition-colors duration-200"><?= esc($ticket['responsavel_nome']) ?></dd>
                    </div>
                <?php endif ?>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 transition-colors duration-200">Criado em</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white transition-colors duration-200"><?= date('d/m/Y H:i', strtotime($ticket['criado_em'])) ?></dd>
                </div>

                <?php if (!empty($ticket['atualizado_em']) && $ticket['atualizado_em'] != $ticket['criado_em']) : ?>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 transition-colors duration-200">Atualizado em</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white transition-colors duration-200"><?= date('d/m/Y H:i', strtotime($ticket['atualizado_em'])) ?></dd>
                    </div>
                <?php endif ?>
            </dl>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
