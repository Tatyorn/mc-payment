## 📖 Contexto (Story)
O desafio consiste em construir uma aplicação que gira pedidos de pagamento multi-moeda numa empresa com colaboradores distribuídos por diversos países. O sistema deve permitir que utilizadores autenticados submetam despesas na sua moeda local, procurem taxas de câmbio em tempo real através de uma API externa e encaminhem os pedidos para aprovação por parte da equipa financeira.

---

## 🛠️ Requisitos Técnicos

### 1. Autenticação (Authentication)
* Implementar um sistema de autenticação completo utilizando **Laravel Passport** ou outro mecanismo seguro de API Tokens.
* Endpoints necessários:
    * `POST /api/register` (Registo)
    * `POST /api/login` (Autenticação)
    * `POST /api/logout` (Revogação do Token)

### 2. Gestão de Pedidos de Pagamento (Payment Requests)
* **Criar (Create):** Submissão de um pedido de pagamento em moeda local. A taxa de conversão deve ser capturada automaticamente no momento da criação.
* **Ler (Read):** Listagem de pedidos de pagamento com suporte a filtros por `status` (Ex: Pendente, Aprovado, Rejeitado, Expirado) e visualização de detalhes de um registo específico.
* **Atualizar (Update):** Funcionalidade restrita ao perfil de Finanças (`finance role`) para aprovar ou rejeitar um pedido pendente.
* **Imutabilidade:** A taxa de câmbio registada no momento da criação do pedido **não pode ser alterada** após a submissão.

### 3. Integração com API de Câmbio (Exchange Rate)
* Integrar com uma API pública e gratuita de taxas de câmbio (Ex: [ExchangeRate-API](https://api.exchangerate-api.com)).
* Obter em tempo real a taxa de conversão entre a **Moeda Local** do colaborador e o **Euro (EUR)**.
* Gravar no modelo de dados: a taxa obtida (`rate`), a fonte da API (`source`) e a data/hora exata da consulta (`timestamp`).
* Retornar obrigatoriamente o valor convertido para EUR na resposta da API.

### 4. Automatização e Rotinas (Scheduled Tasks)
* Implementar uma rotina agendada (Task Scheduling) que expire de forma automática todos os pedidos de pagamento que permaneçam com o estado `pending` por mais de **48 horas**.

### 5. Validação de Dados
* Implementar camadas rígidas de validação para todos os dados de entrada (`FormRequests`), garantindo campos obrigatórios, códigos de moeda ISO válidos (Ex: BRL, USD, EUR) e formatos numéricos corretos para os montantes.

---

## 🧪 Estrutura de Testes (Unit & Integration Testing)
* Desenvolver testes automatizados (utilizando **Pest PHP** ou **PHPUnit**) cobrindo, no mínimo, as funcionalidades críticas do negócio:
    * Autenticação e restrição de rotas baseadas em funções (Roles).
    * Fluxo de conversão e cálculo monetário com mocks de API externa.
    * Validação de expiração de registros após 48 horas.

---

## 🗄️ Dados Iniciais (Seeders)
* O projeto deve incluir *Seeders* configurados para popular a base de dados com pelo menos **5 colaboradores** distribuídos por diferentes países e utilizando diferentes moedas correntes.

---

## 📊 Critérios de Avaliação
O código será revisto com foco nos seguintes pilares de qualidade:
1. Estrutura, arquitetura e organização de ficheiros dentro das convenções do Laravel.
2. Design de API RESTful claro e semântico.
3. Tratamento de erros gracioso com respostas e mensagens significativas em formato JSON.
4. Cobertura e abordagem de testes automatizados.
5. Legibilidade do código, separação de conceitos e aderência ao *Clean Code*.

---

## 📤 Instruções para Submissão
O envio da solução deve cumprir rigorosamente as seguintes exigências da Buzzvel:
* **Ficheiro README.md:** Obrigatório no repositório. Projetos sem instruções claras de configuração local serão desconsiderados.
* **Demonstração:** Disponibilizar um vídeo descritivo ou URL pública com a aplicação a funcionar.
* **Link de Envio:** A submissão do repositório Git e do vídeo deve ser feita no formulário oficial do ClickUp: [Formulário de Submissão Buzzvel](https://forms.clickup.com/6647387/f/6avjv-18455/PLUYAZ40HA3XTOOEFW).

---

### ⏱️ Timeline do Processo
* **Entrega:** Até 5 dias após o recebimento do teste.
* **Revisão do Código:** Entre 10 a 15 dias após a entrega.
* **Fase Seguinte:** Entrevista técnica com a equipa de engenharia.
