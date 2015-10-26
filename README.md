Motorbikes
==========

A test Symfony2 project

### Version
Ver 3.0:
 - Clean the codes
 - Add more unit test files

Ver 2.0:
 - Add pagination class include twig template file
 - Add macro for sorting
 - Update unit test files
 - Add more configs

### Installation

Pretty simple with Composer, run:


```sh
$ composer require cpkgroup/Motorbikes
```
### Configuration example

Create a database (mysql or sqlite etc.) and config the database on "app/config/parameters.yml"
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

