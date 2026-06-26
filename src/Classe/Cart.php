<?php

namespace App\Classe;

use Symfony\Component\HttpFoundation\RequestStack;

class Cart{
    public function __construct(
        private RequestStack $requestStack,
    ) {}

    public function add($product){
        // call session
        // $session = $this->requestStack->getSession();
        $cart = $this->requestStack->getSession()->get('cart');

        // add quantity
        if($cart[$product->getId()]) {
            $cart[$product->getId()] = [
            'product' => $product,
            'qty' => $cart[$product->getId()]['qty'] ++
        ];
        }else{
            $cart[$product->getId()] = [
            'product' => $product,
            'qty' => 1
        ];
        
        }
        

        // create session cart
        $this->requestStack->getSession()->set('cart',$cart);

        
        
    }
    public function getCart()
    {
        return $this->requestStack->getSession()->get('cart');
    }
}