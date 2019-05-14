# Les routes

## Fonctionnement du site
Le site repose sur un système de routes.
Se sont des adresses d'accès par exemple `/admin`.
Le but étant d'avoir des URL qui soient propre et lisible.
Les routes sont définies dans le fichier de configuration **routes.php**.

## Structure d'une route
Chaque route est représentée par une classe **Route** du kernel.
Il faut un nom qui permet d'appeler la route pour générer son adresse,
une route (chemin d'accès), un controller et des méthodes.

La route c'est le chemin d'accès, donc l'adresse à taper.
Le controller lui, c'est ce qui va être appeler, il est géré comme ceci :

    `Bundle\\nom du bundle:controllerController:actionAction`

Les méthodes, se sont les méthodes d'accès :
* GET
* POST
* PUT
* DELETE

Ainsi une route en GET uniquement, ne pourra pas soumettre de formulaire.
Une route prévue pour respecter le principe du CRUD et servir
donc comme API utilisée par un programme externe,
doit définir la bonne méthode. Par exemple DELETE, si cette route
doit supprimer des données.

L'ordre des routes est important, donc si une route n'est pas appelée,
il faut regarder si elle est dans le bon ordre.

Le système utilise **AltoRouter**, regardez donc sa documentation,
pour savoir dans la route comment gérer les arguments.
Le site utilisant ce principe, il est possible de comprendre son
fonctionnement, en regardant les routes déjà présentes.
