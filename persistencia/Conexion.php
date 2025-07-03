<?php

class Conexion{
    private $mysqlConexion;
    private $resultado;
    public function abrirConexion(){
        $this->mysqlConexion = new mysqli("localhost", "root", "", "ecogestordb",3307);
        if ($this->mysqlConexion->connect_error) {
            // Log the error or handle it more gracefully in a real application
            die("Connection failed: " . $this->mysqlConexion->connect_error);
        }
    }
    
    /**
     * For simple queries without parameters. USE WITH CAUTION.
     * Prefer prepared statements for any query involving external data.
     */
    public function ejecutarConsultaDirecta($sentenciaSQL){
        $resultado = $this->mysqlConexion->query($sentenciaSQL);
        if (!$resultado) {
            // Log error: $this->mysqlConexion->error
            error_log("Query failed: " . $this->mysqlConexion->error . " SQL: " . $sentenciaSQL);
            return false;
        }
        return $resultado; // Returns mysqli_result object or true/false for DML
    }

    public function ejecutarConsulta($sentenciaSQL){
        $this -> resultado = $this -> mysqlConexion -> query($sentenciaSQL);
    }
    /**
     * Prepares an SQL statement for execution.
     * @param string $sentenciaSQL The SQL query with placeholders (?).
     * @return mysqli_stmt|false The statement object or false on error.
     */
    public function prepararConsulta($sentenciaSQL){
        $stmt = $this->mysqlConexion->prepare($sentenciaSQL);
        if (!$stmt) {
            // Log error: $this->mysqlConexion->error
            error_log("Prepare failed: (" . $this->mysqlConexion->errno . ") " . $this->mysqlConexion->error . " SQL: " . $sentenciaSQL);
            return false; // Or throw an exception
        }
        return $stmt;
    }
    public function numeroFilas(){
        return $this -> resultado -> num_rows;
    }
    public function obtenerLlaveAutonumerica(){
        return $this->mysqlConexion->insert_id;
    }
    public function siguienteRegistro(){
        return $this -> resultado -> fetch_row();
    }
    public function cerrarConexion(){
        if ($this->mysqlConexion) {
            $this->mysqlConexion->close();
        }
    }
}

?>