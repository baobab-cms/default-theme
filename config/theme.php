<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Réglages d'affichage du thème par défaut (spec 19 §3.8)
|--------------------------------------------------------------------------
|
| Lus par le thème lui-même, jamais par le Core. Les templates les consomment
| via config('theme.*'). Aucun mécanisme nouveau : c'est le patron Laravel
| natif d'un package qui publie sa configuration, et c'est à ce titre qu'il
| figure ici — un thème de référence enseigne autant par sa plomberie que par
| son rendu.
|
| MISE EN GARDE — mergeConfigFrom ne fusionne que les clés de PREMIER NIVEAU.
| Un thème enfant qui publierait sa propre config/theme.php écraserait donc
| entièrement ce bloc, sous-clés comprises : une clé ajoutée ici après coup
| n'existerait pas à l'exécution chez lui. C'est exactement le défaut relevé
| le 10 août 2026 sur la configuration du Core (suivi n° 119), où une racine
| absente avait dégradé un garde-fou de suppression de fichiers. Un enfant qui
| veut changer un seul réglage redéclare le bloc entier, ou lit la valeur du
| parent avant de la surcharger.
|
*/

return [

    /*
    | Mesure de lecture du corps de texte. Volontairement exprimée en ch —
    | l'unité qui suit la police, y compris quand un profil de marque en
    | change (spec 18 §7.2), là où une largeur en pixels ne suivrait pas.
    */
    'reading_measure' => '68ch',

    /*
    | Nombre d'entrées par page d'archive. Distinct de
    | baobab.rendering.per_page, qui gouverne la pagination côté Core : ce
    | réglage-ci n'existe que pour montrer comment un thème porte ses propres
    | options sans toucher à celles du Core.
    */
    'archive_per_page' => 10,

    /*
    | Afficher la date sur les cartes d'archive. Faux pour un site sans
    | actualité, où une date visible vieillit la page sans rien apprendre.
    */
    'show_dates_on_cards' => true,

];
