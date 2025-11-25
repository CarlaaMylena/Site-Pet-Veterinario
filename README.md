# PetShop Planvet

Sistema web para gestão de convênio veterinário.

## Funcionalidades

- Login e cadastro
- Agendamento de consultas
- Formulário de contato
- Seção de pets para adoção
- Área de serviços
- Design responsivo

## Tecnologias

**Front-end**
- HTML5
- CSS3
- JavaScript
- Font Awesome 6.4.0

**Back-end**
- PHP 7.4+
- MySQL 5.7+
- PDO

## Estrutura

```
site_pet_veterinaria/
│
├── index.html
├── README.md
│
├── css/
│   └── style.css
│
├── js/
│   └── script.js
│
├── images/
│   ├── dog-hero.png
│   ├── dog-yellow.jpg
│   ├── service1.jpg
│   ├── service2.jpg
│   ├── service3.jpg
│   └── service4.jpg
│
└── backend/
    ├── config.php
    ├── database.sql
    ├── login.php
    ├── cadastro.php
    ├── agendar.php
    └── contato.php
```

## Instalação

**Requisitos**
- XAMPP ou WAMP
- PHP 7.4+
- MySQL 5.7+

**Setup do Banco**

1. Abra phpMyAdmin (http://localhost/phpmyadmin)
2. Crie o banco `petshop_planvet`
3. Importe `backend/database.sql`

Usuário admin: `admin@planvetsaude.com.br` / `admin123`

**Setup do Projeto**

1. Copie para:
   - XAMPP: `C:\xampp\htdocs\site_pet_veterinaria`
   - WAMP: `C:\wamp64\www\site_pet_veterinaria`

2. Configure `backend/config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'petshop_planvet');
define('DB_USER', 'root');
define('DB_PASS', '');
```

3. Inicie Apache e MySQL

4. Acesse: `http://localhost/site_pet_veterinaria`

## Banco de Dados

**Tabelas**
- usuarios
- pets
- pets_adocao
- agendamentos
- contatos
- servicos

**Consultas úteis**

```sql
SELECT * FROM agendamentos ORDER BY data_agendamento DESC;
SELECT * FROM contatos WHERE lido = FALSE ORDER BY data_envio DESC;
SELECT * FROM pets_adocao WHERE adotado = FALSE;
SELECT COUNT(*) as total FROM usuarios WHERE tipo = 'cliente';
```

## Recursos

- [Documentação PHP](https://www.php.net/manual/pt_BR/)
- [Documentação MySQL](https://dev.mysql.com/doc/)
- [MDN Web Docs](https://developer.mozilla.org/)
- [Font Awesome](https://fontawesome.com/icons)

## Autor

**Carla Mylena Calixto Rodrigues** - RGM: 41887867
**Diego Dantas Cavalcanti** - RGM: 42215285

---

Projeto desenvolvido para a disciplina de Programação Web - UNIPÊ  
Professor: Jeofton Costa Melo  
Data: 24/11/2