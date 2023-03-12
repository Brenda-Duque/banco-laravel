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

<p align="center"><img src="https://s2.glbimg.com/WcVu50imQYm5GntBKg-J5RkOAQA=/1200x/smart/filters:cover():strip_icc()/i.s3.glbimg.com/v1/AUTH_08fbf48bc0524877943fe86e43087e7a/internal_photos/bs/2021/y/M/W5GFw3Qh2YwD5XkhUM2Q/2012-04-17-mysql-logos.gif" width="250"></p>

<p align="center"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAakAAAB3CAMAAACOsU+CAAAA4VBMVEX///977E0dOUh98E187k0AKTt+8k0AJjkYNkUVNEQAIjbY3N4AIzcdOEgAJDd/9E0aM0gAHjMLL0AYL0iyub0rRFIXLkgVKkgALD0ZMkhQYm0TJkg1Slfv8fL29/iNlpx/i5Pj5uhzf4Z030wRI0jIzdCYoadoyEtAVWHM0dRUokqhqq94500AGTArVEi8wsZx2UxguUtJjkk+eEknTUgwXkharksgP0g2aUhrzkxFhUlWpUphcHlQm0oOHkc8dUlJW2Y0ZUhDgUlkwEtqeIAjRkgpUUgAECoLGEcAAEcAAB+SKInfAAAag0lEQVR4nO1diVvaytfWkJ2EJWxhJyAIRQWte1GpYtvv9///QV/mnEmYZAYCFqvt5X3uc+/FhJDMO2eZs0wODvb4h9FseqNRv99PN5utj76XPcRIF9uLYck186alaZaZV/TGrDMeNT/6vvZgkR7XNE1xDEOXQti2bjiKppU6kz1bnwOThZF3DWkFbN3RrPl4T9ZHY9TVLcdeRVMAw83Uinu79YEoNjIMTXauWq74qPvw/1Mu5OzlQUNz2nvB+iAMHCW0S7lCpW6fPT9cvxwf391N745frr9cfH8tV8q5kC0nP/M++p7/ixjrSsBBrlw/u7+eHmbVVCol+//IMvmPmlVPXh5u7XrBDrnq7rn6w+iXAnnKlY/OvvVSWZ+fQx6yqh4e31frVUqWo7U/+tb/U2jW8siTXa37NGVVEUkMW6njr5Uy5crVJx99+/8dFDPUKS+Un459YVpHEyUr23u4qSBXen6xdy3+CFozC3mqHt331ksTC/Xw+pVy5Tj9j36I/wL6hoP26ejrNLsxT6gEv+XQudDze2v17hifgoWyKz+Ot+IJuTq5r+eAZ622Xwi/LzomClTlyybmiUf27qyCGlDa++vviZkGw1w5m25uoOJy9fCIGtAZffTT/LtozV3QfPX7bOptPBGqssdSFY3V3q94Lwwd1HznW1uoCFInV6gB8/uV1fsAiareTNXf4YmIVer+CKgy91S9B2pAVOHs5LcECqnKPhyBsTLTH/1U/yAWChna8vfD3yfKR/YaqNKVvQe4a7QzIFFXwjjs26gCt6K0X1ftFpM8EvV2n4+j6htQ5dQ++tH+LXiwjspd7kb1Uaoe6hCt2AeWdokhCZ7bUm93IuVDfQZn3dwvq3aHDvEm7Me7nRLlO+tXZAmsG3tTtSukT8ncP7rO7pQon6pDiTiAzuyjH/CfQYPovvJXIVHyoSwn+oOrjqeOwVTtw0o7wgB036topNXUYW86nSYshtXDk94JOZdD9oGYKl3a7oaa/XFnVpvXZt32uL9fkIVAv+/xmBtoOfvy9FquVOq/HtYasJ5UqFQqN9+/nPCXSF2SfJWyhf/ntUum5joGgeMomYy0KO6z/YAZiSKV7zndl5qeHVWh9rL8ZT1Tv/yzbDtXqZ5zEUP57pHMg8ymY93snjpM8TumT5TT2Z4s6k7YNjf+8jRXpWOVxNQjPc9+fOCoyt6XiVOx2Oxmiq4jiWCcdt51EP4K1Ig7UT/nuMie5aQtmfJ16At35gkU2eY3MjedfFDjSVSfYehhOXV+H+odkXR87oyThdT5kbQ9U7lLTomqXyqbChXGHkl5pzGsLbqz2lBSNFCGeuPdB+LTYwYixYtC9hZFyvZdiiSP4v98h4JqSn71LMs3vmDYVrJQYexRMjKddLBWbnmTdsNydXfwvqPwF8BDkeKXUjIqntzrl5eX4946og5PXl5ezjFyJJV5S6V+A6FKdv8kcCXcWpxTbzz8395X7zhiKyX3YOTtwomakpOCTKSjIHsPUpW75UiXZVK0bifGlMawWhAHNPZW6oCUuNg3vEil7iC6UBUHLkRsYThCJJ7qA3H/tGLCrUCUeG+QVmBCbHjlGy80qfMKKrNNg7byFDwQ+5L/gtwjJBrz9bfSAiul7CsvxCD+hF054QdevQamKtcbh9fRA7RfBYEn9E4SHPU0MKXt4+5CtEzfglSfBLVIqS9lYIq3YCsgHyJTN4JwbeqFCJUyXnsvfWKm9NIfevK/DRNlFRupB2TqZeMkcAqaB2xJwBRmP4z1eXpgyhi+8xO30v3ieDAYT9Krw1PpybjdbpNz1gp4yz9vQM5bH0RujuCsYvLOHd4ELifcNKLrkGY2UdJCvShsJ1OHqRtgyhbF3dWvxDHU1t4rytQah2JQavjocn9vSSUfUod/coccYHzOdHtuaJqmuK6iae5c2O2f7kr+McdxyDlw5QB6xCUaLfSMfyU8jz2tZDDEtYoNNzjLrcV9qnSJeaD+PEN+13V/inRPSSfKT+Teqc/VLZlSr4gxsqsCo0f9k/XeQhp6F9zVJ/RNXdcNXj1OMjo5oHAHipr/97DiJt3Ro9tqGIrGjYlXy7MBYltnwDqvo4bJ7v3AnqYzNY4DV2F+0dCMKFdp/4moFvGGZnCmJajn9/IrnYbs09ZMgdtgF4Sr5B7pLXU6q2nwtQQknteUcrYgeGtyqmaBj2hyD0g0RmAdRzUuRu8jE+sdmuTpcOmGwe2VwjDVPqVHjci2N3Skg0fwhlrskG7O2B9MZ4K5OWEYPRWonjExU/WpyBTRYFKFDzOtZAqlsNwTXu5HLtEIQYhiXSgDAl9KXIW06DPy88BhQsOdYNAMXxEpSsCaG7GdRXA/bSfjNmqzoWu5y+9olvYz/OUuxiddyxnWag0tE8T/HUfRMpmflKm0g3emk190qQi6rHoHpn4SomhkWie8m4JHXxAzJQmr0LOgy6Q6n19cyRS1bELi8aC71kKDCEjaavNcJBPLiMcw+rTXlUssp8mBwPBB1Ex3NKXWHheL4/acKhuNCSiOgCjD6gRDPUBtaSzavnexvK8x6GnDHeB5rVEH+9eNTrvYDz2VtIZ/tBod8otdAzcjcJjpCkz5IjQ6pTdXGjbcvGg+N1aaqUP1ElgWC5wQ1K+vCwucUi/EUK1PXdCBKq30OzzQj3FL1glmdCZ2ddAYoaTVDMMqdZYFHR5tFTOXsweE2igxU6UJTRUxOfdAAFxWcaZ18lWFLRdpofq0ZuFdFSW4U3fpEwFTZrpFJMmROqi+m4JBapFHKX8RyRStKrLLm7cT0KiG2LLJsC7mNFcUc5zB9ko+YSjjlsywyTKMDEEs3g7KUgu8mOL/arG6mzYU4i9XecCsLkUmSov4XLE1O9FE8SVHWolLNWTSJYt1olr4t2X9DzClpckF8911+gYcivqxcEMQGqC93LxBh9YhieNPspxLdCmoUJF27hV3DfrRjTpsJL9mzMcubwaBVyUY+BbvUUEOdTnkUKFlxfxTSN857NKgaZE5bMUkf0BsGuNzTEBDxruSILS5dF+BKYP02Jjr5zCsYB6FvloQb+Uj4yshTytrvgIZZM7GxDDAJmPJ1QZCFTjReENFlJ/TAZsUdQvB618bbMR1gUOnBVoybj0Hg5tnpk4xolQDtAh/+nKugCwq8ayaR2JCUibgD5iSjESiDoou2V9MWKyH4e8tArQ+UyeQ0Vp1QbL2TYwV0X5wwtVCsKpoYmgw8jciOb6KO+WkDWb5+gxkQ2f4BQb48+EyFqM4QbAzXAUjCKgZTLAiSAu/jG877PRJ0xx3YunWgLh+P8TK6gYdCqFqXAFaeFG5FmlMdP741Wn8lvLB8sQxGwPODQRLlmE5hLiu1gSjFLUdOHRrfRgYdK3PDKLGMQByzBI4j1ASoh0hEG+UX+mD7NtBsIYylRxCI4oj952voEiph0/gcduvuNWBrKpM56//iS3CTPkf8X++oG2z77Iqt0VC6ltZSoonwc1LCqVK0t183Ang5aTtosoihiqipFqwGFoT8gguF7g5sIC2OGZBRbLKDvUad60xey1Yxeu64Cfh24GbQ5myEkuMyb3xgfTe9cOzDURJRxi+UKf3t19fgvNS/qfbMK4hnzzc3tL8FlYhSXb9+8N1PLKLniEfYOAxUIIFpz/bzFJEo400KSY65MnJOMKAsr4luAIJhhG8veBL6CpyNwhuF+tSgFPMzwBQnoEjKbZlBDAfAref2qnkqDTRD9V7NT6kv8p0C7gyOgfq9WMhV32kpZupl3o5lzv6jkzIU6nif7oC25Q6p0VKZD/AmF8hQ+Jjo7bfZkdZxlZ0xWa4gniErTMxVyuYomRFwxITkZcVKG7AFCdT4FLyTEVkCtWqKMwJtxXMNWQqIRtEMPd/s3DBMVWhg1S4xDHu1YG4RxATLIoIxQ2iRL5tugBisnTTEJ+rqzhTxxsz5RMybphuYLB0pbH8Vtz4wIOD0cCBXnIIlkIUQmMQYQo9Bc6PAQeZXfuCG5Lh1hFtlhzQcbwtC34y8DCRqQ2qtoZrmcrdohNHzQ8tqUDZIJ/I92gts68QUMSy13SvU56pO5K8F4WJxfAGpWDrOsk4DWdd3EEjkw1nKBxZOgSt0w30SkQO8AM3vznZnIk9FfbPTdDfumhVCBlBW8JDwJQtPC8KYCpejx4wZVfvkEP1okyZU5nDVfgkv9CTqWsuZ59yK5iCMgveuV2D/iwMfZod+jewGktDBR/ROW9GTQO4bLylaI6Kg85iVps3GiUdI6gBC7j+4rKdIMVskAKo4wPJbJACezKkhgAlDP4xTG1S40MmZPV5lUzZlZcs/gGlqHyhhrIRfur9ihJzVV6l/baUKRzXbp5ylQ/ECKxEqOTQ4/PCIV3Gc9oCSzFqNxyNtpHouk4VdSgvMIjxO0xz62GYHeF6OUDEhxghU7oANKLOMjVPHgjwKL5yTNXpI9hHGEiXfxBPMFfBdEb2inyyHzF0m30mZ9u/0NVTzwr0qxxTKWCKzyElwFuYeDenVN10uWVLsL4E1sKBhtBCJHY/kExHtD9/yNQAxSJKAYSYorsKgZ6LlSV6sLYIXNu+IvgdFixTCUULAOIx5uJeuvzyKtULdLwhfyufXNXrR5dBjBw+3dD+Gzn1XKnXK9/QSbygurBSlrjLgkfxhj6ACboWwfOAng90D8QsAqvlZdgjSmwMilKwA7KNGSpFs0wnwtSBg7F05h69oSgaC0LlzhiHYYSx9EAlThKYsrdlCla+V/H1lJw6md5ThYdGTE5NX+7CGBH5dHwYrqfU3st5D64ROInV1/PeYTzygUo184ai5TQ+dmCsYU05x0OgckIbQrRXoKfATDHuQRdjH7ajWXqtOxgXJ5N+2oOEyZKpPmRVDIUGslrpDqwWdDfmxGGjg+O06S2NZhYkQUP/BX7d/95K5PGKGzPVhmiSIPYj0w1awqKIaKsv2S1d8ImmEnNXcooP/akQo9i44Y0FVI+GooPeN87JmsEG1waM0QIOlgkr3GlIUozo22LaUaYOxhjMdzIkpVdyUeYMhffzFJQMy/XPkxQLlKo7DPUmyFRSJerBFkwRzW7bwoAq9eEEBesrIR9icVJlKvoOTfom35QAczY7gV4zGI4mqwmpVqKEkkXP0r2gSVon7oLHmfJPDOsodFSWujYUzK6uSVUpcRLgfwyTaTsaJZVZBXe8KVNA/aOolijwAKvPW2Q9sJI9912cQyYVNPrbqvlwtUjj8CMwR53w70ykbqn+PBK6CFu2gFF/UDnVyzF1kK6xBSy6k5HEUY5Jgz3PcPJDdv2BXnryOmljpsADFWfTabJJHGkXI4X10dxSGpH9YSfnp1bAw/oynNutZZ/BIuKXU5UH6i8SfKB7A2i8N8MzdVA0bMkxM8TjME+lzsr1n/9V28lbeN6wHb02Tg1hjCKCjZlqkpknLiJ7S3aeVt1+E2f7oYzsbVso4YMHWVasUGpSzthLQlDWJQMPnJ0Gcxo8docv6aRlZixTC/8S2sJLT4q+x7FaJppDV7KtdhPP44/jqi9xmb8xUwdkHcYFKei4/9i24kW9X1N1K09J2EkYs0xGE14FE+TZIQBOVlR9QkxkhSYFUVryP+GKEgJLgogejT+wTNX8a5vJAdMmqWZR1qwNMWLeSbrO5kyRO7X5zlyCN1SRYb1fXVjJjqpxs7ZsDqD9wvAYfCKiBK6rw56IdRYtGmQKpA2T8KcCCYEkNcMU2T7K7STfEBHStbMOLauRdJ3NmQI9LerJCSszxeMuRPZrdTW3KulY3HarlwCTaG8BcRyIC0zGKzpvwUWy+jhQoRCNoCDwlL8uaEuGqaa5mXvazyeNL9YDC3K+UWzOFAyBmIyghnaLamfq2As9lOxNcrPHSsyiBolKTpNTfpjn9U8EMxVG5sBbhFJV0XUZporaZk4PuQFnfYs4lj0l+embMwWmWuysBUxt3umm0m/cCYhHV/KNDfA40ssUNkhOJt3P8FJKqykakawihlR5zYsixTBFVMwmTBFhTngWLCLjapNi2JwpiKaLDVX2O4x7WdBYuoop/Iaw2hmrKLaMpFNgcSRTlACqRSmSep24Q0fUni01YymlDJgjzlHAVv0YU7ao+CGGIVddwwOrTBM2odyCKVjw10VSEDC1fs+QCFPYlVMW1Q8Ci/ZaC9scil9e6kn4zIyHRkbBGcx13qxDyFabQJXkUoZAFcUr2FpDGo6Iaj/fAiZGkUG5ut21yyV0Y6S8aOGcDn5hC6bg1LJI/VHtt0XBH/r1wv4puVdJbsrRFGU2jo+S18aSisi+wwOoR7IlPksEJOoQSmKIAb8+tstMv2RIeiyWjus2I1+addsBBkW+4xAK/v318XARnjcYx3sOsZbazndihHrj2v8CSduCKWhcEbW7H2Zpjw2/EchKpl5X9iSq0Fyw3hUiKstQtNJiQBs7m95oXNOwTskw2OcFAwPRa241C0kqO7aYwWS55DaCEWr1a5bue9GjmJqkEVrdcAKQ7kVlGH+v9Cy4K+Y8Ld5zWMOTHGXRp19vpoudhuYa2luYIuVy0pGg/pIGxgvx0qXVwE1hhL3z2DiyviyTDqduuIqZ9+XLzJsZ16DviLMj8zromBKsadB7iB/CCK1kWEat2+7MGpZGRM9Kg6liV75tYWLJUOIvPpsJd0wzNIflakYv5mh5t1SSDP+JsG8rmSmv1qgVJ/66pB0qAngyUWMObqHDp4RXE3UIiRIu2XsY5HtF4RyeKRG4cPaMUiXYEiGwPtGFbjBoNpEC7CLUSQ8N8bdZplodIQWSrURvwauJ3+6u51kV2wkLgqUg5I63vYFM1VoHk5J3MF9ej1S9SEd8HyGtQRJt2SIGbfUQsY5r4oR8b/PU1SUBdIX3ddHwCD3qAVVMscfv5mMXV6QgkrtkKj2D3QWXyo9sW0fPd5i1UX+ugY/iMOeF12Xn48RQ+GfS3Z+BD7yOqeZBsd0eDZZMgb8j8ClwXxaSocqqasJbw2Q1q2an2BonMGxyj4hUYsbDa+um60TaZn1VaJbGvOB4pzBApwLHKo2HzDi7E0kLL60bioXH0z8dJ3TPOqS8xtCUxqJLMas1bEVDMQtT782aadi2o7nzbnjevGFoGopZtOZiXLIcI2Dbtg3/gRpLqwc3685XMTWZLTxmMhJVbZd5hy0l4Q/UL5+entbGlOST++ensyN6Oh/UwNBtQjkr3vl4MZRcy8xoPizNKdUGYkHsAITNYfQQH2EsziTN1LSM5UqzgP1W1z+V5uKHZC2m1Caxi7b6HRAMm2bUPSJ3utaNrw2bE2xyjAcmRp2hrpAHypiKPlxEvFuvS+5VuDwGpordVrwqWCBUuIUEucVq9Wh9pOKkUqjS/TXtI27HbnTRN475tZpeOj3qkzKHnW8966X7/ZHXFK7b5pDcEk6nNGhUXDo3SeuvbggnUBFDwdyxlv+7E/+HvS12GmrBP/Rf9E+QZuM73uW7X6EWSogpnQSvMic1MvxGtLBfwgbF1x+KNhEIa4XcM63g8JIuvrICIeqZ3OU9khlT4HeHy17Ut2YqJ3FqVD4mViqanfh8aAnb8gOAXwrB/BFbt8aBqRx4D8BNSkd8skK9P8ptx1ShygfSs7BhOr+byudCkeZLVqARVGhgYegqtYwlOBtuY/0GQJlB7pJrTpOz52eP5WquWn1MslPVXC5XqBw98+/awf2CP/3+iqi3VhrGkKl4oCoKkL2EdeNvASp6aWNNBKns9Nvz7dPXC1EMd8noya2Pp4vzE/6VpakpvIr+07/Zcm6sdXqg6mlIE2CrEyOeuG1hd4CCBHGylvSNZhPXU1kC0Ws95BT0/r6jQtgR5mtlBRlYBEytfJp45e7uAbJv3wjT9L+FLFaqKzv3t3cNSCdxkfkAg7C1yglcCyEgxsR76bsEBA4LW2w+sRlUbCfdoNrno9ERN84jsNASVr5QkBnfNSQAbG3wzrt+YsalLjBVvwNqpP6GF4VN1mzY6UEYCjU4VmiIfYYJNBC9t5eL4f66cCuJt0LuvRIjFduJ6HOiBZu6uaI5NcbIMQoSpnI1wYKqhbueJFa5/DYgmGIfveyOKt+bqEK87K/YlX6A2+qX4un+cQP7C0/pAcxMKbXYQ3ltA3t712x6tyO0bAhD1o93RZWcgubFFaG0z4caHWl3MSiO0iT0WBwsSnlMW+jhUzSxr9HIS91xn57Xnul5Fxb/gg6e3QN2dpDsyo6okmnLr9Z5/1vfCVo1uqum4SqZvGmS7YBpnsR2DWZfQNylj7y/TLP80/zzwryGNvwjrx+ZnGL7004UYIoSJdT8nxTtU2HCV3fzHdZ9b83ywoSvofyxKDTdhbW+g5fFqr1XyJn8Xa8y9zrLDWpRmAzHNRvcDnajWt6NZTwdxayJq+DeBViaQ16h95vvNM/e2RDbdd57n/pdo1VcNAwFMpmWmXGl4WIs1GfeeNZwLEuDBKHmSPPuH36PI62iqtzyLxHdiqhrWEdJ4rzzZ0fL80b9vu8trEg5BmhCfhDO+1O3xoBSVbg55oOtmyJ1+BUz9cpfpfr+NkwsnWrAxNeDrYB6fIO7WVjvGP3fY7kde+X1TWKlnlCBWu6fs8c7oTnEYjr76GtvW389pX6jezgab2wU3WMbdOhmC9X6w8kWXMmq+nKJroSkNPZvoPwTmBi4BLTLhYvehh67rMrXl3RvLCNexr3He6G5oMXBPlfPx/z2v7zay04fboI9zJTGmzra9ngTJrqC424X6pffeqrKFVyGwiSn1JPz20qZ8sQXGu/xvhg7QfOFXX68vDg+JOUUMb5ksmH39Mv3eoVuauorvvWtenu8A1rtkCtfso6qVxfXx9NDUv+CyMond+cPTzdH5YAmn6fZX5GM+ufQaltaGDa2c4VKpXxz+f3p6/3FxcX909lrzv9DyBLZRmjP04ehVZybkfcG2nYuVy0QVHM286JA8g7A9t4z/1CkOyVT8IZBFrqjubP9SvcTIN1umJpwt12ytZ1iSt1409EeHwZv0q45lqY4sIU12cXaIBvvWtqwU9wrvU8HbzRuL2rzIcG8NusMinsPYo899vgL8f8gA3MeYaE4cAAAAABJRU5ErkJggg==" width="250"></p>
<p align="center"><img src="https://repository-images.githubusercontent.com/44662669/f3f5c080-808b-11ea-9713-2bea65875d95" width="250"></p>

PHP 7.4 - Laravel 8