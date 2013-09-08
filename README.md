Installation de WindServer
=======================

### Installation des éléments 

* Recuperer le projet depuis git (le dernier paramètre est le nom du repertoir cible):

	```
	git clone git@github.com:lapoiz/WindServer.git WindServer
	```

* Aller dans le repertoir créé
	```
	cd WindServer/
	```

* Installer Composer, si ce n'est pas déjà fait
	```
	curl -s http://getcomposer.org/installer | php
	```

* Installer les vendors (ça peut mettre du temps)
	```
	php composer.phar update
	```
	A noter que nous sommes en `including require-dev` pour pouvoir utiliser les fixtures (chargement de données initiales)
	Si l'erreur suivant apparait: `The process timed-out`, utilisez: `COMPOSER_PROCESS_TIMEOUT=4000 php composer.phar update`


* Tester si tout est OK, et suivre les recommandations 
	```
	php app/check.php
	```
* Verifier la version de Symfony
	```php app/console -version```

* Acceder au script de configuration

	Modifier les droits (souvent nécessaire):
	```
	chmod -R 777 app/cache/
	chmod 777 app/logs/
	```

	Puis configurer avec vos données, en allant avec votre navigateur sur:
	`http://www.votresite.com/WindServer/web/config.php`
	`http://www.votresite.com` peut être: `http://localhost` si vous travaillez en local.
	Vous pouvez également le modifier: `app/config/parameters.yml`


### Installation des éléments 

* Creation de la BD et création des tables
	```
	php app/console doctrine:database:create
	php app/console doctrine:schema:update --force
	php app/console doctrine:generate:entities LaPoizWindBundle
	```

* Intialiser le site avec un site: Saint Aubin (que vous pourez effacer ultérieurement)
	```	
	php app/console doctrine:fixtures:load --fixtures=src/LaPoiz/WindBundle/DataFixture/
	php app/console lapoiz:getData
	```

* 2 dernières commandes 
	```
	php app/console assets:install web/ --symlink
	chmod 777 -R app/cache
	```

* Ca devrait marcher:

	Aller avec votre navigateur sur: `http://www.votresite.com/WindServer/web/app_dev.php/index`


### Problème actuel

* Extension intl

	Il existe un problème pour les serveurs mutualisés: l'impossibiliter d'utiliser l'extension intl.
	Or Symfony2 en a besoin pour la validation des formulaires.
	Dans notre cas,	lors de l'ajout d'un site, le problème se situe pour les données GPS :
	AdminSpotController.php à la ligne 154: return $this->updateSpot($spot); 

	L'erreur:
	Symfony\Component\Form\Extension\Core\DataTransformer\NumberToLocalizedStringTransformer->reverseTransform('45')

	Pour le simuler en local: 
	```
	sudo apt-get remove php5-intl
	```


