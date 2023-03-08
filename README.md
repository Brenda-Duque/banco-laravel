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

## Padrão MVC
Model - View - Controller
- Uniformidade na estrutura do software;
- As aplicações ficam mais fácies de manter;
- Facilita a documentação.
## Ferramentas utilizadas

<p align="center"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/27/PHP-logo.svg/800px-PHP-logo.svg.png" width="150"></p>
<p align="center"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="250"></p>
<p align="center"><img src="https://s2.glbimg.com/WcVu50imQYm5GntBKg-J5RkOAQA=/1200x/smart/filters:cover():strip_icc()/i.s3.glbimg.com/v1/AUTH_08fbf48bc0524877943fe86e43087e7a/internal_photos/bs/2021/y/M/W5GFw3Qh2YwD5XkhUM2Q/2012-04-17-mysql-logos.gif" width="250"></p>