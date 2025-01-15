<?php
class DB {
    private $conn;

    public function connect() {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $this->conn = new mysqli("localhost", "root", "", "gym_helper");
        $this->conn->set_charset("utf8mb4");
        if ($this->conn->connect_error) {
            die($this->conn->connect_error);
        }
    }

    public function prepare($sql) {
        $this->connect();
        return $this->conn->prepare($sql);
    }

    public function send2db($sql, array $args = []) {
        try {
            $this->connect();
            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                throw new Exception('Error preparing statement: ' . $this->conn->error);
            }

            if (!empty($args)) {
                $types = '';
                $params = [];

                foreach ($args as $arg) {
                    if (is_float($arg)) {
                        $types .= 'd';
                    } elseif (is_integer($arg)) {
                        $types .= 'i';
                    } elseif (is_string($arg)) {
                        $types .= 's';
                    } else {
                        $types .= 'b';
                    }
                    $params[] = $arg;
                }

                $bind_names[] = $types;
                for ($i = 0; $i < count($params); $i++) {
                    $bind_name = 'bind' . $i;
                    $$bind_name = $params[$i];
                    $bind_names[] = &$$bind_name;
                }

                call_user_func_array([$stmt, 'bind_param'], $bind_names);
            }

            if (!$stmt->execute()) {
                throw new Exception('Error executing statement: ' . $stmt->error);
            }

            if (stripos($sql, 'select') !== false) {
                return $stmt->get_result();
            } elseif (stripos($sql, 'insert') !== false) {
                return $this->conn->insert_id; 
            } else {
                return $stmt->affected_rows;
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            exit('Error: ' . $e->getMessage());
        }
    }
}
?>