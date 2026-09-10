# Handoff : Page Classement — Les Gais Carabiniers (Design v2)

## Overview
Site de compétition de tir sportif pour le club **Les Gais Carabiniers** (Bernissart, fondé en 1947). Ce handoff couvre la **page de classement** dans son identité visuelle **v2** (vert forêt / crème / rouge cible), ainsi que le **design system** complet (deux variantes, thèmes clair et sombre).

## À propos des fichiers de design
Les fichiers fournis sont des **références de design créées en HTML** — des prototypes montrant l'apparence et le comportement souhaités, **pas du code de production à copier tel quel**. La tâche consiste à **recréer ces designs dans l'environnement cible** (React, Vue, Svelte, etc.) en utilisant ses patterns établis. Si aucun environnement n'existe encore, choisir le framework le plus adapté.

Les fichiers `.dc.html` sont des "Design Components" : ils s'ouvrent dans un navigateur mais utilisent un runtime maison (`support.js`). **Ne pas réutiliser `support.js`** — il sert seulement à prévisualiser. Récupérer le markup, les styles inline et la logique d'état décrits ci-dessous.

## Fidélité
**Haute-fidélité (hifi)** — couleurs, typographie, espacements et interactions sont définitifs. Recréer l'UI au pixel près en utilisant les librairies/patterns de la codebase cible. La palette est aussi fournie en tokens DaisyUI v3 (HSL) et v4 (oklch) — voir section Design Tokens.

---

## Écrans / Vues

### Page Classement (`Classement v2.dc.html`)

**Purpose** : afficher le classement d'une compétition mensuelle, avec 3 catégories de tir basculables par onglets.

**Layout** (largeur de contenu max `980px`, centré, padding horizontal `32px`) :
```
┌──────────────────────────────────────────────┐
│ NAV          hauteur 56px · fond vert #2C6B35  │  logo (34px rond) + nom club | toggle thème
├──────────────────────────────────────────────┤
│ BREADCRUMB   hauteur 36px · fond vert #1F5229  │  Accueil › Compétitions › Classement Général
├──────────────────────────────────────────────┤
│ HERO         padding 40px 0 48px · fond #1F5229 │  kicker + H1 + méta | cible décorative SVG
├──────────────────────────────────────────────┤
│ MAIN         padding-top 0, padding-bottom 80px │
│   TABS       border-bottom 1px #D5CEB0          │  Général / Standard / Appuyé
│   H2 titre   Teko 28px uppercase                │
│   TABLE      card blanche, radius 6px           │  grille 64px | 1fr | 120px
│   note "* Membre du club"                       │
└──────────────────────────────────────────────┘
```

**Composants** :

1. **Nav bar**
   - Fond `#2C6B35` (vert forêt), `border-bottom: 1px solid rgba(0,0,0,0.12)`, hauteur `56px`.
   - Gauche : logo circulaire `34×34px` (`border: 2px solid rgba(255,255,255,0.3)`, `object-fit: cover`) + nom « LES GAIS CARABINIERS » en **Teko 16px / weight 500 / uppercase / letter-spacing 0.06em**, couleur `rgba(255,255,255,0.9)`.
   - Droite : bouton toggle thème — pill `border: 1px solid rgba(255,255,255,0.2)`, `border-radius: 20px`, padding `5px 12px`, icône soleil/lune (`stroke #D4A818`) + label « SOMBRE » / « CLAIR » (Nunito 10.5px / 700 / uppercase / `rgba(255,255,255,0.55)`). Hover : `opacity 0.65`.

2. **Breadcrumb**
   - Fond `#1F5229` (vert foncé), `border-bottom: 1px solid rgba(0,0,0,0.12)`, hauteur `36px`.
   - Items Nunito 12px, couleur `rgba(255,255,255,0.6)`, séparateurs « › » `rgba(255,255,255,0.25)`. Dernier item « CLASSEMENT GÉNÉRAL » : 11px / 700 / uppercase / letter-spacing 0.06em / `rgba(255,255,255,0.9)`. Hover liens : couleur `#D4A818`.

