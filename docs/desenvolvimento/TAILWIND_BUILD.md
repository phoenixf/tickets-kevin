# Tailwind CSS - Build Local

## Resumo

Configurado Tailwind CSS v3.4.18 para build local otimizado.

**Benefícios:**
- ✅ **32KB minificado** vs ~3.4MB do CDN
- ✅ Independente de CDNs externos (sem problema com Cloudflare down)
- ✅ Performance superior (carregamento ~100x mais rápido)
- ✅ Apenas classes utilizadas no projeto

---

## Configuração Atual

### Arquivos

1. **`tailwind.config.js`** - Configuração do Tailwind
   - Dark mode: `class` (usa `dark:` prefix)
   - Cores customizadas: palette `dark` (50-900)
   - Content paths: `app/Views/**/*.php`, `public/**/*.js`

2. **`public/css/tailwind.input.css`** - CSS de entrada
   ```css
   @tailwind base;
   @tailwind components;
   @tailwind utilities;
   ```

3. **`public/css/tailwind.output.css`** - CSS compilado (32KB minificado)
   - Gerado automaticamente pelo build
   - **NÃO editar manualmente**

4. **`package.json`** - Scripts de build
   ```json
   "build:css": "tailwindcss -i ./public/css/tailwind.input.css -o ./public/css/tailwind.output.css --minify",
   "watch:css": "tailwindcss -i ./public/css/tailwind.input.css -o ./public/css/tailwind.output.css --watch"
   ```

---

## Comandos

### Build de Produção (uma vez)
```bash
npm run build:css
```
Gera CSS minificado de 32KB com todas as classes usadas no projeto.

### Watch Mode (desenvolvimento)
```bash
npm run watch:css
```
Monitora mudanças nos arquivos PHP/JS e rebuild automaticamente.

---

## Como Usar

### Opção 1: CDN (Desenvolvimento - Atual)

**Quando usar:** Desenvolvimento rápido, protótipos, testes

**Prós:**
- Zero configuração
- Todas as classes disponíveis
- Hot reload automático

**Contras:**
- 3.4MB não minificado
- Dependente de CDN externo
- Carregamento lento

**Como está agora** em `app/Views/layouts/main.php`:
```html
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        darkMode: 'class',
        theme: { extend: { colors: { dark: {...} } } }
    }
</script>
```

### Opção 2: Build Local (Produção - Recomendado)

**Quando usar:** Produção, performance crítica, CDN down

**Prós:**
- 32KB minificado (~100x menor)
- Independente de CDNs
- Performance superior
- Cache do navegador

**Contras:**
- Precisa rebuild após adicionar classes novas
- Requer `npm run build:css` antes de deploy

**Para ativar**, substituir em `app/Views/layouts/main.php`:

**REMOVER:**
```html
<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        darkMode: 'class',
        theme: { extend: { colors: { dark: {...} } } }
    }
</script>
```

**ADICIONAR:**
```html
<!-- Tailwind CSS (Build Local) -->
<link rel="stylesheet" href="/css/tailwind.output.css">
```

**E também atualizar** `app/Views/layouts/auth.php` da mesma forma.

---

## Workflow Recomendado

### Desenvolvimento Local

1. **Use CDN** para agilidade:
   ```html
   <script src="https://cdn.tailwindcss.com"></script>
   ```

2. **Ou use Watch Mode** para build automático:
   ```bash
   npm run watch:css
   ```
   - Deixe rodando em terminal separado
   - Rebuild automático ao editar arquivos

### Deploy/Produção

1. **Build antes do deploy:**
   ```bash
   npm run build:css
   ```

2. **Troque CDN por build local** nos layouts:
   ```html
   <link rel="stylesheet" href="/css/tailwind.output.css">
   ```

3. **Commit o CSS gerado:**
   ```bash
   git add public/css/tailwind.output.css
   git commit -m "chore: build Tailwind CSS para produção"
   ```

---

## Adicionando Classes Novas

Se adicionar classes Tailwind que **nunca foram usadas antes**, você precisa rebuild:

```bash
npm run build:css
```

**Exemplo:**
```html
<!-- Nova classe que nunca foi usada -->
<div class="bg-purple-950 text-yellow-300">...</div>
```

Após salvar, rode `npm run build:css` para incluir `bg-purple-950` e `text-yellow-300` no CSS final.

---

## Cores Customizadas

A palette `dark` está configurada em `tailwind.config.js`:

```javascript
colors: {
  dark: {
    50: '#1a1a1a',   // Mais escuro
    100: '#2d2d2d',
    200: '#404040',
    300: '#525252',
    400: '#666666',
    500: '#808080',
    600: '#999999',
    700: '#b3b3b3',
    800: '#cccccc',
    900: '#e6e6e6',  // Mais claro
  }
}
```

**Uso:**
```html
<div class="bg-dark-100 text-dark-800">
  Background escuro com texto claro
</div>
```

---

## Problemas Conhecidos

### "caniuse-lite is outdated"

Aviso inofensivo. Para resolver:
```bash
npx update-browserslist-db@latest
```

### CSS não atualiza após adicionar classe

1. Certifique-se que o arquivo está em `app/Views/**/*.php` ou `public/**/*.js`
2. Rode `npm run build:css` manualmente
3. Ou use `npm run watch:css` para rebuild automático

### CDN vs Build - Qual usar?

| Critério | CDN | Build Local |
|----------|-----|-------------|
| Desenvolvimento | ✅ Melhor | ⚠️ OK (watch) |
| Produção | ❌ Não | ✅ Melhor |
| Performance | ❌ 3.4MB | ✅ 32KB |
| Dependências | ❌ Externa | ✅ Local |
| Manutenção | ✅ Zero | ⚠️ Rebuild |

**Recomendação:**
- Dev local: CDN ou Watch
- Produção: **Build local sempre**

---

## Versão Atual

**Tailwind CSS:** v3.4.18 (estável)

**Por que não v4?**
- v4.x ainda está em beta/desenvolvimento
- v3.x é a versão estável recomendada
- Compatibilidade total com projeto existente

---

## Notas Cloudflare

Como mencionado pelo usuário, o Cloudflare teve uma queda global que afetou CDNs.

**Impacto com CDN:**
- ❌ Site fica sem estilos se CDN cair
- ❌ Dependência de infraestrutura externa

**Impacto com Build Local:**
- ✅ Zero impacto, CSS é servido localmente
- ✅ Independente de CDNs externos

**Conclusão:** Build local elimina este ponto único de falha.

---

**Última atualização:** 2025-11-18
**Versão Tailwind:** 3.4.18
