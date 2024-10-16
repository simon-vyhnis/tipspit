<?php
    include 'db_credentials.php';
    class Dao {
        private $pdo;
        public function __construct()
        {
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

        public function login($entry_code) : int 
        {
            $get_code = $this->pdo->prepare("SELECT * FROM account WHERE entry_code = ?");
            $get_code->execute(array($entry_code));
            return count($get_code->fetchAll()) > 0;
        }
        
        public function get_games($account) : array 
        {
            $get_games = $this->pdo->prepare("SELECT * FROM game LEFT OUTER JOIN result ON game.game_id = result.game AND account = ? ORDER BY state");
            $get_games->execute(array($account));
            return $get_games->fetchAll();
        }
        
        public function add_game($team_a, $team_b)
        {
            $add_result_query = $this->pdo->prepare("INSERT INTO game (team_a, team_b) VALUES(?,?)");
            $add_result_query->execute(array($team_a, $team_b));
        }

        public function close_game($game)
        {
            echo '<script>console.log("closing game'.$game.'")</script>';
            $add_result_query = $this->pdo->prepare("UPDATE game SET state=1 WHERE game_id = ?");
            $add_result_query->execute(array($game));
        }
        
        public function add_result($game, $goals_a, $goals_b)
        {
            $add_result_query = $this->pdo->prepare("UPDATE game SET result_a = ?, result_b = ?, state=2 WHERE game_id = ?");
            $add_result_query->execute(array($goals_a, $goals_b, $game));
        }

        public function exists_tip($game, $account) : bool
        {
            $get_tip = $this->pdo->prepare("SELECT * FROM result WHERE game=? AND account = ?");
            $get_tip->execute(array($game, $account));
            return count($get_tip->fetchAll()) > 0;
        }

        public function add_tip($game, $account, $goals_a, $goals_b)
        {
            if(!$this->exists_tip($game, $account))
            {
                $add_result_query = $this->pdo->prepare("INSERT INTO result (game, account, goals_a, goals_b) VALUES (?,?,?,?)");
                $add_result_query->execute(array($game, $account, $goals_a, $goals_b));
            }
        }
    }
?>