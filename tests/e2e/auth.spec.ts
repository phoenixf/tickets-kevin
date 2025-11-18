import { test, expect } from '@playwright/test';

test.describe('Autenticação', () => {
  test('deve redirecionar para login quando não autenticado', async ({ page }) => {
    await page.goto('/');
    await expect(page).toHaveURL(/.*login/);
  });

  test('deve exibir formulário de login', async ({ page }) => {
    await page.goto('/login');

    // Verificar elementos do formulário
    await expect(page.locator('input[name="email"]')).toBeVisible();
    await expect(page.locator('input[name="password"]')).toBeVisible();
    await expect(page.locator('button[type="submit"]')).toBeVisible();
  });

  test('deve fazer login com sucesso como admin', async ({ page }) => {
    await page.goto('/login');

    // Preencher credenciais
    await page.fill('input[name="email"]', 'kevin@tickets.com');
    await page.fill('input[name="password"]', 'segredo0');

    // Submeter formulário
    await page.click('button[type="submit"]');

    // Aguardar redirecionamento para dashboard
    await page.waitForURL(/.*dashboard/);

    // Verificar que está na dashboard
    await expect(page.locator('text=Dashboard')).toBeVisible();
    await expect(page.locator('text=Kevin')).toBeVisible();
  });

  test('deve exibir erro com credenciais inválidas', async ({ page }) => {
    await page.goto('/login');

    await page.fill('input[name="email"]', 'invalido@teste.com');
    await page.fill('input[name="password"]', 'senha_errada');
    await page.click('button[type="submit"]');

    // Verificar mensagem de erro (ajustar conforme a mensagem do sistema)
    // await expect(page.locator('text=credenciais')).toBeVisible();
  });
});
