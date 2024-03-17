# Simulação PicPay

Este guia explica como configurar e executar esse projeto corretamente. 

Antes de colocar em prática todo o desenvolvimento do projeto, modelei a estrutura de banco de dados, onde ficou mais facil mapear e visualizar toda a logica de negócio da aplicação. Confira o diagrama [aqui](https://lucid.app/lucidchart/2e916aff-51ab-41f4-a2da-bf5ca99101f6/edit?invitationId=inv_f7890475-b095-4497-962f-b884bec50129).

## Pré-requisitos

- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/)

## Configuração

1. Clone o repositório da aplicação Laravel:

   ```bash
   git clone https://github.com/seu-usuario/seu-repositorio-laravel.git

2. Navegue até o diretório da aplicação:

   ```bash
   cd seu-repositorio-laravel

3. Crie um arquivo .env na raiz do diretório da aplicação, baseando-se no arquivo .env.example. Você pode usar o comando cp no Unix/Linux ou copy no Windows:

   ```bash
   cp .env.example .env
   
4. Edite o arquivo .env com as configurações de banco de dados e outras configurações específicas da sua aplicação, se necessário.

### Banco de dados

- Praticamente toda a configuracao do banco de dados do projeto já é feita pelo container, portanto nao é necessario nenhuma configuracao manual.


### Fila

- Para execução da fila, estou usando o servico de mensageria da AWS, o SQS. Portanto é necessário seguir os seguintes passos:
    
1. Crie um usuario administrativo na sua conta da AWS. Voce pode conferir como fazer isso [aqui](https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-setting-up.html).

2. Crie uma nova fila com o nome de "main-queue".

3. Adicione sua chave de acesso da aws na variável AWS_ACCESS_KEY_ID.

4. Adicione sua chave de acesso secreta da aws na variável AWS_SECRET_ACCESS_KEY.

5. Adicione a url (https://sqs.us-east-2.amazonaws.com/<account-id>) na variavel SQS_PREFIX 

6. Agora sua fila ja deve estar funcionando corretamente. Para monitorar o envio de JOB na fila, voce pode usar o seguinte comando:

    ```bash
   php artisan queue:listen sqs
    
## Execução

1. Execute o comando Docker Compose para construir os contêineres e iniciar a aplicação:

   ```bash
   docker-compose up -d --build

2. Após a construção e inicialização dos contêineres, você pode acessar a aplicação em seu navegador web através do seguinte endereço:
    
    ```bash
   http://localhost:8000
   
3. Para entrar no bash do projeto com o docker, execute o seguinte comando: 

    ```bash
   docker exec -it pic-pay_laravel /bin/sh
   
   ou
   
   docker exec -it <id-container-aplicacao> /bin/sh
