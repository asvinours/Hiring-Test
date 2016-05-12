<?php
namespace SSENSE\HiringTest\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use SSENSE\HiringTest\Models\Products;

class ProductController 
{
    
    /**
     * Display the canadien products
     * 
     * @param Application $app
     * @param Request $request 
     */
    public function listAction(Application $app, Request $request)
    {
        // Create new repository object
        $productRepository = new Products($app);
        
        // retrieve the list of products
        $products = $productRepository->getWithFilters([
            'currencies.code' => ['=', '"CAD"'],
            'stocks.quantity' => ['>', 0],
        ]);
        
        // Render the page
        return $app['twig']->render(
            'products/list.html', 
            [
                'products' => $products,
                'styles' => [ '/assets/css/products.css' ],
            ]);
    }
}
