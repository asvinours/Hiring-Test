<?php

namespace SSENSE\HiringTest\Models;

class Products extends BaseModel
{
    
    protected $tableName = 'products';

    public function getWithFilters(array $filters = [])
    {
        // Check mandatory fields
        $this->checkMandatoryFields();
        
        $where = '';
        if (!empty($filters)) {
           $where = 'WHERE 1';
            foreach( $filters as $field => $filter) {
                list($operator, $value) = $filter;
                $where .= ' AND ' . $field . ' '. $operator . ' ' . $value;
            } 
        }
        
        // Execute the query and fetch results
        $sqlTemplate = 'SELECT * FROM products
                            JOIN prices ON products.id = prices.product_id 
                            JOIN countries ON countries.id = prices.country_id 
                            JOIN currencies ON currencies.id = countries.currency_id
                            JOIN stocks ON stocks.product_id = products.id';
        $statement = $this->connexion->executeQuery( $sqlTemplate . ' ' . $where);
        $results = $statement->fetchAll();
        
        return $results;
    }
}

