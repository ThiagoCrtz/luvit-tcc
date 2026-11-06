# Luvit

## Guia rápido para executar

Este é o repositório completo do TCC Luvit: uma rede social para descobrir filmes e jogos, criar listas, avaliar títulos, favoritar conteúdos, seguir perfis e entrar com Google.

### Requisitos

- PHP 8.2 ou superior com cURL, Fileinfo, Mbstring, OpenSSL, PDO SQLite/MySQL, XML e Zip.
- Composer.
- Node.js 20.19+ ou 22.12+ e npm.
- MySQL ou SQLite.

### Instalação

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
npm ci
npm run build
php artisan serve --host=127.0.0.1 --port=8000
```

No PowerShell, use `Copy-Item .env.example .env`. Neste computador, o PHP portátil está em `.tools/php/php.exe`.

### APIs e login Google

Preencha no `.env`: `TMDB_API_KEY`, `RAWG_API_KEY`, `IGDB_CLIENT_ID`, `IGDB_CLIENT_SECRET`, `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET` e `GOOGLE_REDIRECT_URI`.

O catálogo de jogos usa IGDB como fonte principal, RAWG como compatibilidade e dados locais como fallback. O callback local do Google deve ser exatamente `http://127.0.0.1:8000/auth/google/callback`.

### Testes

```bash
php artisan test
php artisan luvit:check
php artisan luvit:check --catalogues
npm run build
```

Os testes usam SQLite em memória e respostas simuladas. O comando `luvit:check --catalogues` consulta os provedores reais sem exibir chaves.

Nunca publique `.env`, tokens, segredos OAuth, banco SQLite, fotos de usuários ou logs. Esses arquivos estão bloqueados pelo `.gitignore`.

## Repositório do TCC

Aplicação Laravel completa para descobrir filmes e jogos, criar listas, publicar avaliações, favoritar títulos, seguir perfis e entrar com Google.

Rede social para descobrir filmes e jogos, criar listas, salvar títulos para depois, publicar avaliações e acompanhar outras pessoas. Laravel 12, Blade, JavaScript, Tailwind CSS e Vite.

## Abrir neste computador

Na pasta externa do projeto, dê dois cliques em **Iniciar Luvit.cmd** e abra **http://127.0.0.1:8000**. Mantenha a janela do servidor aberta; pressione Ctrl+C para encerrá-lo.

O PHP portátil e o Composer estão em .tools neste computador. Essa pasta não faz parte do código versionado. O banco local SQLite fica em database/database.sqlite e as fotos em storage/app/public. Faça cópia desses dois locais para preservar seus dados.

Crie sua conta pelo cadastro. A conta usada na verificação local é **demo@luvit.local**, senha **LuvitDemo2026!**. Ela serve apenas para desenvolvimento e não deve ser publicada.

## Instalar em outro ambiente

Requisitos: PHP 8.2+, Composer, Node.js 20.19+ ou 22.12+, extensões PHP cURL, Fileinfo, Mbstring, OpenSSL, PDO SQLite ou MySQL, XML e Zip. GD é usada nos testes de imagem.

Execute dentro da pasta que contém artisan:

```sh
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
npm ci
npm run build
php artisan serve --host=127.0.0.1 --port=8000
```

No PowerShell, copie a configuração com Copy-Item .env.example .env. O script scripts/start.ps1 prepara as pastas de execução, aplica migrações pendentes e inicia o servidor. No Windows, cria uma junção para as fotos públicas quando necessário.

Preencha TMDB_API_KEY e RAWG_API_KEY no .env. Essas chaves ficam no servidor e não devem ser colocadas no JavaScript ou em arquivos públicos. A configuração local usa sessões e cache em arquivos e fila síncrona, dispensando Redis e tabelas de infraestrutura.

Para desenvolver com atualização automática da interface, use composer run dev. Para compilar alterações visuais, use npm run build e recarregue o navegador.

## Recursos implementados

- Cadastro, login por e-mail, lembrar sessão e logout.
- Catálogos e detalhes de filmes (TMDB) e jogos (RAWG).
- Busca de filmes, jogos e pessoas; categorias de filmes pelo rodapé.
- Curtidas e títulos salvos para depois, persistidos no perfil.
- Avaliações de 1 a 5 estrelas, comentários e respostas vinculadas ao mesmo título.
- Listas: criar, excluir, adicionar e remover itens, compartilhar acesso público.
- Perfil público e edição de nome, biografia, foto e senha com confirmação da senha atual.
- Seguir e deixar de seguir pessoas, seguidores e feed.

O catálogo depende dos provedores. Em caso de indisponibilidade, a listagem fica vazia e um título não encontrado retorna 404; o perfil continua lendo os itens já salvos no banco local.

## Login com Google

A integração está implementada, mas precisa de um cliente OAuth. Configure GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET e GOOGLE_REDIRECT_URI. Para o endereço local padrão, autorize exatamente:

```text
http://127.0.0.1:8000/auth/google/callback
```

Sem credenciais, o botão informa que a integração ainda não foi configurada. O login por e-mail continua disponível. A validação real do OAuth depende dessas credenciais e da autorização no Google.

## Verificação

```sh
php artisan test
php artisan luvit:check
php artisan luvit:check --catalogues
npm run build
```

Neste computador, use .tools/php/php.exe no lugar de php se ele não estiver no PATH. O Composer portátil pode ser executado com .tools/php/php.exe .tools/composer.phar install.

Os testes usam SQLite em memória e catálogos simulados, sem alterar suas contas e listas locais. O comando luvit:check --catalogues consulta os provedores reais sem exibir chaves.

## Publicação futura

A publicação deve ocorrer após a revisão visual. O servidor precisa apontar exclusivamente para public, ter HTTPS e permitir escrita em storage e bootstrap/cache.

Use um .env próprio com APP_ENV=production, APP_DEBUG=false, APP_URL com o domínio real, SESSION_SECURE_COOKIE=true, credenciais do banco e chaves dos catálogos. Gere uma chave própria para a instalação e preserve-a nas atualizações.

Para MySQL, crie um banco vazio e configure DB_CONNECTION=mysql, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME e DB_PASSWORD. As migrações criam as tabelas da aplicação em uma instalação nova. Para reutilizar um banco antigo do TCC, faça backup e reconcilie o esquema antes de aplicar as migrações.

Na primeira instalação:

```sh
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan storage:link
npm ci
npm run build
php artisan optimize
```

Ajuste o callback OAuth ao domínio publicado. O servidor embutido artisan serve é destinado ao desenvolvimento local. Não envie .env, .tools, dados SQLite, logs ou a conta de demonstração para a publicação. Planeje backup do banco e das fotos. O login Google precisa ser testado no domínio final.
