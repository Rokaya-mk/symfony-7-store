<?php

namespace App\Twig;

use App\Classe\Cart;
use App\Repository\CategoryRepository;
use Override;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension implements GlobalsInterface
{
    private $categoryRepository;
    private $cart;
    public function __construct(CategoryRepository $categoryRepository,Cart $cart)
    {
       $this->categoryRepository = $categoryRepository;
       $this->cart = $cart;
    }
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

    #[Override]
    public function getGlobals(): array
    {
         return [
            'allCategories' => $this->categoryRepository->findAll(),
            'totalQuantity' => $this->cart->totalQuantity()
        ];
    }
    
   
    
}