3. **Hero**
   - Fond `#1F5229`, padding `40px 0 48px`.
   - Kicker : trait vertical `3×22px` radius 2px couleur `#D4A818` + texte « COMPÉTITION MENSUELLE · BERNISSART » (Nunito 10px / 700 / uppercase / letter-spacing 0.18em / `#D4A818`).
   - H1 : « Tir du mois de mai 2026 » en **Teko 56px / weight 600 / uppercase / letter-spacing 0.02em / line-height 1**, couleur `#F5F1DC`.
   - Méta (margin-top 14px) : « 8 mai 2026 » · puce ronde `4px` `#D4A818` opacity 0.6 · « Tir à la plaquette ». Nunito 13px couleur `rgba(245,241,220,0.5)`.
   - Cible décorative SVG `90×90px`, `opacity: 0.12`, alignée en bas à droite : cercles concentriques (or `#D4A818` extérieurs, rouge `#C82020` intérieurs) + croix pointillée.

4. **Tabs**
   - Conteneur flex, `border-bottom: 1px solid #D5CEB0`, margin-bottom `30px`.
   - Chaque onglet : bouton Nunito 14px, padding `18px 22px 14px`, `margin-bottom: -1px`.
   - Inactif : couleur `#8A8470`, weight 600, `border-bottom: 2px solid transparent`.
   - Actif : couleur `#1E1E1C`, weight 700, `border-bottom: 2px solid #2C6B35`.
   - Transition `color 0.18s`. Onglets : Général / Standard / Appuyé.

5. **Titre de section (H2)**
   - Teko 28px / weight 500 / uppercase / letter-spacing 0.03em, couleur `#1E1E1C`, margin `0 0 20px`. Texte dépend de l'onglet (ex. « Tir plaquettes appuyé »).

6. **Table (card)**
   - Fond `#FFFFFF`, `border-radius: 6px`, `overflow: hidden`, `box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 8px 28px rgba(0,0,0,0.07)`.
   - **En-tête de colonnes** : grille `64px 1fr 120px`, padding `10px 22px`, fond `#F5F1DC`, `border-bottom: 1px solid #E0D8BE`. Labels Nunito 10px / 700 / uppercase / letter-spacing 0.12em / `#8A8470`. « Plaquettes » aligné à droite.
   - **Lignes** : grille `64px 1fr 120px`, `align-items: center`, padding `13px 22px`, `border-bottom: 1px solid #F0EAD6`. Animation d'entrée `fadeRow` (0.25s ease, translateY 5px→0, delay `index × 0.05s`).
     - **Badge rang** : cercle `32×32px`, Teko 15px / 600.
       - Rang 1 (or héraldique) : fond `#D4A818`, `border: 2px solid #B88E10`, texte `#1E1E1C`, fond de ligne `rgba(212,168,24,0.08)`, `box-shadow: inset 3px 0 0 #D4A818`.
       - Rang 2 (argent) : fond `#8E9298`, `border: 2px solid #767A80`, texte `#FFFFFF`, fond de ligne `rgba(142,146,152,0.06)`, `inset 3px 0 0 #8E9298`.
       - Rang 3 (bronze) : fond `#9A6543`, `border: 2px solid #7E5235`, texte `#FFFFFF`, fond de ligne `rgba(154,101,67,0.07)`, `inset 3px 0 0 #9A6543`.
       - Rangs 4+ : fond `#EAE4C8`, pas de bordure, texte `#6A6455`, fond de ligne transparent, pas d'inset.
     - **Nom participant** : Nunito 15px / 600 / `#1E1E1C`.
     - **Plaquettes** : Teko 26px / 500 / `#1E1E1C`, aligné à droite.
   - Note sous la table : « * Membre du club », Nunito 12px / `#9A9280`.

---

## Interactions & Behavior
- **Onglets** : cliquer sur Général / Standard / Appuyé remplace le titre H2 et le jeu de lignes. Pas de navigation de page — changement d'état local.
- **Toggle thème** : bascule clair ↔ sombre (voir tokens dark ci-dessous). En prod, appliquer via `data-theme` sur la racine ou classe conditionnelle.
- **Animation des lignes** : à chaque changement d'onglet, les lignes ré-entrent en `fadeRow` avec un délai échelonné (`index × 0.05s`).
- **Hover** : liens breadcrumb → `#D4A818` ; toggle → `opacity 0.65`.
- Transitions de thème : `background`/`color`/`border-color` à `0.35s`.

