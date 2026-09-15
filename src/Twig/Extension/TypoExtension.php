<?php

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TypoExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('typo', [$this, 'typo']),
        ];
    }

    public function typo(?string $text): string
    {
        if (null === $text || '' === $text) {
            return '';
        }

        // Espace fine insécable avant ? ! ; et après «
        $text = preg_replace('/[ \x{00A0}]*([?!;])/u', "\u{00A0}$1", $text);
        $text = preg_replace('/«[ \x{00A0}]*/u', "«\u{00A0}", $text);

        // Espace insécable (large) avant les deux-points et avant »
        $text = preg_replace('/[ \x{00A0}]*([:»])/u', "\u{00A0}$1", $text);

        return $text;
    }
}