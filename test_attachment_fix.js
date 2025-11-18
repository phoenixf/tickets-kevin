const { chromium } = require('playwright');

(async () => {
  const browser = await chromium.launch({
    headless: false,
    slowMo: 50
  });
  const page = await browser.newPage();

  // Capturar console errors
  const consoleErrors = [];
  page.on('console', msg => {
    if (msg.type() === 'error') {
      consoleErrors.push(msg.text());
      console.log('❌ CONSOLE ERROR:', msg.text());
    }
  });

  try {
    console.log('🔑 Fazendo login como cliente...');
    await page.goto('http://localhost:8081/login');
    await page.waitForLoadState('networkidle');
    await page.screenshot({ path: 'test-attachment-1-login.png', fullPage: true });

    // Login como cliente
    await page.click('#floatingEmailInput');
    await page.waitForTimeout(150);
    await page.type('#floatingEmailInput', 'maria.oliveira@example.com', { delay: 60 });

    await page.click('#floatingPasswordInput');
    await page.waitForTimeout(150);
    await page.type('#floatingPasswordInput', '123456', { delay: 65 });

    await page.screenshot({ path: 'test-attachment-2-preenchido.png', fullPage: true });

    await page.click('button[type="submit"]');
    await page.waitForLoadState('networkidle');
    await page.screenshot({ path: 'test-attachment-3-dashboard.png', fullPage: true });

    console.log('✅ Login realizado com sucesso!');

    // Ir para um ticket
    console.log('🎫 Abrindo primeiro ticket...');
    await page.goto('http://localhost:8081/tickets');
    await page.waitForLoadState('networkidle');
    await page.screenshot({ path: 'test-attachment-4-tickets.png', fullPage: true });

    // Clicar no primeiro ticket
    const firstTicketLink = await page.locator('a[href^="/tickets/"]:not([href*="edit"]):not([href*="create"])').first();
    await firstTicketLink.scrollIntoViewIfNeeded();
    await page.waitForTimeout(200);
    await firstTicketLink.click();
    await page.waitForLoadState('networkidle');
    await page.screenshot({ path: 'test-attachment-5-ticket-detalhes.png', fullPage: true });

    console.log('✅ Ticket aberto!');

    // Rolar até a seção de anexos
    console.log('📎 Testando upload de anexo...');
    const uploadSection = await page.locator('h3:has-text("Anexos")');
    await uploadSection.scrollIntoViewIfNeeded();
    await page.waitForTimeout(200);
    await page.screenshot({ path: 'test-attachment-6-anexos.png', fullPage: true });

    // Criar arquivo de teste
    const fs = require('fs');
    const testFilePath = '/tmp/test-attachment.txt';
    fs.writeFileSync(testFilePath, 'Teste de anexo - arquivo criado pelo Playwright\nData: ' + new Date().toISOString());

    // Upload do arquivo
    await page.setInputFiles('input[type="file"]', testFilePath);
    await page.waitForTimeout(500);
    await page.screenshot({ path: 'test-attachment-7-arquivo-selecionado.png', fullPage: true });

    console.log('📤 Enviando arquivo...');

    // Clicar em "Enviar Arquivo"
    const submitButton = await page.locator('button:has-text("Enviar Arquivo")');
    await submitButton.scrollIntoViewIfNeeded();
    await page.waitForTimeout(200);
    await submitButton.click();

    // Aguardar resposta
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(1000);
    await page.screenshot({ path: 'test-attachment-8-apos-upload.png', fullPage: true });

    // Verificar se deu erro
    const bodyText = await page.textContent('body');
    if (bodyText.toLowerCase().includes('erro') || bodyText.toLowerCase().includes('error')) {
      console.log('🚨 ERRO DETECTADO NA PÁGINA!');
      console.log('Primeiros 500 caracteres:', bodyText.substring(0, 500));
    } else {
      console.log('✅ Upload realizado sem erros visuais!');
    }

    // Verificar se o anexo apareceu na lista
    const anexosList = await page.locator('.space-y-2 .flex.items-center').count();
    console.log(`📋 ${anexosList} anexo(s) na lista`);

    if (consoleErrors.length > 0) {
      console.log('🚨 ERROS NO CONSOLE:', consoleErrors);
    } else {
      console.log('✅ Nenhum erro no console!');
    }

  } catch (error) {
    console.error('💥 ERRO:', error);
    await page.screenshot({ path: 'test-attachment-erro.png', fullPage: true });
  } finally {
    console.log('\n⏸️ Aguardando 5 segundos para análise...');
    await page.waitForTimeout(5000);
    await browser.close();
    console.log('✅ Teste finalizado!');
  }
})();
