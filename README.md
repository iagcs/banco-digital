# Simulação Banco Digital

Este guia explica como configurar e executar esse projeto corretamente. 

Antes de colocar em prática todo o desenvolvimento do projeto, modelei a estrutura de banco de dados, onde ficou mais facil mapear e visualizar toda a logica de negócio da aplicação. Confira o diagrama [aqui](https://lucid.app/lucidchart/2e916aff-51ab-41f4-a2da-bf5ca99101f6/edit?invitationId=inv_f7890475-b095-4497-962f-b884bec50129).

Este projeto utiliza o serviço de mensageria da AWS, o SQS (Amazon Simple Queue Service), para se comunicar com o microsserviço de envio de e-mail. A comunicação acontece ao enviar uma mensagem contendo os dados da transação para o microsserviço "mail-service" através da fila "mail-queue".

## Pré-requisitos

- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/)

## Configuração

1. Clone o repositório da aplicação Laravel:

   ```bash
   git clone https://github.com/iagcs/banco-digital.git

2. Navegue até o diretório da aplicação:

   ```bash
   cd banco-digital

3. Crie um arquivo .env na raiz do diretório da aplicação, baseando-se no arquivo .env.example. Você pode usar o comando cp no Unix/Linux ou copy no Windows:

   ```bash
   cp .env.example .env
   
4. Edite o arquivo .env com as configurações de banco de dados e outras configurações específicas da sua aplicação, se necessário.

### Banco de dados

- Praticamente toda a configuracao do banco de dados do projeto já é feita pelo container, portanto nao é necessario nenhuma configuracao manual.


### Fila

- Para execução da fila, estou usando o servico de mensageria da AWS, o SQS. Portanto é necessário seguir os seguintes passos:
    
1. Se não houver, crie um usuario administrativo na sua conta da AWS. Voce pode conferir como fazer isso [aqui](https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-setting-up.html).

2. Crie uma nova fila com o nome de "main-queue".

3. Configure as variáveis do SQS:

    ```dotenv
    AWS_ACCESS_KEY_ID=<sua-chave-de-acesso-aws>
    AWS_SECRET_ACCESS_KEY=<sua-chave-secreta-de-acesso-aws>
    AWS_DEFAULT_REGION=<regiao-da-fila>
    AWS_SQS_REGION=<regiao-da-fila>
    
    SQS_PREFIX=https://sqs.<regiao-da-fila>.amazonaws.com/<id-da-sua-conta>
    
## Execução

1. Execute o comando Docker Compose para construir os contêineres e iniciar a aplicação:

   ```bash
   docker-compose up -d --build

2. Após a construção e inicialização dos contêineres, você pode acessar a aplicação em seu navegador web através do seguinte endereço:
    
    ```bash
   http://localhost:8000
   
3. Para entrar no bash do projeto com o docker, execute o seguinte comando: 

    ```bash
   docker exec -it banco-digital_laravel /bin/sh
   
   ou
   
   docker exec -it <id-container-aplicacao> /bin/sh
   
4. Para popular o banco:

    ```bash
   php artisan db:seed
   
5. Para executar a fila:

    ```bash
   php artisan queue:listen sqs
   
6. Por fim, para executar os testes da aplicacao:
    ```bash
   php artisan test
    ```

# Documentação da API

A rota `/transaction` é usada para realizar transações entre usuários.

## Requisição

### Método

`POST`

### Endpoint

`/transaction`

### Corpo da Requisição

| Parâmetro | Tipo   | Descrição                                        |
|-----------|--------|--------------------------------------------------|
| payee     | string | ID do usuário destinatário da transação (UUID)   |
| payer     | string | ID do usuário remetente da transação (UUID)      |
| value     | number | Valor da transação                               |

### Exemplo de Corpo da Requisição

```json
{
    "payee": "c2dcec43-662b-4de8-b3c4-99672c4c4e02",
    "payer": "66bdff29-42bc-4b22-a0f2-1faecda0e2cd",
    "value": 100.00
}
```

## Resposta

### Códigos de Resposta

| Código | Descrição                                |
|--------|------------------------------------------|
| 200    | Transação iniciada com sucesso           |
| 400    | Erro na validação dos dados da transação |

## Regras de Validação

| Campo  | Regras                                                                                                                                                                                    |
|--------|-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| payee  | ID do usuário destinatário da transação. Deve ser um UUID válido e corresponder a um usuário existente.                                                                                   |
| payer  | ID do usuário remetente da transação. Deve ser um UUID válido e corresponder a um usuário existente. Além disso, o remetente deve ser um usuário do tipo comun para realizar a transação. |
| value  | Valor da transação. Deve ser um número e o remetente deve ter uma carteira cadastrada e com saldo suficiente para realizar a transação.                                                   |



