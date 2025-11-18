<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="mb-4">
    <h1 class="text-2xl font-bold text-gray-900">Editar Ticket #<?= $ticket['id'] ?></h1>
</div>

<div class="bg-white shadow-md rounded-lg p-6">
    <form action="/tickets/<?= $ticket['id'] ?>" method="POST">
        <?= csrf_field() ?>

        <!-- Título -->
        <div class="mb-4">
            <label for="titulo" class="block text-sm font-medium text-gray-700 mb-2">Título *</label>
            <input type="text" 
                   id="titulo" 
                   name="titulo" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                   required
                   value="<?= old('titulo', $ticket['titulo']) ?>">
        </div>

        <!-- Descrição -->
        <div class="mb-4">
            <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">Descrição *</label>
            <textarea id="descricao" 
                      name="descricao" 
                      rows="6" 
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                      required><?= old('descricao', $ticket['descricao']) ?></textarea>
        </div>

        <!-- Categoria, Prioridade, Status e Responsável -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <!-- Categoria -->
            <div>
                <label for="categoria_id" class="block text-sm font-medium text-gray-700 mb-2">Categoria</label>
                <select id="categoria_id"
                        name="categoria_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Nenhuma</option>
                    <?php foreach ($categorias as $categoria) : ?>
                        <option value="<?= $categoria['id'] ?>" <?= old('categoria_id', $ticket['categoria_id']) == $categoria['id'] ? 'selected' : '' ?>>
                            <?= esc($categoria['nome']) ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>

            <!-- Prioridade -->
            <div>
                <label for="prioridade_id" class="block text-sm font-medium text-gray-700 mb-2">Prioridade *</label>
                <select id="prioridade_id"
                        name="prioridade_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                    <?php foreach ($prioridades as $prioridade) : ?>
                        <option value="<?= $prioridade['id'] ?>" <?= old('prioridade_id', $ticket['prioridade_id']) == $prioridade['id'] ? 'selected' : '' ?>>
                            <?= esc($prioridade['nome']) ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                <select id="status"
                        name="status"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                    <option value="novo" <?= old('status', $ticket['status']) == 'novo' ? 'selected' : '' ?>>Novo</option>
                    <option value="aberto" <?= old('status', $ticket['status']) == 'aberto' ? 'selected' : '' ?>>Aberto</option>
                    <option value="em_progresso" <?= old('status', $ticket['status']) == 'em_progresso' ? 'selected' : '' ?>>Em Progresso</option>
                    <option value="pendente" <?= old('status', $ticket['status']) == 'pendente' ? 'selected' : '' ?>>Pendente</option>
                    <option value="resolvido" <?= old('status', $ticket['status']) == 'resolvido' ? 'selected' : '' ?>>Resolvido</option>
                    <option value="fechado" <?= old('status', $ticket['status']) == 'fechado' ? 'selected' : '' ?>>Fechado</option>
                </select>
            </div>

            <!-- Responsável (Transferir Ticket) -->
            <div>
                <label for="responsavel_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Atribuir para
                    <span class="text-xs text-gray-500">(Agente/Admin)</span>
                </label>
                <select id="responsavel_id"
                        name="responsavel_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Não atribuído</option>
                    <?php foreach ($agentes as $agente) : ?>
                        <option value="<?= $agente->id ?>" <?= old('responsavel_id', $ticket['responsavel_id']) == $agente->id ? 'selected' : '' ?>>
                            <?= esc($agente->nome) ?> (<?= esc($agente->funcao) ?>)
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
        </div>

        <!-- Botões -->
        <div class="flex justify-end space-x-3">
            <a href="/tickets/<?= $ticket['id'] ?>" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg">
                Cancelar
            </a>
            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">
                Salvar Alterações
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
