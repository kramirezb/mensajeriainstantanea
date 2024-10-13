<?php

if(!class_exists('DatabaseConexion'))
{
    class DatabaseConnection{
        const HOST = "localhost";
        const DATABASE = "mensajeria_instantanea";
        const USERNAME = "root";
        const PASSWORD = "";

        private $conn;

        public function __construct()
        {
            $this->connect();
        }

        public function connect(){
            try{
                $dsn = "mysql:host=" . self::HOST . ";dbname=" . self::DATABASE;     
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ];

               $this->conn = new PDO(
                    $dsn,
                    self::USERNAME,
                    self::PASSWORD,
                    $options
                );
            }
            catch(PDOException $ex){
                die("Falló la conexión: ". $ex->getMessage());
            }
        }

        public function getConnection(){
            return $this->conn;
        }

        // Método para insertar datos
        public function insert($table, $data) {

            $columns = implode(", ", array_keys($data));

            $placeholders = ":" . implode(", :", array_keys($data));
            $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
            
            try {
                $stmt = $this->conn->prepare($sql);
                $stmt->execute($data);

                return $this->conn->lastInsertId();

            } catch (PDOException $ex) {
                die("Error al insertar: " . $ex->getMessage());
            }
        }
        /*
            $data = [
                'nombre' => 'Juan',
                'edad' => 30
            ];
            $id = $db->insert('usuarios', $data);

        */


        // Método para obtener datos
        public function get($table, $values = null, $conditions = []) {
            if(empty($values))
            {
                $values = " * ";
            }
            
            $sql = "SELECT $values FROM $table";
            
            if (!empty($conditions)) {
                $sql .= " WHERE " . implode(" AND ", array_map(function($key) {
                    return "$key = :$key";
                }, array_keys($conditions)));
            }

            try {
                $stmt = $this->conn->prepare($sql);
                $stmt->execute($conditions);

                return $stmt->fetchAll();
            } catch (PDOException $e) {
                die("Error al obtener datos: " . $e->getMessage());
            }
        }
        /*
        $usuarios = $db->get('usuarios', ['id' => 30]);
        $usuarios = $db->get('usuarios', 'id, nombre', null);
        print_r($usuarios);

        */

        public function getWithQuery($query){
            try {
                $stmt = $this->conn->prepare($query);
                $stmt->execute();

                return $stmt->fetchAll();
            } catch (PDOException $e) {
                die("Error al obtener datos: " . $e->getMessage());
            }
        }

        // Método para actualizar datos
        public function update($table, $data, $conditions) {

            $set = implode(", ", array_map(function($key) {
                return "$key = :$key";
            }, array_keys($data)));

            $where = implode(" AND ", array_map(function($key) {
                return "$key = :$key";
            }, array_keys($conditions)));

            $sql = "UPDATE $table SET $set WHERE $where";

            try {
                $stmt = $this->conn->prepare($sql);
                $stmt->execute(array_merge($data, $conditions));
                return $stmt->rowCount();
            } catch (PDOException $e) {
                die("Error al actualizar: " . $e->getMessage());
            }
        }
        /*
        $actualizar = [
            'nombre' => 'Pedro'
        ];
        $condiciones = [
            'id' => 1
        ];
        $filas_actualizadas = $db->update('usuarios', $actualizar, $condiciones);

        */

        // Método para eliminar datos
        public function delete($table, $conditions) {
            $where = implode(" AND ", array_map(function($key) {
                return "$key = :$key";
            }, array_keys($conditions)));

            $sql = "DELETE FROM $table WHERE $where";

            try {
                $stmt = $this->conn->prepare($sql);
                $stmt->execute($conditions);
                return $stmt->rowCount();
            } catch (PDOException $e) {
                die("Error al eliminar: " . $e->getMessage());
            }
        }
        /*
        $condiciones = ['id' => 1];
        $filas_eliminadas = $db->delete('usuarios', $condiciones);
        */

    }    
}

?>