# Tailwind 4 + DaisyUI 5 Snapshot

Mis a jour le 19 juillet 2026.

Ce projet utilise Tailwind CSS 4 avec `symfonycasts/tailwind-bundle`, Symfony 7 et Asset Mapper. La configuration Tailwind se fait dans [assets/styles/app.css](/home/jiffy/dev/gai_carabiniers/assets/styles/app.css:1), pas dans un `tailwind.config.js` de style Tailwind 3.

## Regles du projet

- L'entree CSS Tailwind est `assets/styles/app.css`.
- `@import "tailwindcss";` initialise Tailwind 4.
- Les chemins de scan sont declares avec `@source`.
- DaisyUI 5 est charge via `@plugin "daisyui"` dans `assets/styles/app.css`.
- Les themes DaisyUI sont definis via `@plugin "daisyui/theme"`.
- Les assets sont exposes par Asset Mapper et injectes via `{{ importmap('app') }}` dans [templates/base.html.twig](/home/jiffy/dev/gai_carabiniers/templates/base.html.twig:48).
- `importmap.php` ne doit pas contenir de modules Tailwind 3 comme `tailwindcss/plugin`, `tailwindcss/colors` ou `tailwindcss/defaultTheme`.

## Conventions a respecter

- Ne pas reintroduire `tailwind.config.js` pour la configuration applicative courante.
- Ne pas utiliser le schema Tailwind 3 `content`, `theme.extend` et `plugins: [require(...)]` pour l'application.
- Garder DaisyUI en version 5 et Tailwind en version 4.
- Si un nouveau template Twig ou fichier JS contient des classes Tailwind, ajouter un `@source` uniquement si son dossier n'est pas deja scanne.
- Les fichiers de handoff `design_handoff_*` peuvent contenir d'anciennes references Tailwind 3 a titre documentaire, mais ils ne doivent pas redevenir la source de configuration de l'application.

## Verification rapide

- `php bin/console tailwind:build`
- verifier que `assets/app.js` importe `./styles/app.css`
- verifier que le layout principal utilise `{{ importmap('app') }}`
