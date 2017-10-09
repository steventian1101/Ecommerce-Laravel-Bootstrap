<?php

namespace App\Http\Controllers;

session_start();

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Publics\ProductsModel;
use App\Cart;

class Controller extends BaseController
{

    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

    protected $products = [];

    /*
     * Get all products from cart
     * Get all products from database
     * Save all this to $products variable to be accessable from all controllers
     */

    public function __construct()
    {
        $cart = new Cart();
        $productsModel = new ProductsModel();

        $products_ids = $cart->getCartProducts();
        $unique_ids = array_unique($products_ids);

        $products = [];
        if (!empty($products_ids)) {
            $products = $productsModel->getProductsWithIds($unique_ids);
            foreach ($products as &$product) {
                $counts = array_count_values($products_ids);
                $numAddedToCart = $counts[$product->id];
                $product->num_added = $numAddedToCart;
            }
        }
        return $this->products = $products;
    }

}
