pour commencer installer :
symphony
makerbulble
composer require symfony/translation

pour generer les fichier :
php bin/console make:command CheckComposerCommand
php bin/console make:command generetevalidatorCommand

pour generer les log :
changer le path dans le ficher ComposerLog lignes :59
ca génére un fichier logfil.log 
avec la commande : php bin/console app:validate-composer "C:\Users\ervin\composermakerprojet\composer.json" --lang=th ou en ou ch 