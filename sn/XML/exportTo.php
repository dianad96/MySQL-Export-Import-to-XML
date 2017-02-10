<?php
$host = 'localhost';
$user = 'root';
$pass = 'root';
$name = 'mydb';

require("../database.php");
function getConstraints($table, $columnName)
{
    $getConstraints = 'SELECT cols.TABLE_NAME AS INITIAL_TABLE_NAME, cols.COLUMN_NAME AS INITIAL_COLUMN_NAME, cols.ORDINAL_POSITION,
  cols.COLUMN_DEFAULT, cols.IS_NULLABLE, cols.DATA_TYPE,
  cols.CHARACTER_MAXIMUM_LENGTH, cols.CHARACTER_OCTET_LENGTH,
  cols.NUMERIC_PRECISION, cols.NUMERIC_SCALE,
  cols.COLUMN_TYPE, cols.COLUMN_KEY, cols.EXTRA,
  cols.COLUMN_COMMENT, refs.REFERENCED_TABLE_NAME, refs.REFERENCED_COLUMN_NAME,
  cRefs.UPDATE_RULE, cRefs.DELETE_RULE,
  links.TABLE_NAME, links.COLUMN_NAME,
  cLinks.UPDATE_RULE, cLinks.DELETE_RULE
  FROM INFORMATION_SCHEMA.`COLUMNS` as cols
  LEFT JOIN INFORMATION_SCHEMA.`KEY_COLUMN_USAGE` AS refs
  ON refs.TABLE_SCHEMA=cols.TABLE_SCHEMA
  AND refs.REFERENCED_TABLE_SCHEMA=cols.TABLE_SCHEMA
  AND refs.TABLE_NAME=cols.TABLE_NAME
  AND refs.COLUMN_NAME=cols.COLUMN_NAME
  LEFT JOIN INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS AS cRefs
  ON cRefs.CONSTRAINT_SCHEMA=cols.TABLE_SCHEMA
  AND cRefs.CONSTRAINT_NAME=refs.CONSTRAINT_NAME
  LEFT JOIN INFORMATION_SCHEMA.`KEY_COLUMN_USAGE` AS links
  ON links.TABLE_SCHEMA=cols.TABLE_SCHEMA
  AND links.REFERENCED_TABLE_SCHEMA=cols.TABLE_SCHEMA
  AND links.REFERENCED_TABLE_NAME=cols.TABLE_NAME
  AND links.REFERENCED_COLUMN_NAME=cols.COLUMN_NAME
  LEFT JOIN INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS AS cLinks
  ON cLinks.CONSTRAINT_SCHEMA=cols.TABLE_SCHEMA
  AND cLinks.CONSTRAINT_NAME=links.CONSTRAINT_NAME
  WHERE cols.TABLE_SCHEMA= \'mydb\'
  AND cols.TABLE_NAME= ?
  AND cols.COLUMN_NAME= ?;';

    $pdo = Database::connect();
    $q1 = $pdo->prepare($getConstraints);
    $q1->execute(array($table,$columnName));
    $constraints = null;
    foreach ($q1->fetchAll() as $row) {
        if ($columnName==$row['INITIAL_COLUMN_NAME']) {
            /*  echo 'TABLE_NAME: '.$row['INITIAL_TABLE_NAME'].'<br/>';
            echo 'COLUMN_NAME: '.$row['INITIAL_COLUMN_NAME'].'<br/>';
            echo 'TYPE: '.$row['DATA_TYPE'].'<br/>';
            echo 'REFERENCED_TABLE_NAME: '.$row['REFERENCED_TABLE_NAME'].'<br/>';
            echo 'REFERENCED_COLUMN_NAME: '.$row['REFERENCED_COLUMN_NAME'].'<br/>';
            echo '<br/><br/>'; */

            $constraints = array(
              0 => $row['IS_NULLABLE'],
              1 => $row['COLUMN_TYPE'],
              2 => $row['COLUMN_KEY'],
              3 => $row['EXTRA'],
              4 => $row['REFERENCED_TABLE_NAME'],
              5 => $row['REFERENCED_COLUMN_NAME'],
            );
        }
    }
    return $constraints;
}

//connect
$link = mysqli_connect($host, $user, $pass);
mysqli_select_db($link, $name);

