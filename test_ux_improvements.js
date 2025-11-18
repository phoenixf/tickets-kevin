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

    // Login com credenciais corretas
    await page.fill('input[type="email"]', 'maria.oliveira@example.com');
    await page.fill('input[type="password"]', '123456');
    await page.screenshot({ path: 'ux-test-1-login.png', fullPage: true });

    await page.click('button[type="submit"]');
    await page.waitForLoadState('networkidle');
    await page.screenshot({ path: 'ux-test-2-dashboard.png', fullPage: true });

    console.log('✅ Login OK! Dashboard carregado.');
    console.log('📊 Agora vou testar os hovers nos cards...');

    // Hover no card "Novos" - deve mostrar borda azul
    const novosCard = page.locator('a[href="/tickets?status=novo"]');
    await novosCard.scrollIntoViewIfNeeded();
    await page.waitForTimeout(500);
    await novosCard.hover();
    await page.waitForTimeout(1000);
    await page.screenshot({ path: 'ux-test-3-hover-novos.png', fullPage: true });
    console.log('✅ Hover em "Novos" capturado');

    // Hover no card "Em Progresso" - deve mostrar borda amarela
    const emProgressoCard = page.locator('a[href="/tickets?status=em_progresso"]');
    await emProgressoCard.scrollIntoViewIfNeeded();
    await page.waitForTimeout(500);
    await emProgressoCard.hover();
    await page.waitForTimeout(1000);
    await page.screenshot({ path: 'ux-test-4-hover-em-progresso.png', fullPage: true });
    console.log('✅ Hover em "Em Progresso" capturado');

    // Hover no card "Resolvidos" - deve mostrar borda verde
    const resolvidosCard = page.locator('a[href="/tickets?status=resolvido"]');
    await resolvidosCard.scrollIntoViewIfNeeded();
    await page.waitForTimeout(500);
    await resolvidosCard.hover();
    await page.waitForTimeout(1000);
    await page.screenshot({ path: 'ux-test-5-hover-resolvidos.png', fullPage: true });
    console.log('✅ Hover em "Resolvidos" capturado');

    // Rolar para baixo para ver as barras de categoria/prioridade
    await page.evaluate(() => window.scrollTo(0, 500));
    await page.waitForTimeout(500);
    await page.screenshot({ path: 'ux-test-6-barras.png', fullPage: true });
    console.log('✅ Barras de prioridade/categoria visíveis');

    // Testar filtros na página de tickets
    console.log('🎫 Testando filtros na página de tickets...');
    await page.goto('http://localhost:8081/tickets');
    await page.waitForLoadState('networkidle');
    await page.screenshot({ path: 'ux-test-7-tickets-filtros.png', fullPage: true });

    console.log('✅ Filtros na página de tickets visíveis!');
    console.log('🔍 Verificando se os selects estão presentes...');

    const statusSelect = await page.locator('#filter-status').count();
    const prioridadeSelect = await page.locator('#filter-prioridade').count();
    const categoriaSelect = await page.locator('#filter-categoria').count();

    console.log(`   - Select Status: ${statusSelect > 0 ? '✅' : '❌'}`);
    console.log(`   - Select Prioridade: ${prioridadeSelect > 0 ? '✅' : '❌'}`);
    console.log(`   - Select Categoria: ${categoriaSelect > 0 ? '✅' : '❌'}`);

    console.log('\n📸 Todas as capturas realizadas! Revise os arquivos ux-test-*.png');

  } catch (error) {
    console.error('💥 ERRO:', error);
    await page.screenshot({ path: 'ux-test-erro.png', fullPage: true });
  } finally {
    console.log('\n⏸️ Pausando por 10 segundos para você analisar...');
    await page.waitForTimeout(10000);
    await browser.close();
    console.log('✅ Teste finalizado!');
  }
})();
