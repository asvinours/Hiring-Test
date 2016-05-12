<?php

// To import all the data in MySQL run the following command:
// php install/import-data.php --filename data/categories.json --filename data/currencies.json --filename data/countries.json --filename data/products.json --filename data/stocks.json --filename data/prices.json
$debug = true;
// Check for the args passed to the script
$options= getopt(null, [
    'filename:'
]);
$files = isset($options['filename']) ? $options['filename'] : [];
if (empty($files)) {
   println("You need to specify at least one file to import"); 
}
if (!is_array($files)) {
   $files = [$files]; 
}

// First we need to conenct to the DB
try {
    $mysqli = new mysqli('localhost', 'root', '', 'ssense-test');
    if (mysqli_connect_errno()) {
        throw new \Exception(mysqli_connect_error());
    }
    $mysqli->set_charset("utf8");
} catch (\Exception $ex) {
    println("Error: Unable to connect to the MySQL DB");
    if ($debug) {
        println("Error: " . $ex->getMessage());
    }
    exit();
}

// Now we have our DB connection, let's use it
foreach ($files as $file) {
    $path = normalizeFilePath($file);
    $data = getFileContent($path);
    $table = getTableNameFromFile($path);
    
    try {
        $mysqli->begin_transaction ();
        $query = "INSERT INTO `%s` (`%s`) VALUES ('%s');";
        foreach ($data as $entity) {
            $keys = array_keys($entity);
            $values = array_values($entity);
            $sql = sprintf($query, $table, implode('`,`', $keys), implode('\',\'', $values));
            println($sql);
            $mysqli->query($sql);
        }
        $res = $mysqli->commit();
        if (!$res) {
            $error = $mysqli->error;
            throw new \Exception("Unable to commit transaction: " . $error); 
        }
    } catch (\Exception $ex) {
        println("Unable to run the commit query");
        $mysqli->rollback();
        if ($debug) {
            println("Error: " . $ex->getMessage());
        }
    }
}



/*
 * Functions
 *
*/

function getTableNameFromFile($file)
{
    $info = pathinfo($file);
    
    if (empty($info['filename'])) {
        throw new \Exception("Unable to extract table name from file");
    }
    
    return $info['filename'];
}

function normalizeFilePath($file)
{
    // check if the file path start by a /
    if (!strpos($file, '/') === 0) {
        // Assume it is a relative path
        return dirname(__FILE__) . $file;
    }
    
    return $file;
}

function verifyFile($path)
{
    if (!file_exists($path) || !is_readable($path)) {
        throw new \Exception("Unable to open the following file: ".$path);
    }
    
    return true;
}

function getFileContent($path)
{
    // Verify we can read the file
    $res = verifyFile($path);
    
    // get the content and decode it
    $jsonContent = file_get_contents($path);
    $data = json_decode($jsonContent, true);
    
    if(!$data) {
        throw new \Exception("Invalid JSON data");
    }
    
    // return the array
    return $data;
}

function println($line)
{
    echo $line.PHP_EOL;
}