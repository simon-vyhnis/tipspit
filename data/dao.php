<?php
    include 'db_credentials.php';
    class Dao {
        private $pdo;
        public function __construct(){
            $dsn = 'mysql:dbname=' . SQL_DBNAME . ';host=' . SQL_HOST . '';
            $user = SQL_USERNAME;
            $password = SQL_PASSWORD;
            try {
                $this->pdo = new PDO($dsn, $user, $password);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die('Connection failed: ' . $e->getMessage());
            }
        }

        public function login($entry_code) : int {
            $get_code = $this->pdo->prepare("SELECT * FROM account WHERE entry_code = ?");
            $get_code->execute(array($entry_code));
            return count($get_code->fetchAll()) > 0;
        }
        
        public function get_games($account) : array {
            $get_games = $this->pdo->prepare("SELECT * FROM game LEFT OUTER JOIN result ON game.id = result.game_id AND account = ? ORDER BY state");
            $get_games->execute(array($account));
            return $get_games->fetchAll();
        }
        
         public function add_result($team, $stage, $value){
            $add_result_query = $this->pdo->prepare("INSERT INTO tap_result (stage_id, team, value) VALUES(?,?,?)");
            $add_result_query->execute(array($stage, $team, $value));
        }
        

    }
?>