# Configurações

## Banco de Dados
File: `db.json` 

```
{
	"_DB_HOST": "host_url",
	"_DB_NAME": "database name",
	"_DB_USER": "database usernama",
	"_DB_PSWD": "database password"
}
```
## Site
File: `site.json`

```
{
    "site_title": "Título do Site",
    "site_subtitle": "Subítulo do Site",
    "site_author": "Autor do Site",
    "description": "Descrição do Site",
    "key_words": "Palavras chave do Site",
    "site_prefix": "Prefixo"
}
```

## Menu Público
File: `menu/public.json`

```
{
    "login": {
        "name": "login",
        "ctlr": "auth",
        "act": "main",
        "title": "acesso a \u00e1rea restrita",
        "permitions": null
    }
}
```

## Menu Restrito
File: `menu/restrict.json`

```
{
    "controle": {
        "name": "controle",
        "ctlr": "valor",
        "act": "main",
        "title": "controle dos gastos",
        "permitions": [
            1
        ]
    },
    "categorias": {
        "name": "categorias",
        "ctlr": "cat",
        "act": "main",
        "title": "categorias",
        "permitions": [
            1
        ]
    },
    "exerc\u00edcio": {
        "name": "exerc\u00edcio",
        "ctlr": "exe",
        "act": "main",
        "title": "Gerencia o in\u00edcio do exerc\u00edcio de cada m\u00eas",
        "permitions": [
            1
        ]
    },
    "logout": {
        "name": "logout",
        "ctlr": "auth",
        "act": "logout",
        "title": "sair da \u00e1rea restrita",
        "permitions": [
            1
        ]
    }
}
```