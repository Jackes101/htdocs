<?php
$username = 'root';
$password = 'ahojsvete';
$host = 'localhost';
$dbname = 'IWWW';

$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);


//PDO has three error handling modes.
//
//PDO::ERRMODE_SILENT acts like mysql_* where you must check each result and then look at $db->errorInfo(); to get the error details.
//PDO::ERRMODE_WARNING throws PHP Warnings
//PDO::ERRMODE_EXCEPTION throws PDOException. In my opinion this is the mode you should use. It acts very much like or die(mysql_error()); when it isn't caught, but unlike or die() the PDOException can be caught and handled gracefully if you choose to do so.


try {
    //connect as appropriate as above
    $pdo->query('hi'); //invalid query!
} catch (PDOException $ex) {
    echo "An Error occured!" . $ex->getMessage(); //user friendly message
}

echo "\n\n";
## simpliest sql query
## fetching data :: loop style
$sql = 'SELECT * FROM emails ORDER BY id DESC';
$q = $pdo->query($sql);
$q->setFetchMode(PDO::FETCH_ASSOC);

var_dump($q->fetch());

while ($row = $q->fetch(PDO::FETCH_ASSOC)) {
    echo $row['id'] . ' ' . $row['name']; //etc...
}

echo "\n\n";
## fetching data :: all at once
$sql = 'SELECT * FROM iwww_emails ORDER BY id DESC';
$q = $pdo->query($sql);
var_dump($q->fetchAll(PDO::FETCH_ASSOC));

//Note the use of PDO::FETCH_ASSOC in the fetch() and fetchAll() code above. This tells PDO to return the rows as an associative array with the field names as keys. Other fetch modes like PDO::FETCH_NUM returns the row as a numerical array. The default is to fetch with PDO::FETCH_BOTH which duplicates the data with both numerical and associative keys. It's recommended you specify one or the other so you don't have arrays that are double the size! PDO can also fetch objects with PDO::FETCH_OBJ, and can take existing classes with PDO::FETCH_CLASS. It can also bind into specific variables with PDO::FETCH_BOUND and using bindColumn method. There are even more choices! Read about them all here: PDOStatement Fetch documentation.

echo "\n\n";
//Running Statements With Parameters 1.
$id = 3;
$stmt = $pdo->prepare("SELECT * FROM iwww_emails WHERE id=?  ORDER BY id DESC");
$stmt->execute(array($id));
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
var_dump($rows);

echo "\n\n";
//Running Statements With Parameters 2.
$id = 2;
$stmt = $pdo->prepare("SELECT * FROM iwww_emails WHERE id=:id  ORDER BY id DESC");
$stmt->bindValue(":id", $id, PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
var_dump($rows);


?>