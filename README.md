Motorbikes
==========

A test Symfony2 project

### Version
ver 3.0:
 - clean the codes
 - add more unit test files

ver 2.0:
 - add pagination class include twig template file
 - add macro for sorting
 - update unit test files
 - add more configs

### Installation

You need Gulp installed globally:
Pretty simple with Composer, run:


```sh
$ composer require cpkgroup/Motorbikes
```
### Configuration example

create a database (mysql or sqlite etc.) and config the database on "app/config/parameters.yml"
```yaml
parameters:
    database_host: 127.0.0.1
    database_port: null
    database_name: test
    database_user: root
    database_password: 
```
You can configure default upload path and pagination size on "app/config/config.yml"

```yaml
parameters:
    locale: en
    motorbikes:
        items_per_page: 5
        web_dir: web
        upload_dir: uploads
```

