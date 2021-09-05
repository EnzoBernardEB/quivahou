# quivahou ECF Symfony

-Composer install
-yarn install
-yarn dev

Récupérer le dossier BDD dans le moodle cefim
Ajouter dans le dossier uploads public du projet, il contient quelque photo et document pour une inscription sur la plateforme.

Copie puis colle le .env en renommant la copie .env.local, ajouter la connexion a la base de donnée. 
Crée la base de donnée -> symfony console doctrine:database:create
Puis importer le fichier .sql du dossier BDD dans la base de donnée

Lancer le serveur symfony -> symfony serve -d

Voila, plus qu'a tester... Il est loin d'être parfait, plein de chose a optimiser, à refaire autrement mais je me suis focaliser sur les fonctionnalités,
d'abord trouver un moyen de le faire et l'optimisation plus tard si y a le temps.

Admin : admin@qivhaou.net
mdp : testtttt

Commercial : commercial{x}@qivahou.net  x-> [1 à 5]
mdp : testtttt

collaborateur: jean.bon@gmail.com
mdp : testtttt

candidat : julie.roman@gmail.com
mdp : testtttt

jean bon visibilité avec gislaine purkoton (purkoton@hotmail.com)
jean bon demande collegue avec julie roman en attente (julie.roman@gmail.com)
