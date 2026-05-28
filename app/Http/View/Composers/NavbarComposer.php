<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class NavbarComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $cartData = $this->getCartData();

        $view->with([
            'cartCount' => $cartData['cartCount'],
            'downloadCount' => $cartData['downloadCount'],
            'totalItems' => $cartData['totalItems'],
            'cart' => $cartData['cart']
        ]);
    }

    /**
     * Get cart data for navbar
     */
    private function getCartData(): array
    {
        $cartCount = 0;
        $downloadCount = 0;
        $cart = [];

        if (Auth::check()) {
            $cart = session('cart', []);
            foreach ($cart as $item) {
                if (($item['type'] ?? '') === 'download') {
                    $downloadCount += $item['quantity'] ?? 0;
                } else {
                    $cartCount += $item['quantity'] ?? 0;
                }
            }
        }

        return [
            'cartCount' => $cartCount,
            'downloadCount' => $downloadCount,
            'totalItems' => $cartCount + $downloadCount,
            'cart' => $cart
        ];
    }
}
