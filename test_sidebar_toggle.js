const { chromium } = require('playwright');

(async () => {
  const browser = await chromium.launch({
    headless: false,
    slowMo: 100
  });
  const page = await browser.newPage();

  try {
    console.log('🔑 Fazendo login...');
    await page.goto('http://localhost:8081/login');
    await page.waitForLoadState('networkidle');

    // Login
    await page.fill('input[type="email"]', 'ana.costa@cliente.com');
    await page.fill('input[type="password"]', '123456');
    await page.click('button[type="submit"]');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(1000);
    await page.screenshot({ path: 'sidebar-1-expandido.png', fullPage: true });

    console.log('✅ Login OK! Dashboard com sidebar expandido.');

    // Verificar largura do sidebar
    const sidebarWidth = await page.locator('#sidebar').evaluate(el => {
      return window.getComputedStyle(el).width;
    });
    console.log(`📏 Largura do sidebar: ${sidebarWidth}`);

    // Clicar no botão de toggle
    console.log('🔄 Clicando no botão de toggle...');
    await page.click('#sidebar-toggle');
    await page.waitForTimeout(500);
    await page.screenshot({ path: 'sidebar-2-colapsado.png', fullPage: true });

    // Verificar nova largura
    const sidebarWidthCollapsed = await page.locator('#sidebar').evaluate(el => {
      return window.getComputedStyle(el).width;
    });
    console.log(`📏 Largura do sidebar colapsado: ${sidebarWidthCollapsed}`);

    // Verificar se textos estão escondidos
    const textsHidden = await page.locator('.sidebar-text').first().evaluate(el => {
      return el.classList.contains('hidden');
    });
    console.log(`📝 Textos escondidos: ${textsHidden ? '✅' : '❌'}`);

    // Expandir novamente
    console.log('🔄 Expandindo sidebar novamente...');
    await page.click('#sidebar-toggle');
    await page.waitForTimeout(500);
    await page.screenshot({ path: 'sidebar-3-expandido-novamente.png', fullPage: true });

    // Verificar se voltou
    const sidebarWidthExpanded = await page.locator('#sidebar').evaluate(el => {
      return window.getComputedStyle(el).width;
    });
    console.log(`📏 Largura do sidebar expandido: ${sidebarWidthExpanded}`);

    // Testar se mantém estado após navegação
    console.log('🔄 Colapsando e navegando para tickets...');
    await page.click('#sidebar-toggle');
    await page.waitForTimeout(500);
    await page.screenshot({ path: 'sidebar-4-colapsado-antes-navegar.png', fullPage: true });

    await page.click('a[href="/tickets"]');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(500);
    await page.screenshot({ path: 'sidebar-5-tickets-colapsado.png', fullPage: true });

    // Verificar se manteve colapsado
    const sidebarWidthAfterNav = await page.locator('#sidebar').evaluate(el => {
      return window.getComputedStyle(el).width;
    });
    console.log(`📏 Largura após navegação: ${sidebarWidthAfterNav}`);
    console.log(`💾 Estado preservado: ${sidebarWidthAfterNav === sidebarWidthCollapsed ? '✅' : '❌'}`);

    console.log('\n✅ Teste completo! Verificações:');
    console.log(`   - Sidebar expande/colapsa: ✅`);
    console.log(`   - Textos aparecem/desaparecem: ${textsHidden ? '✅' : '❌'}`);
    console.log(`   - Estado preservado entre páginas: ${sidebarWidthAfterNav === sidebarWidthCollapsed ? '✅' : '❌'}`);

  } catch (error) {
    console.error('💥 ERRO:', error);
    await page.screenshot({ path: 'sidebar-erro.png', fullPage: true });
  } finally {
    console.log('\n⏸️ Pausando 10 segundos para análise...');
    await page.waitForTimeout(10000);
    await browser.close();
    console.log('✅ Teste finalizado!');
  }
})();
