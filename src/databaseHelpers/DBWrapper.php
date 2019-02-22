<?php

namespace Forum\databaseHelpers;
use Forum\Models\BaseModel;
use PDO;


class DBWrapper
{
    private $tableName;
    private $conn;

    public function __construct(string $tableName)
    {
        $this->tableName = $tableName;
        try{
            $this->conn = new PDO(sprintf("mysql:host=%s;dbname=%s", DBConnectionInfo::$serverName, DBConnectionInfo::$dbName),
                                  DBConnectionInfo::$userName, DBConnectionInfo::$password);
        } catch (\Exception $e){
            die("Couldn't connect to DB");
        }
    }

    public function selectAll(string $orderBy = null, bool $descending = false) : array
    {
        $command = "SELECT * FROM $this->tableName";
        if ($orderBy){
            $command .= " ORDER BY $orderBy";
            if ($descending) $command .= " DESC";
        }
        $result = $this->conn->query($command);

        return $result->fetchAll();
    }

    public function selectOne(string $fieldName, $fieldValue){

        $statement = $this->execCommand("SELECT * FROM $this->tableName WHERE $fieldName = ?", [$fieldValue]);
        return $statement->fetch();
    }

    public function selectCount(string $fieldName){
        $statement = $this->execCommand("SELECT $fieldName, COUNT(*) AS 'count' FROM $this->tableName GROUP BY $fieldName", []);
        return $statement->fetchAll();
    }

    public function update(BaseModel $model){
        $variableSets = [];
        $variableValues = [];

        $modelInfo = $model->getValues();

        foreach ($modelInfo as $columnName => $columnValue){
            if ($columnName != "id" && $columnValue != null){
                array_push($variableSets, $columnName . " = ?");
                array_push($variableValues, $columnValue);
            }

        }

        $command = sprintf("UPDATE $this->tableName SET %s WHERE id = '$model->id'", implode(", ", $variableSets));

        $this->execCommand($command, $variableValues);
    }


    public function insert(BaseModel $model) : int{
        $columnNames = [];
        $columnValues = [];
        $escapeHelpers = [];

        $modelInfo = $model->getValues();
        unset($modelInfo["id"]);

        foreach ($modelInfo as $columnName => $columnValue){
            array_push($columnNames, $columnName);
            array_push($escapeHelpers, "?");
            array_push($columnValues, $columnValue);
        }

        $command = sprintf("INSERT INTO $this->tableName (%s) VALUES (%s)", implode(", ", $columnNames), implode(", ", $escapeHelpers));

        $this->execCommand($command, $columnValues);

        return $this->conn->lastInsertId();
    }

    private function execCommand(string $command, array $args) : \PDOStatement{
        $statement = $this->conn->prepare($command);
        $statement->execute($args);
        return $statement;
    }

}
