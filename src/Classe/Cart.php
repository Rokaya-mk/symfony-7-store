<?php

namespace App\Classe;

use Symfony\Component\HttpFoundation\RequestStack;

class Cart{
    public function __construct(
        private RequestStack $requestStack,
    ) {}

    /**
     * ajouter produit un produitou une quantité au panier 
     */
    public function add($product){
        // call session
        $cart = $this->getCart();
        // add quantity
         $id = $product->getId();
        if(isset($cart[$id])) {
             $cart[$id]['qty']++;
        
        }else{
            $cart[$id] = [
            'product' => $product,
            'qty' => 1
        ];
        
        }
        

        // create session cart
        $this->getCart()->set('cart',$cart);

        
        
    }
     /**
     * supprimer produit un produit ou une dimnuer quantité au panier 
     */
    public function decrease($id){
        $cart = $this->getCart();
        if($cart[$id]['qty'] > 1){
            $cart[$id]['qty']-- ;
        }else{
            unset($cart[$id]);
        }

       return $this->requestStack->getSession()->set('cart',$cart);
    }
      /**
     * Récupérer le panier produits 
     */
    public function getCart()
    {
        return $this->requestStack->getSession()->get('cart');
    }
    /**
     * Supprimer le panier produits 
     */
    public function remove()
    {
        return $this->requestStack->getSession()->remove('cart');
    }

    /**
     * Calculer quantité total produits 
     */
    public function totalQuantity():int
    {
        $cart = $this->getCart();
        $totalQty = 0;
        if(isset($cart)){
            foreach( $cart as $product ){
                $totalQty += $product['qty'];
            }
        }
        return $totalQty;

    }

     /**
     * Calculer prix total hors tax produits 
     */

    public function getTotalWt():float
    {
        $cart = $this->requestStack->getSession()->get('cart');
        $total = 0;
        if(isset($cart)){
            foreach( $cart as $product ){
                $total += ($product['product']->getPriceWithTva() * $product['qty']);
            }
        }
        return $total;

    }
}