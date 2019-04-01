# Variables globales

## Description
Ce document décrit les différentes variables globales utilisables dans les templates.

## Title
La variable **title**, sert à indiquer le titre de la page au sein du contenu.
Il ne faut pas confondre le titre de la page HTML et le titre de la page de contenu.

Le titre de la page HTML, c'est ce qui apparaît dans la barre de titre de votre navigateur
ou de l'onglet de votre navigateur.

Le titre de la page de contenu, c'est le tire qui apparaît sous le breadcrum
et qui permet donc de savoir immédiatement sur qu'elle page on se trouve.

Cette variable sert donc à renseigner ce titre ; si aucun titre n'est indiqué
dans au moins un des templates appelés par le controller, alors aucun titre
ne sera affiché et l'emplacement prévu n'est pas présent afin d'éviter de casser
l'aspect visuel.

## Breadcrumb
La variable **breadcrumb** sert à indiquer le "breadcrumb",
c'est à dire ce qui est en haut de la page sous le logo et la navigation.
Cet élément sert habituellement à indiquer sur qu'elle page vous vous trouvez
et vous donnez des liens vers les pages hiérarchiques.

Par défaut, donc en l'absence de cette variable ou si elle est vide,
le breadcrumb indiquera que vous êtes sur l'accueil du site.

La variable est un tableau à deux dimensions :
1. dimension indexée (numérotée)
2. dimension associative

En effet, chaque élément du breadcrumb est représenté par deux éléments :
1. *name* qui indique le nom à afficher
2. *link* qui indique le lien vers la page

Le fait que la première dimension est numérotée,
permet simplement de faire une boucle dessus afin de pouvoir
afficher donc chacun des éléments.
