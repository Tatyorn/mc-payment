# MC Payment - Guia de Configuração e Acesso

## Requisitos

- Docker
- PHP 8.3+
- Composer

## Localização (i18n)

O sistema suporta **Português (Brasil)** e **Inglês**.

| Variável | Arquivo | Padrão |
|----------|---------|--------|
| `APP_LOCALE` | `.env` | `en` |
| `APP_FALLBACK_LOCALE` | `.env` | `en` |

Para mudar para português, edite o `.env`:

```
APP_LOCALE=pt_BR
```

Os arquivos de tradução estão em `lang/` organizados por domínio:
- `messages.php` — textos gerais da interface
- `auth.php` — formulários de login/registro
- `payment.php` — páginas de solicitação de pagamento
- `enums.php` — rótulos de moedas e status
- `errors.php` — mensagens de erro e respostas da API
- `validation.php` — nomes de atributos de validação

## Setup Rápido

### 1. Clonar e instalar dependências

```bash
git clone <repo-url> mc-payment
cd mc-payment
composer install
```

### 2. Configurar ambiente

```bash
cp .env.example .env
php artisan key:generate
```

### 3. Iniciar containers Docker (Laravel Sail)

```bash
vendor/bin/sail up -d
```

### 4. Executar migrations e seeders

```bash
vendor/bin/sail artisan migrate
vendor/bin/sail artisan db:seed
```

### 5. Acessar a aplicação

Abra o navegador em: `http://localhost`

A API estará disponível em: `http://localhost/api`

---

## Usuários Seed

| Nome | Email | Senha | Role | Moeda |
|------|-------|-------|------|-------|
| Finance User | finance@mcpayment.com | password | finance | EUR |
| Alice Johnson | alice@mcpayment.com | password | employee | USD |
| Carlos Silva | carlos@mcpayment.com | password | employee | BRL |
| Yuki Tanaka | yuki@mcpayment.com | password | employee | JPY |
| James Smith | james@mcpayment.com | password | employee | GBP |
| Marie Dubois | marie@mcpayment.com | password | employee | EUR |

> Todos os usuários usam a senha `password`.

---

## Testando a API

### Exemplo com cURL

```bash
# Login como financeiro
curl -X POST http://localhost/api/login \
  -H "Content-Type: application/json" \
  -d '{"email": "finance@mcpayment.com", "password": "password"}'

# Login como employee
curl -X POST http://localhost/api/login \
  -H "Content-Type: application/json" \
  -d '{"email": "alice@mcpayment.com", "password": "password"}'
```

Use o token retornado nas requisições seguintes:

```bash
TOKEN="1|abc123..."

# Criar payment request
curl -X POST http://localhost/api/payment-requests \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"description": "Novo monitor", "amount": 800.00, "currency": "USD"}'

# Listar payment requests
curl http://localhost/api/payment-requests \
  -H "Authorization: Bearer $TOKEN"

# Listar com filtro de status
curl "http://localhost/api/payment-requests?status=pending" \
  -H "Authorization: Bearer $TOKEN"
```

---

## Comandos Úteis

### Desenvolvimento

```bash
vendor/bin/sail up -d              # Iniciar containers
vendor/bin/sail stop               # Parar containers
vendor/bin/sail artisan test       # Rodar testes
vendor/bin/sail artisan test --compact --filter=PaymentRequest  # Testes específicos
vendor/bin/sail artisan migrate:fresh --seed  # Resetar banco e seed
vendor/bin/sail artisan payment:expire        # Expirar requests pendentes > 48h
vendor/bin/sail artisan queue:work            # Processar jobs
```

### Testes

```bash
vendor/bin/sail artisan test --compact
```

---

## Scheduled Task

O comando `payment:expire` é agendado para rodar a cada hora via scheduler do Laravel.

Para testar manualmente:

```bash
vendor/bin/sail artisan payment:expire
```
