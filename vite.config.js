import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';

// Build isolé du thème (spec 03 §8, spec 19 §8.1) : pas de laravel-vite-plugin,
// dont le mécanisme @vite()/hot-file suppose une seule application hôte. Le
// manifest est lu directement par Baobab\View\Components\Vite, d'où un build
// Vite « nu » avec manifest activé.
//
// `base` doit correspondre au chemin publié par PublishThemeAssets, qui crée
// public/themes/{slug} → {thème}/public, avec slug = baobab-default-theme.
//
// Le résultat (public/build/) est VERSIONNÉ : une installation par Composer ou
// par ZIP ne doit jamais exiger Node (§8.1). Cette configuration sert le
// mainteneur du thème, pas qui l'installe.
//
//   npm run build   (depuis packages/baobab/default-theme)
export default defineConfig({
    root: import.meta.dirname,
    base: '/themes/baobab-default-theme/build/',
    plugins: [tailwindcss()],
    build: {
        manifest: true,
        outDir: 'public/build',
        emptyOutDir: true,
        rollupOptions: {
            input: ['resources/assets/css/app.css'],
        },
    },
});
