import { test, expect } from '@playwright/test';

test.describe('Dashboard', () => {
  test.beforeEach(async ({ page }) => {
    // Login como admin antes de cada teste
    await page.goto('/login');
    await page.fill('input[name="email"]', 'kevin@tickets.com');
    await page.fill('input[name="password"]', 'segredo0');
    await page.click('button[type="submit"]');
    await page.waitForURL(/.*dashboard/);
  });

  test('deve exibir cards de estatísticas', async ({ page }) => {
    // Verificar que os 4 cards principais estão visíveis
    await expect(page.locator('text=Total de Tickets')).toBeVisible();
    await expect(page.locator('text=Novos')).toBeVisible();
    await expect(page.locator('text=Em Progresso')).toBeVisible();
    await expect(page.locator('text=Resolvidos')).toBeVisible();
  });

  test('deve exibir gráficos de distribuição', async ({ page }) => {
    // Verificar títulos dos gráficos
    await expect(page.locator('text=Tickets por Prioridade')).toBeVisible();
    await expect(page.locator('text=Tickets por Categoria')).toBeVisible();
  });

  test('deve exibir tabela de tickets recentes', async ({ page }) => {
    // Verificar que a tabela existe
    const table = page.locator('table').first();
    await expect(table).toBeVisible();

    // Verificar headers da tabela
    await expect(page.locator('th:has-text("#")')).toBeVisible();
    await expect(page.locator('th:has-text("Título")')).toBeVisible();
    await expect(page.locator('th:has-text("Prioridade")')).toBeVisible();
    await expect(page.locator('th:has-text("Status")')).toBeVisible();
  });

  test('deve ter navegação funcionando', async ({ page }) => {
    // Clicar em "Tickets" no menu
    await page.click('a[href="/tickets"]');
    await page.waitForURL(/.*tickets/);

    // Verificar que está na página de tickets
    await expect(page.locator('text=Tickets')).toBeVisible();
  });

  test('deve exibir informações do usuário logado', async ({ page }) => {
    // Verificar nome do usuário
    await expect(page.locator('text=Kevin')).toBeVisible();

    // Verificar função
    await expect(page.locator('text=admin')).toBeVisible();
  });
});
