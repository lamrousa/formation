## Exercice for Aghiles

# 0 - Build the full FrameWork from OCRoom : Done !

# 1 - Add Feature : Allow logged user to logout :
* Add a link in menu to logout the connected user. 
* Make a new action in ConnexionController.
* After logout, redirect to homepage. 

# 2 - Refactoring user managment :
* Store user in Database : Create table(s) according to DreamCentury database nomenclature.
* Add a new Manager to work the new table(s) (according to the Framework manager name nomenclature).
* Update your ConnexionController code.
* Modification supplémentaires : 
  * Les utilisateurs sont maintenant des auteurs, c'est a dire que tous les utilisateurs sont maintenant des auteurs.
    * Un visiteur peut donc : 
    * S'inscrire en tant qu'Auteurs ( pseudo, email et password ).
    * Poster un commentaire en saisissant sont pseudo (et son email : nullable)
  * Un utilisateurs connecté peut donc : 
    * Créer une news.
    * Editer/supprimer ses news.
    * Poster un commentaire qui sera automatiquement associé a son compte utilisateur ( il ne rentrera donc ni pseudo ni email).
  * Un administrateurs connectés peut donc : 
    * Faire tout ce que peut faire un utilisateurs connecté ( il y a donc heritage de droit) 
    * Editer/supprimer les news des autres auteurs.
    * Supprimer n'importe quel commentaire.
  * Liens vers les pages profils : 
    * Le nom de l'auteur d'une news ou d'un commentaire qui fait reference à un compte utilisateurs/admin pointera vers une page de profil d'un utilisateur qui listera les news posté par ce membre et les commentaires écrits. 
  * Mails. 
    * Lorsqu'un nouveau commentaires est posté, envoyé un mail a tous les gens qui ont commenté cette meme news précédement.


# 3 - CSS : Fix the CSS error when text is too long.
* Try to enter a very long text in title and text of a News and observe the result. (Long text without space)
* Fix visual problem by adding somes CSS rules.

# 4 - Fix SQL Injection : 
* If you have good memory, you know that SQL Injection mean ! 
* Remove all SQL Injection vulnerabilities. 

# 5 - Fix JavaScript Injection :
* Google is your best friend.
* Remove all JavaScript Injection vulnerabilities. 

# 6 - Improve your code : Url and Link
Actually, you need to enter manually the value of a href attribute according to the route.xml file. 
What happens if tomorrow i decided to edit a route ?
All your code is break down.
This part consist to add a functionnality for ask a route for a Controller and an Action using a function. Replace the manually entered href by a call of this function. 

# 7 - Centralisation - gestion des composants génériques du site
* Mettre en place une centralisation pour la gestion des composants génériques du site.
* Actuellement, le menu est géré dans le layout. On pourrait le faire évoluer, avec des
* parties à afficher en fonction du membre connecté. Il faudrait contrôler le menu par le
* code.
* Idem pour la gestion de cookies pour la reconnexion, pour la redirection automatique en cas de non connexion lors de certaines actions, etc.
* En fait, il faudrait gérer la centralisation des actions des contrôleurs dans un nouvel élément du framework
* Il faut créer un nouveau composant (ne pas le mettre dans Application ou autre)

# 8 - Add Feature : Ajax ! Flower Party :)
* Actuellement, toutes les pages retournées par les applications (Front et Back) retournent des pages générées en HTML. 
* Faire en sorte que lorsque l'on veut un retour JSON parfait, on puisse l'avoir à la demande. 
* Dans un premier temps, déterminer quel process va permettre de déterminer le type/format de retour que l'on attend. Valider cette approche avec Baptiste ou moi-même.
* Dans un second temps mettre en place un test bateau selon le principe arrêté.
