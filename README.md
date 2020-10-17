# Introduction
Utilisation des tests unitaires et fonctionnels
# Pré requis

Pour lancer le projet vous aurez besoin de la configuration suivante :
* [Apache](http://httpd.apache.org/docs/2.4/fr/install.html) >= 2
* [MySql](https://dev.mysql.com/doc/mysql-installation-excerpt/5.7/en/) >= 5.7 ou [MariaDB](https://mariadb.com/kb/en/where-to-download-mariadb/#the-latest-packages) >=10.2 ou SQlite
* [Php](https://www.php.net/manual/fr/install.php) >= 7.2

 [Aide Linux](https://www.digitalocean.com/community/tutorials/comment-installer-la-pile-linux-apache-mysql-php-lamp-sur-un-serveur-ubuntu-18-04-fr)
  ou [Aide Mac](https://documentation.mamp.info/en/MAMP-Mac/Installation/) 
  
# Stack technique
* [Symfony 4.4](https://symfony.com/doc/4.4/setup.html)
* [Twig](https://twig.symfony.com/)
* [Bootstrap 4](https://getbootstrap.com/)
* [PHPUnit](https://symfony.com/doc/current/components/phpunit_bridge.html)
* [LiipTestFixturesBundle](https://github.com/liip/LiipTestFixturesBundle)

# Pour initialiser le projet

#### Créer son fichier .env.local qui contiendra les informations de sa base de données. Sinon les commandes suivantes ne pourront pas fonctionner !

```
composer install
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```
> Pour les tests avec phpunit il faut configuer les informations de la base de données dans le fichier .env.test
