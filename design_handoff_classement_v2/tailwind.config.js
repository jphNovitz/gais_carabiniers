/** @type {import('tailwindcss').Config} */

// ================================================================
//  Tailwind + DaisyUI v3 — Les Gais Carabiniers
//
//  IMPORTANT : DaisyUI v3 exige les couleurs en HSL séparé par
//  espaces ("H S% L%"), PAS en hex. Les utilitaires Tailwind
//  (bg-primary, text-primary-content…) sont générés automatique-
//  ment par le plugin DaisyUI — pas besoin de les redéclarer dans
//  theme.extend.colors.
//
//  Installation :
//    npm install -D tailwindcss daisyui
//    npx tailwindcss init
//
//  DaisyUI v4 (Tailwind v4) → config différente, voir doc officielle
// ================================================================

module.exports = {
  content: [
    './**/*.{html,js,jsx,ts,tsx}',
    '!./node_modules/**/*',
  ],

  // Le dark mode est géré par DaisyUI via data-theme="dark"
  // Ne pas utiliser 'class' ou 'media' — DaisyUI s'en charge
  darkMode: ['class', '[data-theme="dark"]'],

  theme: {
    extend: {

      // ── Polices ─────────────────────────────────────────────
      fontFamily: {
        // v1 — Barlow
        display: ['"Barlow Condensed"', 'sans-serif'],
        body:    ['Barlow',             'sans-serif'],
        // v2 — Teko + Nunito
        teko:    ['Teko',               'sans-serif'],
        nunito:  ['Nunito',             'sans-serif'],
        // Commun
        mono:    ['"JetBrains Mono"',   'monospace'],
      },

      // ── Ombres custom ────────────────────────────────────────
      // Les couleurs DaisyUI (bg-primary etc.) sont gérées par le plugin.
      // On ajoute seulement les shadows spécifiques au projet.
      boxShadow: {
        card:         '0 1px 3px rgba(0,0,0,0.06), 0 6px 24px rgba(0,0,0,0.05)',
        subtle:       '0 1px 3px rgba(0,0,0,0.05)',
        'medal-gold': 'inset 3px 0 0 hsl(var(--p))',
      },

    },
  },

  plugins: [
    require('daisyui'),
  ],

  // ================================================================
  //  Config DaisyUI
  //  Toutes les couleurs en HSL séparé par espaces : "H S% L%"
  //  (sans "hsl()" ni virgules)
  // ================================================================
  daisyui: {
    logs: true,
    themes: [

      // ── Thème v1 : Or / Anthracite / Bronze ─────────────────
      {
        'gais-v1': {
          // Primary — Or
          'primary':                   '42 60% 47%',    // #C09A30
          'primary-focus':             '37 61% 40%',    // #A88528
          'primary-content':           '0 0% 100%',     // #FFFFFF

          // Secondary — Anthracite
          'secondary':                 '234 12% 11%',   // #18191E
          'secondary-focus':           '234 9% 18%',    // #2C2D34
          'secondary-content':         '45 14% 90%',    // #ECEAE4

          // Accent — Bronze
          'accent':                    '25 39% 43%',    // #9A6543
          'accent-focus':              '24 39% 36%',    // #7E5235
          'accent-content':            '0 0% 100%',     // #FFFFFF

          // Neutral — Gris chaud
          'neutral':                   '22 4% 46%',     // #787470
          'neutral-focus':             '22 4% 38%',     // #615E5A
          'neutral-content':           '0 0% 100%',     // #FFFFFF

          // Base — Fond chaud
          'base-100':                  '45 14% 90%',    // #ECEAE4
          'base-200':                  '44 13% 85%',    // #DEDAD2
          'base-300':                  '40 9% 77%',     // #CCC9C2
          'base-content':              '234 12% 11%',   // #18191E

          // États sémantiques
          'info':                      '217 91% 60%',   // #3B82F6
          'info-content':              '0 0% 100%',
          'success':                   '142 71% 45%',   // #22C55E
          'success-content':           '0 0% 100%',
          'warning':                   '38 92% 50%',    // #F59E0B
          'warning-content':           '0 0% 100%',
          'error':                     '0 84% 60%',     // #EF4444
          'error-content':             '0 0% 100%',

          // DaisyUI UI variables
          '--rounded-box':             '0.5rem',
          '--rounded-btn':             '0.375rem',
          '--rounded-badge':           '9999px',
          '--animation-btn':           '0.15s',
          '--animation-input':         '0.15s',
          '--btn-focus-scale':         '0.97',
          '--border-btn':              '1px',
          '--tab-border':              '1px',
          '--tab-radius':              '0.375rem',
        },
      },

      // ── Thème v1 dark : Or / Charbon ────────────────────────
      {
        'gais-v1-dark': {
          'primary':                   '42 60% 47%',    // #C09A30
          'primary-focus':             '42 62% 55%',    // #D4AE44
          'primary-content':           '234 12% 11%',   // #18191E

          'secondary':                 '45 10% 82%',    // #D8D4CA
          'secondary-focus':           '45 12% 88%',    // #E8E4DA
          'secondary-content':         '234 12% 11%',   // #18191E

          'accent':                    '25 32% 56%',    // #B07653
          'accent-focus':              '25 33% 63%',    // #C48667
          'accent-content':            '234 12% 11%',

          'neutral':                   '240 4% 31%',    // #4A4A52
          'neutral-focus':             '240 4% 37%',    // #5A5A62
          'neutral-content':           '45 14% 90%',    // #ECEAE4

          'base-100':                  '228 16% 6%',    // #0D0F14
          'base-200':                  '228 14% 11%',   // #161820
          'base-300':                  '228 13% 14%',   // #1E2028
          'base-content':              '40 9% 74%',     // #C4C0B6

          'info':                      '213 94% 68%',   // #60A5FA
          'info-content':              '228 16% 6%',
          'success':                   '142 69% 58%',   // #4ADE80
          'success-content':           '228 16% 6%',
          'warning':                   '43 96% 64%',    // #FCD34D
          'warning-content':           '228 16% 6%',
          'error':                     '0 91% 71%',     // #F87171
          'error-content':             '228 16% 6%',

          '--rounded-box':             '0.5rem',
          '--rounded-btn':             '0.375rem',
          '--rounded-badge':           '9999px',
          '--animation-btn':           '0.15s',
          '--animation-input':         '0.15s',
          '--btn-focus-scale':         '0.97',
          '--border-btn':              '1px',
          '--tab-border':              '1px',
          '--tab-radius':              '0.375rem',
        },
      },

      // ── Thème v2 : Vert forêt / Crème / Rouge cible ─────────
      {
        'gais-v2': {
          // Primary — Vert forêt
          'primary':                   '130 41% 30%',   // #2C6B35
          'primary-focus':             '130 44% 23%',   // #1F5229
          'primary-content':           '0 0% 100%',

          // Secondary — Anthracite chaud
          'secondary':                 '60 3% 12%',     // #1E1E1C
          'secondary-focus':           '60 4% 17%',     // #2C2C28
          'secondary-content':         '47 65% 91%',    // #F5F1DC

          // Accent — Rouge cible
          'accent':                    '0 72% 46%',     // #C82020
          'accent-focus':              '0 72% 37%',     // #A51818
          'accent-content':            '0 0% 100%',

          // Neutral — Olive / kaki
          'neutral':                   '90 9% 34%',     // #5A6050
          'neutral-focus':             '90 12% 27%',    // #464C3C
          'neutral-content':           '0 0% 100%',

          // Base — Crème tradition
          'base-100':                  '47 65% 91%',    // #F5F1DC
          'base-200':                  '46 48% 85%',    // #EAE4C8
          'base-300':                  '43 30% 76%',    // #D5CEB0
          'base-content':              '60 3% 12%',     // #1E1E1C

          // États (adaptés à la palette)
          'info':                      '208 59% 43%',   // #2E78B0
          'info-content':              '0 0% 100%',
          'success':                   '130 41% 30%',   // = primary
          'success-content':           '0 0% 100%',
          'warning':                   '44 78% 46%',    // #D4A818 (or)
          'warning-content':           '60 3% 12%',
          'error':                     '0 72% 46%',     // = accent
          'error-content':             '0 0% 100%',

          '--rounded-box':             '0.375rem',
          '--rounded-btn':             '0.3rem',
          '--rounded-badge':           '9999px',
          '--animation-btn':           '0.15s',
          '--animation-input':         '0.15s',
          '--btn-focus-scale':         '0.97',
          '--border-btn':              '1px',
          '--tab-border':              '1px',
          '--tab-radius':              '0.3rem',
        },
      },

      // ── Thème v2 dark : Vert nuit / Crème sombre ────────────
      {
        'gais-v2-dark': {
          'primary':                   '134 39% 49%',   // #4CAF68
          'primary-focus':             '134 40% 56%',   // #5EC47B
          'primary-content':           '136 29% 6%',    // #0A150C

          'secondary':                 '46 28% 85%',    // #E8E3CC
          'secondary-focus':           '47 65% 91%',    // #F5F1DC
          'secondary-content':         '60 3% 12%',     // #1E1E1C

          'accent':                    '0 76% 57%',     // #E54040
          'accent-focus':              '0 84% 63%',     // #F05252
          'accent-content':            '60 3% 12%',

          'neutral':                   '90 10% 28%',    // #484E3E
          'neutral-focus':             '90 10% 34%',    // #585E4E
          'neutral-content':           '47 17% 71%',    // #C4C0A8

          'base-100':                  '136 25% 6%',    // #0C140E
          'base-200':                  '130 20% 10%',   // #141E16
          'base-300':                  '130 18% 14%',   // #1C2A1E
          'base-content':              '47 17% 71%',    // #C4C0A8

          'info':                      '213 94% 68%',
          'info-content':              '136 25% 6%',
          'success':                   '134 39% 49%',
          'success-content':           '136 25% 6%',
          'warning':                   '45 80% 57%',    // #E8C240
          'warning-content':           '60 3% 12%',
          'error':                     '0 76% 57%',
          'error-content':             '60 3% 12%',

          '--rounded-box':             '0.375rem',
          '--rounded-btn':             '0.3rem',
          '--rounded-badge':           '9999px',
          '--animation-btn':           '0.15s',
          '--animation-input':         '0.15s',
          '--btn-focus-scale':         '0.97',
          '--border-btn':              '1px',
          '--tab-border':              '1px',
          '--tab-radius':              '0.3rem',
        },
      },

    ],

    // Thème appliqué par défaut
    base:    true,
    styled:  true,
    utils:   true,
    prefix:  '',       // Pas de préfixe sur les classes DaisyUI
    logs:    true,
    rtl:     false,
  },
};
