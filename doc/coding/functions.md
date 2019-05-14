# Fonctions globales

## path
La fonction **path** contenue dans l'extension Twig *AppExtension*,
permet d'indiquer un chemin d'accès.
Elle prends deux paramètres : la route et un tableau d'options (facultatif).
En retour, la fonction renvoi le chemin d'accès relatif.

## pathab
La fonction **pathab** contenue dans l'extension Twig *AppExtension*,
permet d'indiquer un chemin d'accès mais cette fois absolu.
Le fonctionnement est identique à *path*, mais va donner
un lien complet.

## controller
La fonction **controller** contenue dans l'extension Twig *AppExtension*,
permet d'appeler l'action d'un controller en particulier.
Elle prends deux paramètres : la route d'accès au controller,
et un tableau contenant les paramètres à passer à l'action.

En retour, la fonction va fournir le contenu donné par ce controller.
Ce n'est donc pas un Response qui est attendu mais une chaîne de caractères.

La route est définie de la même façon que dans le fichier des routes :

    namespace\bundle:controllerController:actionAction
  
Ainsi s'il fallait par exemple appeler l'action *index* du controller d'administration,
nous aurions alors :

    Bundle\Admin:IndexController:indexAction

## asset
La fonction **asset** contenue dans l'extension Twig *AssetExtension*,
permet d'utiliser des assets (images, sons, vidéos etc.) qui sont
dans le dossier **web** du site.

Attention, le dossier **web** des différents Bundle ne sont pas là
pour ce genre de données, donc rien ne sera lu depuis ces derniers.
En revanche pour des assets spécifiques aux différents Bundle,
il est conseillé d'avoir un sous-répertoire avec le nom de ce dernier,
dans le dossier **web** du site.
