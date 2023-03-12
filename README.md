## Banco-Laravel

## Usuários

Comuns: 
- Nome;
- email;
- cpf;
- senha.

Lojistas:
- Nome;
- Razão social;
- cpnj;
- email;
- senha.

CPF/CNPJ e e-mails devem ser únicos no sistema. 

### Cadastrar usuário
Só pode escolher dois tipos de usuário: comum ou lojista. Dependendo do tipo a ser escolhido é requerido cpf ou cnpj, razão social.

- Usuário comum:

POST /register
```json
{
  "type": "common",
  "name": "Nome completo",
  "email": "seuemail@gmail.com",
  "cpf_cnpj": "23565292091",
  "password": "Senha@123"
}
```

- Usuário lojista:

POST /register
```json
{
  "type": "shopkeeper",
  "name": "Nome fantasia",
  "company_name": "Brenda Pic",
  "email": "emaillojista@gmail.com",
  "cpf_cnpj": "99869272000190",
  "password": "Senha@123"
}
```

Ao cadastrar o usuário retorna o número da conta e agência.

## Login
Para o login é necessário informar o cpf e a senha.

POST /login
```json
{
  "cpf_cnpj": "23565292091",
  "password": "Senha@123"
}
```

## Transferências
Para trannsferir dinheiro, apenas os usuários do tipo "common" são permitidos, os do tipo "shopkeeper" apenas recebem.

POST /transfer
```json
{
  "value": "5",
  "account_transfer": "7804491"
}
```

## Tratamento de erros

Cadastro: 
- Não é possível cadastrar caso um cpf ou email já tenha sido cadastrado (ERRO: 400);
- Todos os campos são obrigatórios e do tipo string (ERRO: 400);
- Gera token

Login: 
- CPF/CNPJ e senha são obrigatŕoias (ERRO: 400);
- Caso o cpf/cnpj e a senha não forem iguais ao que está cadastrado não permite entrar (ERRO: 401);
- Gera token.

Transferência:
- Todos os campos são obrigatórios (ERRO: 400);
- Só transfere se o usuário estivr logado de fato;
- Apenas contas do tipo "common" podem realizar transferências;
- Contas do tipo "shopkeeper" só recebem, não fazem transferências (ERRO: 402);
- É necessário informar o valor e a conta destino;
- Só transfere se tiver dinheiro na conta e tiver saldo suficiente e a conta destino for encontrada (ERRO: 422).

## Padrão SOLID
- Single Responsibility Principle: princípio da responsabilidade única;
- Open Closed Principle: princípio do aberto/fechado;
- Liskov Substitution Principle: princípio da substituição de Liskov;
- Interface Segregation Principle: princípio da segregação de Interfaces;
- Dependency Inversion Principle: princípio da inversão de dependência.
## Ferramentas utilizadas

<p align="center"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/27/PHP-logo.svg/800px-PHP-logo.svg.png" width="150"></p>
<p align="center"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="250"></p>

<p align="center"><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTrc0DOS0bdLt5fERra-MNjX4gE4MMO_Ann2A&usqp=CAU" width="250"></p>

<p align="center"><img src="https://repository-images.githubusercontent.com/44662669/f3f5c080-808b-11ea-9713-2bea65875d95" width="250"></p>

PHP 7.4 - Laravel 8