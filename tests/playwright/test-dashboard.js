const { chromium } = require('playwright');

(async () => {
  const browser = await chromium.launch({
    headless: false,
    slowMo: 50
  });
  const page = await browser.newPage();

  // Capturar erros do console
  const consoleErrors = [];
  page.on('console', msg => {
    if (msg.type() === 'error') {
      consoleErrors.push(msg.text());
      console.log('❌ CONSOLE ERROR:', msg.text());
    }
  });

  try {
    console.log('📍 Navegando para login...');
    await page.goto('http://localhost:8081/login');
    await page.waitForLoadState('networkidle');
    await page.screenshot({ path: '1-login.png', fullPage: true });

    console.log('🔑 Fazendo login...');
    // Preencher formulário de login
    await page.click('input[name="email"]');
    await page.waitForTimeout(150);
    await page.type('input[name="email"]', 'admin@tickets.com', { delay: 60 });

    await page.click('input[name="password"]');
    await page.waitForTimeout(150);
    await page.type('input[name="password"]', '123456', { delay: 65 });

    // Clicar no botão de login
    await page.waitForTimeout(200);
    await page.screenshot({ path: '2-antes-login.png', fullPage: true });

    await page.click('button[type="submit"]');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(1000);

    await page.screenshot({ path: '3-apos-login.png', fullPage: true });

    console.log('📊 Verificando dashboard...');

    // Verificar se está no dashboard
    const url = page.url();
    console.log('URL atual:', url);

    // Capturar conteúdo do body
    const bodyText = await page.textContent('body');
    if (bodyText.toLowerCase().includes('erro')) {
      console.log('🚨 ERRO NA PÁGINA:', bodyText.substring(0, 200));
    }

    // Verificar cards de estatísticas
    console.log('\n📈 Estatísticas do Dashboard:');

    // Tentar encontrar os cards
    const cards = await page.locator('.bg-white.rounded-lg.shadow, .bg-gradient-to-br').all();
    console.log(`Total de cards encontrados: ${cards.length}`);

    for (let i = 0; i < cards.length; i++) {
      const cardText = await cards[i].textContent();
      console.log(`Card ${i + 1}: ${cardText.trim().substring(0, 100)}`);
    }

    // Verificar tabela de tickets recentes
    console.log('\n🎫 Tickets Recentes:');
    const rows = await page.locator('table tbody tr').all();
    console.log(`Total de linhas na tabela: ${rows.length}`);

    if (rows.length === 0) {
      console.log('⚠️  NENHUM TICKET RECENTE ENCONTRADO!');
      // Verificar se há mensagem de "nenhum ticket"
      const noTicketsMsg = await page.locator('text=Nenhum ticket').count();
      if (noTicketsMsg > 0) {
        console.log('💬 Mensagem "Nenhum ticket" encontrada');
      }
    } else {
      for (let i = 0; i < Math.min(5, rows.length); i++) {
        const rowText = await rows[i].textContent();
        console.log(`Ticket ${i + 1}: ${rowText.trim().substring(0, 150)}`);
      }
    }

    // Screenshot final
    await page.screenshot({ path: '4-dashboard-completo.png', fullPage: true });

    // Verificar console errors
    if (consoleErrors.length > 0) {
      console.log('\n🚨 ERROS NO CONSOLE:', consoleErrors);
    }

    console.log('\n✅ Teste concluído! Verifique os screenshots gerados.');

  } catch (error) {
    console.error('💥 ERRO:', error);
    await page.screenshot({ path: 'erro.png', fullPage: true });
  } finally {
    await page.waitForTimeout(3000);
    await browser.close();
  }
})();
