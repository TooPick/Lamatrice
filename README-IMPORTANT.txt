Pour initialiser le projet et récupérer les dépendances il faut executer la commande

:> php composer.phar install

dans la racine du projet.

ET à la fin il faut renseigner les configurations, elles sont déjà préremplies.
Et on s'en fous des config de mailer, laissez par defaut.

Pour créer la base de données, si elle n'est pas déjà crée :

:> php bin/console doctrine:database:create

Pour mettre à jour les entités de la base de donnée :

:> php bin/console doctrine:schema:update --force

Pour mettre à jour les dépendances si elle ont été modifiées

:> php composer.phar update


--------------------
Gestion utilisateur
--------------------
Pour créer un compte utilisateur en console (possible de le faire direct sur le site avec l'inscription) :

:> php bin/console fos:user:create

et suivre les instructions.

Pour mettre un compte créé en admin :

:> php bin/console fos:user:promote

Mettre le username de votre compte puis le role ROLE_ADMIN
