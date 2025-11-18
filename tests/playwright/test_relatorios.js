const { chromium } = require('playwright');

(async () => {
  const browser = await chromium.launch({
    headless: false,
    slowMo: 100
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
    console.log('🔑 Fazendo login como agente...');
    await page.goto('http://localhost:8081/login');
    await page.waitForLoadState('networkidle');

    // Login como agente
    await page.fill('input[type="email"]', 'joao.silva@tickets.com');
    await page.fill('input[type="password"]', '123456');
    await page.click('button[type="submit"]');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(1000);

    console.log('✅ Login realizado');

    // Verificar se menu tem link Relatórios
    const relatoriosLink = await page.locator('a[href="/relatorios"]').count();
    console.log(`📊 Link 'Relatórios' no menu: ${relatoriosLink > 0 ? '✅' : '❌'}`);

    // Clicar em Relatórios
    console.log('📊 Acessando página de relatórios...');
    await page.click('a[href="/relatorios"]');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(1000);
    await page.screenshot({ path: 'relatorios-1-inicial.png', fullPage: true });

    console.log('✅ Página carregada!');

    // Verificar elementos principais
    const titulo = await page.locator('h1:has-text("Relatórios e Métricas")').count();
    console.log(`   - Título: ${titulo > 0 ? '✅' : '❌'}`);

    const filtros = await page.locator('form[action="/relatorios"]').count();
    console.log(`   - Formulário de filtros: ${filtros > 0 ? '✅' : '❌'}`);

    // Verificar 5 cards KPI
    const cards = await page.locator('.grid.grid-cols-1.gap-5.sm\\:grid-cols-2.lg\\:grid-cols-5 > div').count();
    console.log(`   - Cards KPI: ${cards} (esperado: 5) ${cards === 5 ? '✅' : '❌'}`);

    // Verificar valores dos KPIs
    const kpis = await page.evaluate(() => {
      const cards = document.querySelectorAll('.grid.grid-cols-1.gap-5.sm\\:grid-cols-2.lg\\:grid-cols-5 > div');
      return Array.from(cards).map(card => {
        const label = card.querySelector('dt')?.textContent?.trim();
        const value = card.querySelector('dd')?.textContent?.trim();
        return { label, value };
      });
    });

    console.log('\n📊 Valores dos KPIs:');
    kpis.forEach(kpi => {
      console.log(`   - ${kpi.label}: ${kpi.value}`);
    });

    // Testar filtro de período
    console.log('\n🔍 Testando filtros...');
    await page.fill('input[name="periodo_inicio"]', '2025-11-01');
    await page.fill('input[name="periodo_fim"]', '2025-11-18');
    await page.screenshot({ path: 'relatorios-2-filtros-preenchidos.png', fullPage: true });

    await page.click('button[type="submit"]');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(1000);
    await page.screenshot({ path: 'relatorios-3-apos-filtro.png', fullPage: true });

    console.log('✅ Filtro aplicado!');

    // Verificar se tem erros no console
    if (consoleErrors.length > 0) {
      console.log('\n🚨 ERROS NO CONSOLE:', consoleErrors);
    } else {
      console.log('\n✅ Nenhum erro no console!');
    }

    // Verificar conteúdo do body para erros PHP
    const bodyText = await page.textContent('body');
    if (bodyText.toLowerCase().includes('error') || bodyText.toLowerCase().includes('exception')) {
      console.log('🚨 POSSÍVEL ERRO NA PÁGINA!');
      console.log('Primeiros 500 caracteres:', bodyText.substring(0, 500));
    }

  } catch (error) {
    console.error('💥 ERRO:', error);
    await page.screenshot({ path: 'relatorios-erro.png', fullPage: true });
  } finally {
    console.log('\n⏸️ Pausando 10 segundos para análise...');
    await page.waitForTimeout(10000);
    await browser.close();
    console.log('✅ Teste finalizado!');
  }
})();
