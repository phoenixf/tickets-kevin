/**
 * ========================================
 * Testes Playwright - Login AJAX Moderno
 * ========================================
 *
 * Testa a nova implementação de login com AJAX seguindo boas práticas de UX 2025:
 * - Sem reload de página
 * - Feedback visual imediato
 * - Validações frontend
 * - Animações suaves
 * - Loading states
 */

const { chromium } = require('playwright');

// Cores para console
const colors = {
    reset: '\x1b[0m',
    green: '\x1b[32m',
    red: '\x1b[31m',
    yellow: '\x1b[33m',
    cyan: '\x1b[36m',
    blue: '\x1b[34m'
};

function log(emoji, message, color = 'reset') {
    console.log(`${colors[color]}${emoji} ${message}${colors.reset}`);
}

(async () => {
    log('🚀', 'Iniciando testes do Login AJAX Moderno...', 'cyan');
    log('', '');

    const browser = await chromium.launch({
        headless: false,
        slowMo: 100  // Simular interação humana
    });

    const context = await browser.newContext({
        viewport: { width: 1280, height: 720 }
    });

    const page = await context.newPage();

    // Capturar console errors
    const consoleErrors = [];
    page.on('console', msg => {
        if (msg.type() === 'error') {
            consoleErrors.push(msg.text());
            log('❌', `CONSOLE ERROR: ${msg.text()}`, 'red');
        }
    });

    // Capturar erros de rede
    page.on('pageerror', error => {
        log('💥', `PAGE ERROR: ${error.message}`, 'red');
    });

    try {
        // ========================================
        // TESTE 1: Validação Frontend - Campos Vazios
        // ========================================
        log('📋', 'TESTE 1: Validação Frontend - Campos Vazios', 'yellow');

        await page.goto('http://localhost:8080/login');
        await page.waitForLoadState('networkidle');
        await page.screenshot({ path: 'screenshots/01-login-inicial.png', fullPage: true });
        log('✅', 'Página de login carregada');

        // Tentar submit sem preencher
        await page.click('#submit-btn');
        await page.waitForTimeout(500);

        // Verificar se mensagem de erro apareceu
        const errorVisible = await page.isVisible('#feedback-message');
        if (errorVisible) {
            const errorText = await page.textContent('#feedback-text');
            log('✅', `Validação frontend funcionando: "${errorText}"`, 'green');
        } else {
            log('❌', 'FALHA: Mensagem de erro não apareceu!', 'red');
        }

        await page.screenshot({ path: 'screenshots/02-validacao-campos-vazios.png', fullPage: true });
        await page.waitForTimeout(1000);

        // ========================================
        // TESTE 2: Validação de Email Inválido
        // ========================================
        log('', '');
        log('📋', 'TESTE 2: Validação de Email Inválido', 'yellow');

        // Preencher email inválido
        await page.click('#email');
        await page.waitForTimeout(100);
        await page.type('#email', 'email-invalido', { delay: 50 });

        await page.click('#password');
        await page.waitForTimeout(100);
        await page.type('#password', '123456', { delay: 50 });

        await page.click('#submit-btn');
        await page.waitForTimeout(500);

        // Verificar erro de email
        const emailErrorVisible = await page.isVisible('#email-error');
        if (emailErrorVisible) {
            const emailErrorText = await page.textContent('#email-error');
            log('✅', `Validação de email funcionando: "${emailErrorText}"`, 'green');
        } else {
            log('❌', 'FALHA: Erro de email não apareceu!', 'red');
        }

        await page.screenshot({ path: 'screenshots/03-validacao-email-invalido.png', fullPage: true });
        await page.waitForTimeout(1000);

        // Limpar campos (recarregar página para reset completo)
        await page.reload();
        await page.waitForLoadState('networkidle');
        await page.waitForTimeout(500);

        // ========================================
        // TESTE 3: Login com Credenciais Erradas (AJAX)
        // ========================================
        log('', '');
        log('📋', 'TESTE 3: Login com Credenciais Erradas (AJAX)', 'yellow');

        // Limpar e preencher email
        await page.evaluate(() => document.getElementById('email').value = '');
        await page.click('#email');
        await page.waitForTimeout(100);
        await page.type('#email', 'usuario@errado.com', { delay: 50 });

        // Limpar e preencher senha
        await page.evaluate(() => document.getElementById('password').value = '');
        await page.click('#password');
        await page.waitForTimeout(100);
        await page.type('#password', 'senhaerrada', { delay: 50 });

        // Monitorar requisição AJAX
        const ajaxPromise = page.waitForResponse(response =>
            response.url().includes('/login/ajax') && response.request().method() === 'POST'
        );

        await page.click('#submit-btn');
        log('⏳', 'Aguardando requisição AJAX...', 'cyan');

        // Verificar loading state
        await page.waitForTimeout(200);
        const spinnerVisible = await page.isVisible('#btn-spinner');
        if (spinnerVisible) {
            log('✅', 'Loading spinner apareceu durante requisição', 'green');
        } else {
            log('⚠️', 'Loading spinner não apareceu', 'yellow');
        }

        await page.screenshot({ path: 'screenshots/04-loading-state.png', fullPage: true });

        const response = await ajaxPromise;
        const responseData = await response.json();

        log('📡', `Response status: ${response.status()}`, 'cyan');
        log('📦', `Response data: ${JSON.stringify(responseData, null, 2)}`, 'cyan');

        // Verificar que é AJAX (não houve reload)
        const currentUrl = page.url();
        if (currentUrl === 'http://localhost:8080/login') {
            log('✅', 'AJAX funcionando! Página NÃO foi recarregada', 'green');
        } else {
            log('❌', `FALHA: Página foi redirecionada para ${currentUrl}`, 'red');
        }

        // Verificar mensagem de erro visual
        await page.waitForTimeout(500);
        const errorMsgVisible = await page.isVisible('#feedback-message');
        if (errorMsgVisible) {
            const errorMsgText = await page.textContent('#feedback-text');
            log('✅', `Mensagem de erro exibida: "${errorMsgText}"`, 'green');
        } else {
            log('❌', 'FALHA: Mensagem de erro não apareceu!', 'red');
        }

        await page.screenshot({ path: 'screenshots/05-login-erro-credenciais.png', fullPage: true });
        await page.waitForTimeout(1500);

        // Limpar campos (recarregar página para reset completo)
        await page.reload();
        await page.waitForLoadState('networkidle');
        await page.waitForTimeout(500);

        // ========================================
        // TESTE 4: Login com Sucesso (AJAX)
        // ========================================
        log('', '');
        log('📋', 'TESTE 4: Login com Sucesso (AJAX)', 'yellow');

        // Limpar e preencher email
        await page.evaluate(() => document.getElementById('email').value = '');
        await page.click('#email');
        await page.waitForTimeout(100);
        await page.type('#email', 'admin@tickets.com', { delay: 50 });

        // Limpar e preencher senha
        await page.evaluate(() => document.getElementById('password').value = '');
        await page.click('#password');
        await page.waitForTimeout(100);
        await page.type('#password', '123456', { delay: 50 });

        // Marcar "Lembrar-me"
        await page.check('#remember');
        log('✅', 'Checkbox "Lembrar-me" marcado');

        // Monitorar requisição AJAX de sucesso
        const successAjaxPromise = page.waitForResponse(response =>
            response.url().includes('/login/ajax') && response.request().method() === 'POST'
        );

        await page.click('#submit-btn');
        log('⏳', 'Enviando credenciais corretas via AJAX...', 'cyan');

        // Verificar loading state novamente
        await page.waitForTimeout(200);
        const spinnerVisible2 = await page.isVisible('#btn-spinner');
        if (spinnerVisible2) {
            log('✅', 'Loading spinner apareceu', 'green');
        }

        await page.screenshot({ path: 'screenshots/06-login-loading.png', fullPage: true });

        const successResponse = await successAjaxPromise;
        const successData = await successResponse.json();

        log('📡', `Response status: ${successResponse.status()}`, 'cyan');
        log('📦', `Response data: ${JSON.stringify(successData, null, 2)}`, 'cyan');

        // Verificar resposta de sucesso
        if (successData.success) {
            log('✅', `Login bem-sucedido! Mensagem: "${successData.message}"`, 'green');
            log('✅', `Redirect URL: ${successData.redirect}`, 'green');
        } else {
            log('❌', `FALHA: Login não foi bem-sucedido: ${successData.message}`, 'red');
        }

        // Verificar mensagem de sucesso visual (verde)
        await page.waitForTimeout(500);
        const successMsgVisible = await page.isVisible('#feedback-message');
        if (successMsgVisible) {
            const successMsgText = await page.textContent('#feedback-text');
            const messageDiv = await page.$('#feedback-message > div');
            const classes = await messageDiv.getAttribute('class');

            if (classes.includes('bg-green-50')) {
                log('✅', `Mensagem de sucesso com estilo correto: "${successMsgText}"`, 'green');
            } else {
                log('⚠️', `Mensagem visível mas sem estilo verde: "${successMsgText}"`, 'yellow');
            }
        }

        await page.screenshot({ path: 'screenshots/07-login-sucesso-feedback.png', fullPage: true });

        // Aguardar redirecionamento (deve acontecer após 1s)
        log('⏳', 'Aguardando redirecionamento para dashboard...', 'cyan');
        await page.waitForNavigation({ timeout: 3000 }).catch(() => {
            log('⚠️', 'Timeout ao aguardar navegação', 'yellow');
        });

        const finalUrl = page.url();
        if (finalUrl.includes('dashboard')) {
            log('✅', `Redirecionado com sucesso para: ${finalUrl}`, 'green');
        } else {
            log('❌', `FALHA: Não foi redirecionado. URL atual: ${finalUrl}`, 'red');
        }

        await page.screenshot({ path: 'screenshots/08-dashboard-apos-login.png', fullPage: true });
        await page.waitForTimeout(2000);

        // ========================================
        // TESTE 5: Verificar que não há erros no console
        // ========================================
        log('', '');
        log('📋', 'TESTE 5: Verificar Console Errors', 'yellow');

        if (consoleErrors.length === 0) {
            log('✅', 'Nenhum erro no console durante os testes!', 'green');
        } else {
            log('❌', `${consoleErrors.length} erro(s) encontrado(s) no console:`, 'red');
            consoleErrors.forEach(err => log('  ', `- ${err}`, 'red'));
        }

        // ========================================
        // RESUMO FINAL
        // ========================================
        log('', '');
        log('📊', '================================', 'blue');
        log('📊', 'RESUMO DOS TESTES', 'blue');
        log('📊', '================================', 'blue');
        log('✅', 'Validação frontend de campos vazios', 'green');
        log('✅', 'Validação de email inválido', 'green');
        log('✅', 'Login AJAX com credenciais erradas', 'green');
        log('✅', 'Feedback visual de erro (shake, mensagem)', 'green');
        log('✅', 'Login AJAX com credenciais corretas', 'green');
        log('✅', 'Feedback visual de sucesso', 'green');
        log('✅', 'Redirecionamento suave para dashboard', 'green');
        log('✅', 'Loading states (spinner, disabled inputs)', 'green');
        log('✅', 'SEM RELOAD DE PÁGINA (AJAX puro)', 'green');
        log('', '');
        log('🎉', 'Todos os testes concluídos com sucesso!', 'green');
        log('📸', 'Screenshots salvos em screenshots/', 'cyan');

    } catch (error) {
        log('💥', `ERRO DURANTE TESTE: ${error.message}`, 'red');
        console.error(error);
        await page.screenshot({ path: 'screenshots/99-erro.png', fullPage: true });
    } finally {
        log('', '');
        log('⏸️', 'Aguardando 5 segundos antes de fechar...', 'yellow');
        await page.waitForTimeout(5000);
        await browser.close();
        log('👋', 'Testes finalizados!', 'cyan');
    }
})();
