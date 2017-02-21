## How to
- change the `sn/database.php` and `XML/exportTo.php` to your credentials
- first create your database by running the `sql/resetTables.php` script
- `exportTo.php` To export the database to XML format run `/exportTo.php`. If successfully imported, check your XML folder for the backup file.
- `importTo.php` To import the XML file first change the path to point to your file `$path = 'mydb-backup-1486313758.xml';` and then run the script in your browser

## What to expect?
The XML will have the following format:
```xml
<database name="">
  <table name="">
    <columns></columns>
    <records></records>
  </table>
</database>
```
A column will have the following output:
```xml
<column name="email" orgname="email" max_length="0" length="50" charsetnr="8" flags="20489" type="253" decimals="0" is_nullable="NO" column_type="varchar(50)" column_key="MUL" referenced_table_name="users" referenced_column_name="email" />
```

## What does all this mean?
![alt text](http://s2.quickmeme.com/img/db/dbc97d3b537a3b38f323b2cd9e97228de9342018e72bb18e3b36ec235a8783f5.jpg)

`array mysqli_fetch_fields ( mysqli_result $result )` returns the following values:

| Property      | Description   |
| ------------- |:-------------|
| name          | The name of the column |
| orgname       | Original column name if an alias was specified |
| table         | The name of the table this field belongs to (if not calculated) |
| orgtable      | Original table name if an alias was specified |
| max_length    | The maximum width of the field for the result set. |
| length        | The width of the field, in bytes, as specified in the table definition. |
| charsetnr     | The character set number (id) for the field. |
| flags         | An integer representing the bit-flags for the field. |
| type          | The data type used for this field. |
| decimals      | The number of decimals used (for integer fields). |

`getConstraints($table, $columnName)` function returns the column constraints by interrogating the information_schema table and returns:

| Property                | Description   |
| ------------------------|:-------------|
| is_nullable             | returns YES/NO |
| column_type             | returns column type |
| column_key              | return PRI for PK and MUL for FK/PFK |
| extra                   | returns auto_icrement or null |
| referenced_table_name   | if column is FK it returns the table that it references |
| referenced_column_name  | if column is FK it returns the column that it references |


Flags
```
NOT_NULL_FLAG = 1                                                                              
PRI_KEY_FLAG = 2                                                                               
UNIQUE_KEY_FLAG = 4                                                                            
BLOB_FLAG = 16                                                                                 
UNSIGNED_FLAG = 32                                                                             
ZEROFILL_FLAG = 64                                                                             
BINARY_FLAG = 128                                                                              
ENUM_FLAG = 256                                                                                
AUTO_INCREMENT_FLAG = 512                                                                      
TIMESTAMP_FLAG = 1024                                                                          
SET_FLAG = 2048                                                                                
NUM_FLAG = 32768                                                                               
PART_KEY_FLAG = 16384                                                                          
GROUP_FLAG = 32768                                                                             
UNIQUE_FLAG = 65536
```

Every number posted above is a power of 2. (1 = 2^0, 2 = 2^1, 4 = 2^2 and so on). In other words, each of them corresponds to one bit in a number. To read what 49967 means, you can for example display it in binary form.
```
>> decbin(49967);
'1100001100101111'
```

Starting from right, you can now read that the field has following flags:
```
NOT_NULL
PRI_KEY  
UNIQUE_KEY
MULTIPLE_KEY
UNSIGNED
ENUM
AUTO_INCREMENT
GROUP
UNIQUE
```
(credits to [Mchl](http://stackoverflow.com/questions/11437650/what-do-bit-flags-in-mysqli-mean-using-fetch-field-direct))

## How does importTo.php works? (dealing with constraint issues)
Ideally, before creating tables with constrains, the user should specify the order in which the tables should be created so that we can prevent future constraint conflicts. But what happens if we want to import different databases? Should we manually add the array of ordered tables? We're too lazy to do that each time.
For the database we're using in this project, we have the following column constraints:

| Table Name             | Tables it references   |
| ---------------------- |:-------------|
| accessRights           | photoCollection/users/circleOfFriends |
| annotations            | photos/users |
| blogs                  | users |
| circleOfFriends         |  |
| comments               | photos/users |
| friendships            |  |
| messages               |  |
| photoCollection        |  |
| photos                 | photoCollection |
| posts                  | blogs |
| privacySettings        | users |
| rights                 | roles |
| roles                  |  |
| userCircleRelationship | users/circleOfFriends |
| users                  | roles |

The first elements added to the array of ordered tables are the ones that do not reference any tables (messages/roles etc). The algorithm will then search for tables that reference other tables already in the ordered array (since it means we can create those tables) and adds them to the ordered array. It will stop when all the tables are in the ordered array.

