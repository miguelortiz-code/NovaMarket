<?php

require_once "connection.php";

class PostModel
{
    /*=============================================
	Peticion para tomar los nombres de las columnas
	=============================================*/
    static public function getColumnsData($table, $database)
    {
        return Connection::connect()
            ->query("SELECT COLUMN_NAME AS item FROM information_schema.columns WHERE table_schema = '$database' AND table_name = '$table'")
            ->fetchAll(PDO::FETCH_OBJ);
    }

    /*=============================================
	Peticion POST para crear datos de forma dinámica
	=============================================*/
    static public function postData($table, $data)
    {
        $columns = "(";
        $params = "(";

        foreach ($data as $key => $value) {
            $columns .= $key . ",";
            $params .= ":" . $key . ",";
        }

        $columns = substr($columns, 0, -1);
        $params = substr($params, 0, -1);

        $columns .= ")";
        $params .= ")";

        $stmt = Connection::connect()->prepare("INSERT INTO $table $columns VALUES $params");

        foreach ($data as $key => $value) {
            $stmt->bindParam(":" . $key, $data[$key], PDO::PARAM_STR);
        }

        if ($stmt->execute()) {
            return "The process was successful";
        } else {
            return Connection::connect()->errorInfo();
        }
    }
}
