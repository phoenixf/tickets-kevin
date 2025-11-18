<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="mb-4">
    <h1 class="text-2xl font-bold text-gray-900">Novo Ticket</h1>
</div>

<div class="bg-white shadow-md rounded-lg p-6">
    <form action="/tickets" method="POST">
        <?= csrf_field() ?>

        <!-- Título -->
        <div class="mb-4">
            <label for="titulo" class="block text-sm font-medium text-gray-700 mb-2">Título *</label>
            <input type="text" 
                   id="titulo" 
                   name="titulo" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                   required
                   value="<?= old('titulo') ?>"
                   placeholder="Digite um título descritivo (mínimo 10 caracteres)">
        </div>

        <!-- Descrição -->
        <div class="mb-4">
            <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">Descrição *</label>
            <textarea id="descricao" 
                      name="descricao" 
                      rows="6" 
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                      required
                      placeholder="Descreva o problema ou solicitação (mínimo 20 caracteres)"><?= old('descricao') ?></textarea>
        </div>

        <!-- Categoria e Prioridade -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <!-- Categoria -->
            <div>
                <label for="categoria_id" class="block text-sm font-medium text-gray-700 mb-2">Categoria</label>
                <select id="categoria_id" 
                        name="categoria_id" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Selecione uma categoria</option>
                    <?php foreach ($categorias as $categoria) : ?>
                        <option value="<?= $categoria['id'] ?>" <?= old('categoria_id') == $categoria['id'] ? 'selected' : '' ?>>
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
                    <option value="">Selecione a prioridade</option>
                    <?php foreach ($prioridades as $prioridade) : ?>
                        <option value="<?= $prioridade['id'] ?>" <?= old('prioridade_id') == $prioridade['id'] ? 'selected' : '' ?>>
                            <?= esc($prioridade['nome']) ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
        </div>

        <!-- Botões -->
        <div class="flex justify-end space-x-3">
            <a href="/tickets" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg">
                Cancelar
            </a>
            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">
                Criar Ticket
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
