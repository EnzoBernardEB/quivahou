# quivahou ECF Symfony

##Principe de la plateforme :

C'est une plateforme sur laquelle on peut s'inscire et suivre un parcours d'inscription ou l'on renseigne ses compétences, ses experiences, son adresse et pour finir on partage un cv+LM ainsi qu'une photo de profil.

Une fois fini ( je viens de penser à l'envoi d'un mail a l'admin pour signaler une nouvelle inscription, facile à mettre en place avec le MailerInterface) l'admin voit les comptes à valider sur son dashboard. Une fois validé la personne passe de Candidat a collaborateurs.
L'admin peut ensuite assigné un commercial référent aux collaborateurs.
Les commerciaux eux peuvent effectuer des recherches en fonction de criteres, et assigné un collaborateur a une mission au sein d'une entreprise.
Lorsqu'une mission est fini, le commercial ferme la mission ce qui rajoute cette mission dans les experiences du collaborateur.
Le collaborateur peut en ajouter/modifier compétences/experiences/informations personnelles. Ils peut également télécharger et imprimer une fiche qui regroupe ses informations. Le commercial peut faire de même en choisissant si anonyme ou non.


------------------------------
##Installation

-Composer install
-yarn install
-yarn dev
Il y à plusieurs fonctionnalité qui sont accompagné de l'envoi d'un mail. En developpement il faut lancer la commande -> maildev --hide-extensions STARTTLS pour lancer le mailcatcher sous peine d'avoir une erreur. 

Récupérer le dossier BDD dans le moodle cefim
Ajouter dans le dossier uploads public du projet, il contient quelque photo et document pour une inscription sur la plateforme.

Copie puis colle le .env en renommant la copie .env.local, ajouter la connexion a la base de donnée. 
Crée la base de donnée -> symfony console doctrine:database:create
Puis importer le fichier .sql du dossier BDD dans la base de donnée

Lancer le serveur symfony -> symfony serve -d

Voila, plus qu'a tester... Il est loin d'être parfait, plein de chose a optimiser, à refaire autrement mais je me suis focaliser sur les fonctionnalités. Ce projet à été réalisé dans un contexte particulier avec plein de coupure, j'ai developpé un peu par ci, un peu par la et je pense que ça se ressent sur la qualité du code. Le même projet dans un autre contexte inintérrompu serait totalement différent de celui je pense.
Mais globalement le projet à été apprécié, un peu de frustration de le rendre comme ça !

Admin : admin@qivhaou.net
mdp : testtttt

Commercial : commercial{x}@qivahou.net  x-> [1 à 5]
mdp : testtttt

collaborateur: jean.bon@gmail.com
mdp : testtttt

candidat : julie.roman@gmail.com
mdp : testtttt

jean bon visibilité avec gislaine purkoton (purkoton@hotmail.com)
jean bon demande collegue avec julie roman en attente (julie.roman@gmail.com).
