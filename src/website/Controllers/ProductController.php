<?php
namespace SSENSE\HiringTest\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use SSENSE\HiringTest\Models\Products;

class ProductController 
{
    
    public function listAction(Application $app, Request $request)
    {
        $productRepository = new Products($app);
        $products = $productRepository->getWithFilters([
            'currencies.code' => ['=', '"CAD"'],
            'stocks.quantity' => ['>', 0],
        ]);
        
        // Render the page
        return $app['twig']->render('product/list.html', []);
    }
}
