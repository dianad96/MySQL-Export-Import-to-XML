<?php

//Getting XML file
$path = 'mydb-backup-1486313758.xml';
if (file_exists($path)) {
    $xml = new SimpleXMLElement($path, 0, true);
    //echo $xml->asXML();
} else {
    exit('Failed to open xml file.');
}


// <---------------------------Create Array of Ordered Tables------------------------------------------------------>

//Gets the Tables without any references -> this means these tables can be created first
function checkTableHaveReferences($xml, $tableName)
{
    $ok=0;
    foreach ($xml->table as $table) { //iterate tables
      if (strcmp($table['name'], $tableName)==0) {
          foreach ($table->columns->column as $column) {
              if ($column['referenced_table_name']!=null) {
                  $ok=1;
              }
          }
      }
    }
    return $ok;
}

function checkTableExistsInOrder($xml, $tableName, $tables_order)
{
    $ok=0;
    foreach ($tables_order as $table) {
        if (strcmp($table, $tableName)==0) {
            $ok=1;
        }
    }
    return $ok;
}

// Checks that the Tables it references are already in the ordered table;
// if so, it means this table can be created and so add it to the ordered array;
// if not, move on
function checkReferencedAreOrdered($xml, $tables_order, $tableName)
{
    $ok=0;
    foreach ($xml->table as $table) { //iterate tables
      if (strcmp($table['name'], $tableName)==0) { //find the table we are looking for
        foreach ($table->columns->column as $column) {
            if ($column['referenced_table_name']!=null) {
                if (!checkTableExistsInOrder($xml, $column['referenced_table_name'], $tables_order)) { //check that it references any tables that are not in the ordered array
                    $ok=1;
                }
            }
        }
      }
    }
    return $ok;
}

function getTableNames($xml)
{
    $tables = array();
    foreach ($xml->table as $table) { //iterate tables
      array_push($tables, $table['name']);
    }
    return $tables;
}

$x = 0; //keeps track of ordered tables
$tables=getTableNames($xml);
$tables_order = array();
foreach ($tables as $table) {
    if (checkTableHaveReferences($xml, $table)==0) {
        array_push($tables_order, $table);
        $x=$x+1;
    } else {
    }
}

while ($x<count($tables)) { //don't stop unless all the tables have been ordered
  foreach ($tables as $table) {
      if (!in_array($table, $tables_order)) { //skip those that are already ordered
        if (checkReferencedAreOrdered($xml, $tables_order, $table)==0) {
            array_push($tables_order, $table);
            $x=$x+1;
        }
      }
  }
}

// <------------------------------------------------------------------------------------------------------->
function getDBName($xml)
{
    return $xml['name'];
}

//Getting Table Constraints
function getTableConstraints($xml, $tableName)
{
    $databaseName=getDBName($xml);
    $sqlQuery=null;
    foreach ($xml->table as $table) { //iterate tables
        if ($table['name']==$tableName) {
            $foreign_key=null;
            $primary_key=null;
            $nr_of_pk=0;
            $nr_of_fk=0;
            foreach ($table->columns->column as $column) {//iterate constraints
                if ($sqlQuery!=null) {
                    $sqlQuery=$sqlQuery.', ';
                } else {
                    $sqlQuery='CREATE TABLE IF NOT EXISTS '.$databaseName.'.'.$table['name'].'(';
                }
                $sqlQuery=$sqlQuery.$column['name'].' '.$column['column_type'];
                if ($column['is_nullable']=='NO') {
                    $sqlQuery=$sqlQuery.' NOT NULL';
                }
                if ($column['extra']=='auto_increment') {
                    $sqlQuery=$sqlQuery.' AUTO_INCREMENT';
                }
                if ($column['column_key']=='PRI') {
                    if ($nr_of_pk==0) {
                        $primary_key='PRIMARY KEY('.$column['name'];
                        $nr_of_pk=1;
                    } else { //first PK
                      $primary_key=$primary_key.','.$column['name'];
                    }
                }
                if ($column['referenced_table_name']!=null) {
                    if ($nr_of_fk==0) {
                        $foreign_key='FOREIGN KEY('.$column['name'].') REFERENCES '.$databaseName.'.'.$column['referenced_table_name'].'('.$column['referenced_column_name'].')';
                        $nr_of_fk=1;
                    } else {
                        $foreign_key=$foreign_key.','.'FOREIGN KEY('.$column['name'].') REFERENCES '.$databaseName.'.'.$column['referenced_table_name'].'('.$column['referenced_column_name'].')';
                    }
                }
            }
            if ($primary_key!=null) {
                $sqlQuery=$sqlQuery.', '.$primary_key.')';
            }
            if ($foreign_key!=null) {
                $sqlQuery=$sqlQuery.', '.$foreign_key;
            }
            $sqlQuery=$sqlQuery.')';
        }
    }
    return $sqlQuery;
}

