<?php

declare(strict_types=1);

/*
| Default theme strings (spec 19 §3.9) — no hard-coded string in any template.
| Keys must stay in sync with resources/lang/fr/theme.php; a test in Pass B3
| makes the CI fail if one locale drifts from the other.
*/

return [

    // --- Navigation and structure -------------------------------------------
    'skip_to_content' => 'Skip to content',
    'menu' => 'Menu',
    'main_navigation' => 'Main navigation',
    'footer_navigation' => 'Footer navigation',
    'home' => 'Home',
    'back_to_home' => 'Back to home',
    'built_with' => 'Powered by :product',

    // --- Search --------------------------------------------------------------
    'search' => 'Search',
    'search_title' => 'Search',
    'search_label' => 'Your search',
    'search_placeholder' => 'A word, a title, a name…',
    'search_submit' => 'Search',
    'search_invite' => 'Type a word to search this site.',
    'search_results_for' => 'Results for “:query”',
    'search_empty_title' => 'No result for “:query”',
    'search_empty_hint' => 'Try a shorter term, or a different spelling.',

    // --- Empty states ---------------------------------------------------------
    'nothing_here_title' => 'Nothing to show yet',
    'nothing_here_body' => 'This site has no published content yet.',

];