//get all the tables
$query = 'SHOW TABLES FROM '.$name;
$result = mysqli_query($link, $query) or die('cannot show tables');
if (mysqli_num_rows($result)) {
    //prep output
    $tab = "\t";
    $br = "\n";
    $xml = '<?xml version="1.0" encoding="UTF-8"?>'.$br;
    $xml.= '<database name="'.$name.'">'.$br;

    //for every table...
    while ($table = mysqli_fetch_row($result)) {
        //prep table out
        $xml.= $tab.'<table name="'.$table[0].'">'.$br;
        echo 'TABLE NAME:'.$table[0].'<br/>';

        //get the rows
        $query3 = 'SELECT * FROM '.$table[0];
        $records = mysqli_query($link, $query3) or die('cannot select from table: '.$table[0]);

        //table attributes
        $attributes = array('name','orgname','max_length','length','charsetnr','flags','type','decimals');
        $xml.= $tab.$tab.'<columns>'.$br;
        $x = 0;
        while ($x < mysqli_num_fields($records)) {
            $meta = mysqli_fetch_field($records);
            $xml.= $tab.$tab.$tab.'<column ';

            /*
            echo 'name:'.$meta->name.'<br/>';
            echo 'type:'.$meta->type.'<br/>';
            echo 'flags:'.$meta->flags.'<br/>';
            if ($meta->flags & MYSQLI_PRI_KEY_FLAG) {
                echo 'PK ';
            }
            if ($meta->flags & MYSQLI_UNIQUE_KEY_FLAG) {
                echo 'UNIQUE KEY ';
            }
            if ($meta->flags & MYSQLI_PART_KEY_FLAG) {
                echo 'PART KEY ';
            }
            if ($meta->flags & MYSQLI_MULTIPLE_KEY_FLAG) {
                echo 'MULTIPLE KEY ';
            }
            echo '<br/>';
            */


            foreach ($attributes as $attribute) {
                if ($attribute=='name') {
                    $columnName = $meta->$attribute;
                    echo 'COLUMN NAME:'.$columnName.'<br/>';
                }
                $xml.= $attribute.'="'.$meta->$attribute.'" ';
            }
            $constraints = getConstraints($table[0], $columnName);
            if ($constraints) {
                echo 'IS_NULLABLE: '.$constraints[0].'<br/>';
                echo 'COLUMN_TYPE: '.$constraints[1].'<br/>';
                echo 'COLUMN_KEY: '.$constraints[2].'<br/>';
                echo 'EXTRA: '.$constraints[3].'<br/>';
                echo 'REFERENCED_TABLE_NAME: '.$constraints[4].'<br/>';
                echo 'REFERENCED_COLUMN_NAME: '.$constraints[5].'<br/>';

                $xml.= 'is_nullable="'.$constraints[0].'" ';
                $xml.= 'column_type="'.$constraints[1].'" ';
                if ($constraints[2]) {
                    $xml.= 'column_key="'.$constraints[2].'" ';
                }
                if ($constraints[3]) {
                    $xml.= 'extra="'.$constraints[3].'" ';
                }
                if ($constraints[4]) {
                    $xml.= 'referenced_table_name="'.$constraints[4].'" ';
                }
                if ($constraints[5]) {
                    $xml.= 'referenced_column_name="'.$constraints[5].'" ';
                }
            }

            $xml.= '/>'.$br;
            $x++;
        }
        echo '<br/><br/>';
        $xml.= $tab.$tab.'</columns>'.$br;

        //stick the records
        $xml.= $tab.$tab.'<records>'.$br;
        while ($record = mysqli_fetch_assoc($records)) {
            $xml.= $tab.$tab.$tab.'<record>'.$br;
            foreach ($record as $key=>$value) {
                $xml.= $tab.$tab.$tab.$tab.'<'.$key.'>'.htmlspecialchars(stripslashes($value)).'</'.$key.'>'.$br;
            }
            $xml.= $tab.$tab.$tab.'</record>'.$br;
        }
        $xml.= $tab.$tab.'</records>'.$br;
        $xml.= $tab.'</table>'.$br;
    }
    $xml.= '</database>';

    echo 'Imported Successfully! Check your XML folder.';

    //save file
    $handle = fopen($name.'-backup-'.time().'.xml', 'w+');
    fwrite($handle, $xml);
    fclose($handle);
}
