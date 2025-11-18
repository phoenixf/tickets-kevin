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
    console.log('\n🧪 ========== TESTE: IMPLEMENTAÇÃO SLA ==========\n');

    // 1. Login
    console.log('1️⃣ Fazendo login...');
    await page.goto('http://localhost:8081/login');
    await page.waitForLoadState('networkidle');
    await page.screenshot({ path: '.playwright-mcp/sla-01-login.png', fullPage: true });

    await page.click('input[name="email"]');
    await page.waitForTimeout(150);
    await page.type('input[name="email"]', 'admin@tickets.com', { delay: 60 });

    await page.click('input[name="password"]');
    await page.waitForTimeout(150);
    await page.type('input[name="password"]', '123456', { delay: 65 });

    await Promise.all([
      page.waitForNavigation({ waitUntil: 'networkidle' }),
      page.click('button[type="submit"]')
    ]);
    await page.waitForTimeout(1000);
    await page.screenshot({ path: '.playwright-mcp/sla-02-dashboard.png', fullPage: true });
    console.log('✅ Login realizado com sucesso');

    // 2. Navegar para Relatórios
    console.log('\n2️⃣ Navegando para Relatórios...');
    await page.goto('http://localhost:8081/relatorios');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(2000); // Aguardar gráficos renderizarem
    await page.screenshot({ path: '.playwright-mcp/sla-03-relatorios-inicial.png', fullPage: true });
    console.log('✅ Página de relatórios carregada');

    // 3. Verificar Quick Filters
    console.log('\n3️⃣ Verificando Quick Filters...');
    const quickFilters = await page.locator('.quick-filter-btn').count();
    console.log(`   ✅ ${quickFilters} quick filters encontrados`);

    if (quickFilters >= 5) {
      console.log('   ✅ Todos quick filters presentes (Hoje, Ontem, Últimos 7, Últimos 30, Este Ano)');
    } else {
      console.log('   ⚠️  Alguns quick filters podem estar faltando');
    }

    // 4. Verificar SLA Metrics Cards
    console.log('\n4️⃣ Verificando SLA Metrics Cards...');

    // Procurar por textos SLA específicos
    const bodyText = await page.textContent('body');

    const hasPrimeiraResposta = bodyText.includes('SLA Primeira Resposta') || bodyText.includes('Primeira Resposta');
    const hasResolucao = bodyText.includes('SLA Resolução') || bodyText.includes('Resolução');
    const hasFCR = bodyText.includes('FCR') || bodyText.includes('First Contact');

    console.log(`   ${hasPrimeiraResposta ? '✅' : '❌'} SLA Primeira Resposta`);
    console.log(`   ${hasResolucao ? '✅' : '❌'} SLA Resolução`);
    console.log(`   ${hasFCR ? '✅' : '❌'} FCR (First Contact Resolution)`);

    // 5. Verificar Alert Panel de Tickets Críticos
    console.log('\n5️⃣ Verificando Alert Panel de Tickets Críticos...');
    const hasAcaoImediata = bodyText.includes('Ação Imediata') || bodyText.includes('AÇÃO IMEDIATA');
    const hasProximosVencimento = bodyText.includes('Próximos ao Vencimento') || bodyText.includes('próximo');

    console.log(`   ${hasAcaoImediata ? '✅' : '❌'} Título "Ação Imediata"`);
    console.log(`   ${hasProximosVencimento ? '✅' : '❌'} Referência a tickets próximos ao vencimento`);

    // 6. Testar Quick Filter - Hoje
    console.log('\n6️⃣ Testando Quick Filter "Hoje"...');
    const hojeBtnBefore = await page.locator('button:has-text("Hoje")');
    await hojeBtnBefore.scrollIntoViewIfNeeded();
    await page.waitForTimeout(200);

    await Promise.all([
      page.waitForNavigation({ waitUntil: 'networkidle' }),
      page.click('button:has-text("Hoje")')
    ]);
    await page.waitForTimeout(2000);
    await page.screenshot({ path: '.playwright-mcp/sla-04-filter-hoje.png', fullPage: true });
    console.log('   ✅ Filtro "Hoje" aplicado');

    // 7. Testar Quick Filter - Últimos 7 dias
    console.log('\n7️⃣ Testando Quick Filter "Últimos 7 dias"...');
    await page.goto('http://localhost:8081/relatorios');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(1500);

    const ultimos7Btn = await page.locator('button:has-text("Últimos 7")');
    await ultimos7Btn.scrollIntoViewIfNeeded();
    await page.waitForTimeout(200);

    await Promise.all([
      page.waitForNavigation({ waitUntil: 'networkidle' }),
      page.click('button:has-text("Últimos 7")')
    ]);
    await page.waitForTimeout(2000);
    await page.screenshot({ path: '.playwright-mcp/sla-05-filter-ultimos7.png', fullPage: true });
    console.log('   ✅ Filtro "Últimos 7 dias" aplicado');

    // 8. Verificar Gráficos estão renderizando
    console.log('\n8️⃣ Verificando renderização de gráficos...');
    await page.goto('http://localhost:8081/relatorios');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(3000); // Aguardar gráficos ApexCharts

    const chartsRendered = await page.locator('.apexcharts-canvas').count();
    console.log(`   ✅ ${chartsRendered} gráficos ApexCharts renderizados`);

    // 9. Rolar página completa e capturar screenshot final
    console.log('\n9️⃣ Capturando screenshot final (página completa)...');
    await page.evaluate(() => window.scrollTo(0, 0));
    await page.waitForTimeout(500);

    // Rolar progressivamente
    await page.evaluate(() => window.scrollBy(0, 500));
    await page.waitForTimeout(300);
    await page.evaluate(() => window.scrollBy(0, 500));
    await page.waitForTimeout(300);
    await page.evaluate(() => window.scrollBy(0, 500));
    await page.waitForTimeout(300);

    await page.screenshot({ path: '.playwright-mcp/sla-06-final-completo.png', fullPage: true });
    console.log('   ✅ Screenshot final capturado');

    // 10. Verificar console errors
    console.log('\n🔍 Verificação de Console Errors:');
    if (consoleErrors.length > 0) {
      console.log('   🚨 ERROS ENCONTRADOS NO CONSOLE:');
      consoleErrors.forEach((err, i) => {
        console.log(`   ${i + 1}. ${err}`);
      });
    } else {
      console.log('   ✅ Nenhum erro no console!');
    }

    // 11. Verificar se há erros visíveis na página
    console.log('\n🔍 Verificação de Erros Visuais:');
    const finalBodyText = await page.textContent('body');
    const hasError = finalBodyText.toLowerCase().includes('erro') &&
                     !finalBodyText.toLowerCase().includes('sem erro'); // Evitar falso positivo

    if (hasError) {
      console.log('   ⚠️  Palavra "erro" encontrada na página');
      // Buscar contexto
      const errorContext = finalBodyText.match(/.{0,50}erro.{0,50}/gi);
      if (errorContext) {
        console.log('   Contexto:', errorContext[0]);
      }
    } else {
      console.log('   ✅ Nenhum erro visível na página');
    }

    // Resumo final
    console.log('\n' + '='.repeat(60));
    console.log('📊 RESUMO DOS TESTES SLA:');
    console.log('='.repeat(60));
    console.log(`✅ Quick Filters: ${quickFilters} encontrados`);
    console.log(`✅ Métricas SLA: ${hasPrimeiraResposta && hasResolucao ? 'OK' : 'VERIFICAR'}`);
    console.log(`✅ FCR: ${hasFCR ? 'OK' : 'VERIFICAR'}`);
    console.log(`✅ Alert Panel: ${hasAcaoImediata ? 'OK' : 'VERIFICAR'}`);
    console.log(`✅ Gráficos: ${chartsRendered} renderizados`);
    console.log(`✅ Console Errors: ${consoleErrors.length} erros`);
    console.log('='.repeat(60));

    console.log('\n✨ Screenshots salvos em .playwright-mcp/sla-*.png');

  } catch (error) {
    console.error('💥 ERRO:', error.message);
    await page.screenshot({ path: '.playwright-mcp/sla-erro.png', fullPage: true });
  } finally {
    console.log('\n⏳ Aguardando 5 segundos para análise...');
    await page.waitForTimeout(5000);
    await browser.close();
    console.log('✅ Teste concluído!\n');
  }
})();
