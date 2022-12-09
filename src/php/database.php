<?php 

    class database
    {
        private $host;
        private $user;
        private $pass;
        private $dbname;
        private $status;
        private $conn;

        public function __construct()
        {
            $this->host = 'localhost';
            $this->user = 'root';
            $this->pass = '';
            $this->dbname = 'hiyas';
            $this->status = false;


            $this->conn = $this->init();
        }

        public function getConnection()
        {
            return $this->conn;
        }

        public function getStatus()
        {
            return $this->status;
        }

        public function closeConnection()
        {
            $this->conn = null;
        }

        private function init()
        {
            try {
                $conn = new PDO('mysql:host='.$this->host.';dbname='.$this->dbname,$this->user, $this->pass); 
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->status = true;

                return $conn;
            } catch (PDOException $e) {
                return "Connection Failure: " . $e->getMessage();
            }
        }
    }

?>