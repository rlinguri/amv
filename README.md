# AMV
_Abstract Class Library for PHP Applications_

### class AMVDatabase ###

##### Four Steps to Interacting with Database #####

1. Access the database connection

        $db = YourExtendedClass::db();
    
2. Set the Statement Parameter

        $db->setStmt('SELECT * FROM table WHERE column = ?');
    
3. Execute the Statement, passing in optional parameters

        $db->executeStmt('value');
    
4. Access the output (optional)

        $record = $db->fetchRecord();   // returns assoc
        $results = $db->fetchRecords(); // returns array
        $id = $db->lastInsertId();      // returns integer