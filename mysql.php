<?php

if (!defined('ACCESS_ALLOWED')) {
    die('Direct access not allowed.');
}

class Database
{
    private $connection;

    public function __construct()
    {
        if (!function_exists('mysqli_init') && !extension_loaded('mysqli')) {
            die("WE DONT HAVE MYSQL!!!");
        }

        $config = include('config.php');
        $dbConfig = $config['db'];

        $this->connection = new mysqli(
            $dbConfig['host'],
            $dbConfig['username'],
            $dbConfig['password'],
            $dbConfig['database']
        );

        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }

        $this->connection->set_charset($dbConfig['charset']);
    }

    public function query($sql, $params = [], $isInsert = false)
    {
        $stmt = $this->connection->prepare($sql);
        if ($stmt === false) {
            die("Prepare failed: " . $this->connection->error);
        }
    
        if (!empty($params)) {
            $types = '';
            foreach ($params as $param) {
                if (is_int($param)) {
                    $types .= 'i';
                } elseif (is_double($param)) {
                    $types .= 'd';
                } else {
                    $types .= 's';
                }
            }
    
            $stmt->bind_param($types, ...$params);
        }
    
        $stmt->execute();
    
        if ($isInsert) {
            if ($stmt->affected_rows > 0) {
                $lastInsertId = $this->connection->insert_id;
                $stmt->close();
                return $lastInsertId;
            } else {
                $stmt->close();
                return false;
            }
        } else {
            $result = $stmt->get_result();
    
            if ($result) {
                $stmt->close();
                return $result;
            }
    
            if ($stmt->affected_rows > 0) {
                $affectedRows = $stmt->affected_rows;
                $stmt->close();
                return $affectedRows;
            }
            $stmt->close();
            return false;
        }
    }    

    public function close()
    {
        $this->connection->close();
    }
}

?>
