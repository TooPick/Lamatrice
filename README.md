Projet ODI M1 Informatique 2016 - Lamatrice
========================

Voici la démarche d'installation du projet ainsi que les différentes commandes utiles à sa mise en place.

Installation
--------------

 * Afin de télécharger les dépendances du projet, il est nécessaire d'exécutez la commande :

`php composer.phar install`

Les différents paramètres ont été pré-remplis, modifiez-les pour vous adapter à votre système.

`php composer.phar install`

 * Pour créer la base de données exécutez :
 
`php bin/console doctrine:database:create`
 
  * Pour créer les différentes tables de la base, exécutez :
  
`php bin/console doctrine:schema:update --force`

 * (Optionnel) Afin de générer un jeu de test dans la base de données, exécutez :
 
`php bin/console doctrine:fixtures:load`

Ou bien 

`php bin/console doctrine:fixtures:load --append`

Si vous souchaitez concerver ce qui est déjà présent dans la base.
