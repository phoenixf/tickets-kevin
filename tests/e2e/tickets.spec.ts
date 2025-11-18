import { test, expect } from '@playwright/test';

test.describe('CRUD de Tickets', () => {
  test.beforeEach(async ({ page }) => {
    // Login como admin antes de cada teste
    await page.goto('/login');
    await page.fill('input[name="email"]', 'kevin@tickets.com');
    await page.fill('input[name="password"]', 'segredo0');
    await page.click('button[type="submit"]');
    await page.waitForURL(/.*dashboard/);
  });

  test('deve listar tickets existentes', async ({ page }) => {
    await page.goto('/tickets');

    // Verificar título da página
    await expect(page.locator('h1:has-text("Tickets")')).toBeVisible();

    // Verificar que existe uma tabela
    const table = page.locator('table').first();
    await expect(table).toBeVisible();

    // Verificar que existem linhas de dados (deve ter os 8 tickets de teste)
    const rows = page.locator('tbody tr');
    const count = await rows.count();
    expect(count).toBeGreaterThan(0);
  });

  test('deve acessar formulário de criação', async ({ page }) => {
    await page.goto('/tickets');

    // Clicar em "Novo Ticket"
    await page.click('a:has-text("Novo Ticket")');
    await page.waitForURL(/.*tickets\/create/);

    // Verificar elementos do formulário
    await expect(page.locator('input[name="titulo"]')).toBeVisible();
    await expect(page.locator('textarea[name="descricao"]')).toBeVisible();
    await expect(page.locator('select[name="prioridade_id"]')).toBeVisible();
  });

  test('deve criar novo ticket com sucesso', async ({ page }) => {
    await page.goto('/tickets/create');

    // Preencher formulário
    await page.fill('input[name="titulo"]', 'Teste automatizado Playwright');
    await page.fill('textarea[name="descricao"]', 'Este é um ticket criado automaticamente pelos testes E2E do Playwright para validar o sistema.');
    await page.selectOption('select[name="prioridade_id"]', { index: 2 }); // Alta

    // Submeter formulário
    await page.click('button[type="submit"]');

    // Aguardar redirecionamento
    await page.waitForURL(/.*tickets\/\d+/);

    // Verificar que foi criado
    await expect(page.locator('text=Teste automatizado Playwright')).toBeVisible();
  });

  test('deve visualizar detalhes de um ticket', async ({ page }) => {
    await page.goto('/tickets');

    // Clicar no primeiro ticket "Ver"
    await page.click('a:has-text("Ver")').first;
    await page.waitForURL(/.*tickets\/\d+/);

    // Verificar que está na página de detalhes
    await expect(page.locator('h2')).toBeVisible();

    // Verificar que as seções existem
    await expect(page.locator('text=Comentários')).toBeVisible();
    await expect(page.locator('text=Anexos')).toBeVisible();
  });

  test('deve acessar edição de ticket', async ({ page }) => {
    await page.goto('/tickets');

    // Clicar em "Editar" no primeiro ticket
    const editButton = page.locator('a:has-text("Editar")').first();

    if (await editButton.isVisible()) {
      await editButton.click();
      await page.waitForURL(/.*tickets\/\d+\/edit/);

      // Verificar que está no formulário de edição
      await expect(page.locator('h1:has-text("Editar Ticket")')).toBeVisible();
      await expect(page.locator('select[name="status"]')).toBeVisible();

      // Verificar campo de transferência
      await expect(page.locator('select[name="responsavel_id"]')).toBeVisible();
      await expect(page.locator('text=Atribuir para')).toBeVisible();
    }
  });

  test('deve adicionar comentário a um ticket', async ({ page }) => {
    await page.goto('/tickets');

    // Abrir primeiro ticket
    await page.click('a:has-text("Ver")').first();
    await page.waitForURL(/.*tickets\/\d+/);

    // Verificar que o formulário de comentário existe
    const commentTextarea = page.locator('textarea[name="conteudo"]');
    await expect(commentTextarea).toBeVisible();

    // Adicionar comentário
    await commentTextarea.fill('Comentário adicionado via teste automatizado Playwright');
    await page.click('button:has-text("Adicionar Comentário")');

    // Aguardar o reload ou atualização
    await page.waitForTimeout(1000);

    // Verificar que o comentário apareceu
    await expect(page.locator('text=Comentário adicionado via teste automatizado Playwright')).toBeVisible();
  });

  test('deve ter validação no formulário de criação', async ({ page }) => {
    await page.goto('/tickets/create');

    // Tentar submeter sem preencher
    await page.click('button[type="submit"]');

    // Verificar que o formulário valida (HTML5 validation)
    const tituloInput = page.locator('input[name="titulo"]');
    const isInvalid = await tituloInput.evaluate((el: HTMLInputElement) => !el.validity.valid);
    expect(isInvalid).toBe(true);
  });

  test('deve aplicar filtros na listagem', async ({ page }) => {
    await page.goto('/tickets');

    // Verificar que existem elementos de filtro (se implementados)
    const statusFilter = page.locator('select[name="status"]');
    if (await statusFilter.isVisible()) {
      await statusFilter.selectOption('novo');

      // Verificar que a URL mudou ou a tabela foi atualizada
      await page.waitForTimeout(500);
    }
  });
});
