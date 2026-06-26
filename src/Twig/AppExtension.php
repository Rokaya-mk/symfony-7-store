<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('prix', [$this, 'formatPrice']),
        ];
    }

    public function formatPrice($number)
    {
        $price = number_format($number, '2',',');
        $price = $price . ' MAD';

        return $price;
    }
    
}
