{{--
    Pied de page (spec 19 §4.1, §4.2, §4.4).

    Trois choses : le menu `footer`, la zone de widgets `footer`, et la seule
    mention Baobab du thème — discrète, et le seul endroit où le logo du produit
    apparaît.

    Le lien vers la recherche vit ici, pas en en-tête (§4.4) : un champ permanent
    imposerait une largeur et un comportement mobile que l'échafaudage n'a pas à
    trancher. Le champ vit sur sa page.

    Menu non assigné ou zone vide : les deux composants ne rendent rien, et le
    pied se réduit à la mention de marque. Un vide de chrome disparaît
    silencieusement pour le visiteur (§5.9).
--}}
<footer class="mt-xl border-t border-border bg-surface">
    <div class="mx-auto flex max-w-5xl flex-col gap-md px-md py-lg">
        <x-baobab::widget-zone name="footer" class="grid gap-md sm:grid-cols-2" />

        <div class="flex flex-col gap-sm border-t border-border pt-md text-sm text-muted sm:flex-row sm:items-center sm:justify-between">
            <x-baobab::menu
                location="footer"
                class="flex flex-wrap gap-md"
                aria-label="{{ __('theme::theme.footer_navigation') }}"
            />

            <div class="flex items-center gap-md">
                <a href="{{ route('baobab.search') }}" class="text-muted underline underline-offset-4">
                    {{ __('theme::theme.search') }}
                </a>

                <span>{{ __('theme::theme.built_with', ['product' => 'Baobab']) }}</span>
            </div>
        </div>
    </div>
</footer>