//Get Records
function getRecords($xml, $tableName)
{
    $databaseName=getDBName($xml);
    $sqlQuery=null;
    $records_exist=0;
    foreach ($xml->table as $table) { //iterate tables
        if ($table['name']==$tableName) {
            $columnNames=array();
            $columnTypes=array();
            foreach ($table->columns->column as $column) {
                array_push($columnNames, $column['name']);
                array_push($columnTypes, $column['column_type']);
            }

            $sqlQuery='INSERT INTO '.$databaseName.'.'.$table['name']. ' VALUES ';
            $ok2=0; //to keep track when to add comma between records
            foreach ($table->records->record as $record) {//iterate constraints
                if ($ok2==1) {
                    $sqlQuery=$sqlQuery.',';
                }
                $sqlQuery=$sqlQuery.'(';
                $ok=0; //to keep track when to add comma between elements in a record
                foreach ($columnNames as $key=>$column) {
                    $records_exist=1; //to keep track if the table doesn't have any records
                    $value=$record[0]->$column;
                    $value=str_replace('"', '\"', $value); //escape any quatations marks
                    $datatype=$columnTypes[$key];
                    if ($value=='') {
                        $value="null";
                    }

                    if ($ok==0) { //first element in record
                        if (strpos($datatype, "varchar") !== false || $datatype=="timestamp" || $datatype=="longtext") { //if element is varchar/date/timestamp/text add brackets
                            $sqlQuery=$sqlQuery.'"'.$value.'"';
                        } else {
                            $sqlQuery=$sqlQuery.''.$value;
                        }
                        $ok=1;
                    } else { //not first element in record => add comma before
                        if (strpos($datatype, "varchar") !== false || $datatype=="timestamp" || $datatype=="longtext") {
                            $sqlQuery=$sqlQuery.',"'.$value.'"';
                        } else {
                            $sqlQuery=$sqlQuery.','.$value;
                        }
                    }
                    $ok2=1;
                }
                $sqlQuery=$sqlQuery.')';
            }
            //echo $sqlQuery.'<br/><br/>';
        }
    }
    if ($records_exist==0) {
        $sqlQuery=0;
    }
    return $sqlQuery;
}

function createTable($xml, $tables_order)
{
    $sqlQueryArray=array();
    foreach ($tables_order as $value) {
        $result = getTableConstraints($xml, $value);
        array_push($sqlQueryArray, $result);
    }
    return $sqlQueryArray;
}

function createRecords($xml, $tables_order)
{
    $sqlQueryArray2=array();
    foreach ($tables_order as $value) {
        $result = getRecords($xml, $value);
        if ($result!='0') {
            array_push($sqlQueryArray2, $result);
        }
    }
    return $sqlQueryArray2;
}


require '../database.php';
$pdo = Database::connect_fordrop();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$name=getDBName($xml);
$storageEngine = "SET default_storage_engine = INNODB";
$dropDatabase = "DROP DATABASE IF EXISTS $name";
$createDatabase = "CREATE DATABASE IF NOT EXISTS $name";

$creatingDatabase = [ //make sure you create in the right order! foreign keys must refer to a primary key in an existing table
    $storageEngine,
    $dropDatabase, //uncomment this if there is a wrong format in any table
    $createDatabase,
];

//Creating Tables
$sqlQueryArray=createTable($xml, $tables_order);
foreach ($sqlQueryArray as $value) {
    array_push($creatingDatabase, $value);
}

//Populating Tables
$sqlQueryArray2=createRecords($xml, $tables_order);
foreach ($sqlQueryArray2 as $value) {
    //echo $value.'<br/><br/>';
    array_push($creatingDatabase, $value);
}

foreach ($creatingDatabase as $sqlquery) {
    echo nl2br("\n"); //Line break in HTML conversion
  echo "<b>Executing SQL statement: </b>";
    echo $sqlquery; //Dispay statement being executed
  echo nl2br("\n");
    $q= $pdo->prepare($sqlquery);
    if ($q->execute() === true) {
        echo "<b><font color='green'>SQL statement performed correctly</b></font>";
    } else {
        echo "<b><font color='red'>Error executing statement: </b></font>" . $pdo->error;
    }
}
Database::disconnect();
