<?php
namespace LTIPlatform;
?>
<!DOCTYPE html>
<html lang="en">
<head>
        <title>Setting up database</title>
</head>

<body>
    <h3>Creating tables ...</h3>

<?php

// include database and core files
// require_once 'database.php';
require_once dirname(__FILE__).'\database.php';

//generate table if not exist
function createTable($name, $query)
{

    // instantiate database object
    $database = new Database();
    $db = $database->getConnection();

    $createTable = "CREATE TABLE IF NOT EXISTS $name($query) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    $stmt = $db->prepare($createTable);
    try
    {
        $stmt->execute();
    }
    catch(\PDOException $e)
    {
        echo $e->getMessage();
        
    }
}

function alterTable($name, $query)
{

    // instantiate database object
    $database = new Database();
    $db = $database->getConnection();

    $alterTable = "ALTER TABLE $name $query";
    $stmt = $db->prepare($alterTable);
    try
    {
        $stmt->execute();
    }
    catch(\PDOException $e)
    {
        echo $e->getMessage();
        
    }
}

createTable(
    'lti2_tool',
    'tool_pk int(11) NOT NULL AUTO_INCREMENT,
    name varchar(50) NOT NULL,
    consumer_key varchar(255) DEFAULT NULL,
    secret varchar(1024) DEFAULT NULL,
    message_url varchar(255) DEFAULT NULL,
    initiate_login_url varchar(255) DEFAULT NULL,
    redirection_uris text DEFAULT NULL,
    public_key text DEFAULT NULL,
    lti_version varchar(10) DEFAULT NULL,
    signature_method varchar(15) DEFAULT NULL,
    settings text DEFAULT NULL,
    enabled tinyint(1) NOT NULL,
    enable_from datetime DEFAULT NULL,
    enable_until datetime DEFAULT NULL,
    last_access date DEFAULT NULL,
    created datetime NOT NULL,
    updated datetime NOT NULL,
    PRIMARY KEY (tool_pk)'
);

alterTable(
    'lti2_tool',
    'ADD UNIQUE INDEX lti2_tool_initiate_login_url_UNIQUE (initiate_login_url ASC)'
);
// Total of 1 table
?>

<br>...done.

</body>
</html>