<?php
namespace SSENSE\HiringTest\Models;

use Silex\Application;

abstract class BaseModel
{
    /**
     * Database connexion
     * 
     * @var \Doctrine\DBAL\Connection
     */
    protected $connexion;
    
    /**
     * Name of the table to use for the queries
     * 
     * @var string
     */
    protected $tableName;
    
    /**
     * Class constructor - store DB connexion to facilitate queries
     * 
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->connexion = $app['db'];
    }
    
    /**
     * Return a model by Id 
     * 
     * @param int $id
     * @return Array
     * @throw \Exception
     */
    public function getById($id)
    {
        // Check mandatory fields
        $this->checkMandatoryFields();
        
        // Query the table with the given id
        $statement = $this->connexion->executeQuery('SELECT * FROM ' . $this->tableName . ' WHERE id = ?');
        $result = $statement->fetchAssoc($statement, [(int)$id]);
        
        return $result;
    }
    
    /**
     * Return all models corresponding to given filters
     * 
     * @param Array $filters - field => value pairs of filters that models must have
     *                       - Ex: ['category_id' => 1] will search for "category_id = 1"
     * @return Array
     * @throw \Exception
     */
    public function getAll(Array $filters = [])
    {
        // Check mandatory fields
        $this->checkMandatoryFields();
        
        // Add the filters if given
        if ($filters) {
            $where = 'WHERE 1';
            foreach( $filters as $field => $value) {
                $where .= ' AND ' . $field . ' = "' . $value . '"';
            }
        } else {
            $where = '';
        }
        
        // Execute the query and fetch results
        $statement = $this->connexion->executeQuery('SELECT * FROM ' . $this->tableName . ' ' . $where);
        $results = $statement->fetchAll();
        
        return $results;
    }
    
    /**
     * Verify that all mandatory field to make a query are set
     * 
     * @throw \Exception
     */
    protected function checkMandatoryFields()
    {
        if (empty($this->tableName)) {
            throw new \Exception('The tableName parameter is mandatory');    
        }
    }

}