## State Management
- `activeTab` : `'general' | 'standard' | 'appuye'` (défaut `'appuye'`).
- `theme` : `'light' | 'dark'` (défaut `'light'`).
- Données : objet statique `{ general, standard, appuye }`, chacun `{ title, rows: [{ rang, participant, plaquettes }] }`. Les badges/couleurs de médaille sont dérivés de l'index de ligne (0/1/2 = or/argent/bronze, sinon style « plain »).

### Données (exactes)
```
général  : David D.* 12 · Marie-Jo S.* 9 · Felipe S.* 7 · Anastasios P.* 5 · Thomas B. 3
standard : Felipe S.* 6 · David D.* 5 · Anastasios P.* 4 · Marie-Jo S.* 2
appuyé   : David D.* 7 · Felipe S.* 4 · Marie-Jo S.* 3 · Anastasios P.* 0
```
(`*` = membre du club)

---

## Design Tokens

### Typographie
- **Display / titres** : `Teko` (Google Fonts), weights 400/500/600/700. Toujours uppercase pour titres.
- **Corps / UI** : `Nunito` (Google Fonts), weights 400/500/600/700.
- **Mono** : `JetBrains Mono` (utilisé dans le design system seulement).

### Couleurs — v2 clair (thème principal de cette page)
| Rôle | Hex |
|---|---|
| Primary (vert forêt) | `#2C6B35` |
| Primary focus | `#1F5229` |
| Accent (rouge cible) | `#C82020` |
| Or héraldique | `#D4A818` / focus `#B88E10` |
| Secondary (anthracite chaud) | `#1E1E1C` |
| Neutral (olive) | `#5A6050` |
| Base-100 (crème) | `#F5F1DC` |
| Base-200 | `#EAE4C8` |
| Base-300 (bordures) | `#D5CEB0` |
| Base-content (texte) | `#1E1E1C` |
| Argent (médaille 2) | `#8E9298` |
| Bronze (médaille 3) | `#9A6543` |
| Texte atténué | `#8A8470` / `#9A9280` |

### Couleurs — v2 sombre
| Rôle | Hex |
|---|---|
| Page / base-100 | `#0C140E` |
| Nav / hero | `#071009` |
| Base-200 (cards) | `#141E16` |
| Base-300 (bordures) | `#1C2A1E` |
| Primary (vert clair) | `#4CAF68` |
| Accent (rouge vif) | `#E54040` |
| Or lumineux | `#E8C240` |
| Texte principal | `#C4C0A8` |
| Texte atténué | `#3A4A3A` |

### Radius / Espacements
- Card radius `6px` ; badges `50%` ; toggle pill `20px`.
- Largeur contenu max `980px`, padding horizontal `32px`.
- Grille table : `64px 1fr 120px`.

### Tokens prêts à l'emploi (fournis dans le bundle)
- `tokens-v2.css` — variables CSS DaisyUI-style (hex), thèmes clair + sombre.
- `theme-v2.css` / `theme-v2-dark.css` — format **DaisyUI v4** (oklch).
- `tailwind.config.js` — config Tailwind + DaisyUI v3 (HSL), 4 thèmes : `gais-v1`, `gais-v1-dark`, `gais-v2`, `gais-v2-dark`.

## Assets
- `assets/logo.webp` — logo du club (médaillon rond). Utilisé dans la nav (34px) et dans le design system (40–90px).
- Toutes les autres icônes (soleil, lune, cible) sont des **SVG inline** décrits ci-dessus — pas de fichiers externes.

## Files
- `Classement v2.dc.html` — page de classement (cette spec).
- `Classement.dc.html` — variante v1 (or/anthracite), pour référence.
- `Design System v2.dc.html` — référence visuelle complète de la palette v2.
- `Design System.dc.html` — référence v1.
- `tokens-v2.css`, `theme-v2.css`, `theme-v2-dark.css`, `tailwind.config.js` — tokens.
- `assets/logo.webp` — logo.

> Note : ne pas réutiliser `support.js` (runtime de prévisualisation interne). Les `.dc.html` sont des références visuelles — réimplémenter dans le framework cible.
