<?php

namespace SSENSE\HiringTest\Models;

/**
 * Class Products
 * @package SSENSE\HiringTest\Models
 */
class Products extends BaseModel
{

    protected $tableName = 'products';

    /**
     * @param array $filters
     * @return mixed
     * @throws \Exception
     */
    public function getWithFilters(array $filters = [])
    {
        // Check mandatory fields
        $this->checkMandatoryFields();

        $where = '';
        if (!empty($filters)) {
            $where = 'WHERE 1';
            foreach ($filters as $field => $filter) {
                list($operator, $value) = $filter;
                $where .= ' AND ' . $field . ' ' . $operator . ' ' . $value;
            }
        }

        // Execute the query and fetch results
        $sqlTemplate = 'SELECT products.name, products.id, categories.name as "category_name", currencies.format, prices.price, stocks.quantity FROM products
                            JOIN prices ON products.id = prices.product_id
                            JOIN countries ON countries.id = prices.country_id
                            JOIN currencies ON currencies.id = countries.currency_id
                            JOIN stocks ON stocks.product_id = products.id
                            JOIN categories ON categories.id = products.category_id';
        $statement = $this->connexion->executeQuery($sqlTemplate . ' ' . $where);

        // fetch results
        $results = $statement->fetchAll();

        return $results;
    }
}