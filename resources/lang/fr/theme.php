<?php

declare(strict_types=1);

/*
| Chaînes du thème par défaut (spec 19 §3.9) — aucune chaîne en dur dans les
| templates. Deux raisons, dans cet ordre : un thème de référence qui code ses
| libellés en dur enseigne ce qu'il ne faut pas faire, et le produit vise des
| agences, donc des sites multilingues dès le premier client.
*/

return [

    // --- Navigation et structure -------------------------------------------
    'skip_to_content' => 'Aller au contenu',
    'menu' => 'Menu',
    'main_navigation' => 'Navigation principale',
    'footer_navigation' => 'Navigation de pied de page',
    'home' => 'Accueil',
    'back_to_home' => "Retour à l'accueil",
    'built_with' => 'Propulsé par :product',

    // --- Recherche ----------------------------------------------------------
    'search' => 'Rechercher',
    'search_title' => 'Recherche',
    'search_label' => 'Votre recherche',
    'search_placeholder' => 'Un mot, un titre, un nom…',
    'search_submit' => 'Rechercher',
    'search_invite' => 'Saisissez un mot pour chercher dans le site.',
    'search_results_for' => 'Résultats pour « :query »',
    'search_empty_title' => 'Aucun résultat pour « :query »',
    'search_empty_hint' => 'Essayez un terme plus court, ou une autre orthographe.',

    // --- Accueil ------------------------------------------------------------
    'home_hero_body' => "Ce site est prêt. Publiez un premier contenu, puis choisissez ce que l'accueil doit montrer dans Réglages > Lecture.",
    'home_hero_action' => 'Parcourir le site',

    // --- Fiches et listes ---------------------------------------------------
    'pagination_label' => 'Pagination',
    'pagination_previous' => 'Précédent',
    'pagination_next' => 'Suivant',
    'pagination_go_to' => 'Page :page',

    // --- Page introuvable ---------------------------------------------------
    'not_found_title' => 'Cette page n’existe pas',
    'not_found_body' => "L'adresse demandée ne correspond à aucun contenu. Elle a peut-être changé, ou le contenu a été retiré.",

    // --- États vides --------------------------------------------------------
    'nothing_here_title' => 'Rien à afficher pour le moment',
    'nothing_here_body' => "Ce site n'a pas encore de contenu publié.",
    'archive_empty' => 'Aucun contenu publié dans cette rubrique pour le moment.',

];
