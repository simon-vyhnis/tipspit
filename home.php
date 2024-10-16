<!DOCTYPE html>
<html lang="cz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tipšpit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="tipspit.css">
</head>
<body>
    <?php
        session_start();
        if(!isset($_SESSION["entry_code"]))
        {
            header("Location: index.php");
            die();
        }
        if(isset($_POST["logout"]))
        {
            $_SESSION["entry_code"] = null;
            header("Location: index.php");
            die();
        }
        include "data/dao.php";
        $dao = new Dao();

        if(isset($_POST["result"]) && isset($_POST["goals_a"]) && $_POST["goals_a"] != "" 
            && isset($_POST["goals_b"]) && $_POST["goals_b"] != "")
        {
            echo '<script>console.log("adding tip");</script>';
            $dao->add_tip($_POST["result"], $_SESSION["entry_code"], $_POST["goals_a"], $_POST["goals_b"]);
        }

        include "evaluator.php";
    ?>
    <div class="container">
        <br>
        <h1><i class="vlogo">v</i> Tipšpit</h1>
        <br>
        <h3>Zápasy</h3>
            <?php
                $games = $dao->get_games($_SESSION["entry_code"]);
                $account_points = 0;  
                foreach($games as $game)
                {
                    //game card
                    echo '<div class="card mb-3"><div class="card-body">';
                    echo '<h5 class="card-title">'.$game["team_a"].' vs '.$game["team_b"].'</h5>';
                    //different part according to state
                    if($game["state"] == 0 && $game["goals_a"] == null)
                    {
                        echo '<form method="post">';
                        echo '<input type="hidden" id="result" name="result" value="'.$game["game_id"].'">';
                        echo '<div class="mb-3">';
                        echo '<p>Góly týmu '.$game["team_a"].': </p>';
                        echo '<input type="number" id="goals_a" name="goals_a" class="form-control">';
                        echo '</div>';
                        echo '<div class="mb-3">';
                        echo '<p>Góly týmu '.$game["team_b"].': </p>';
                        echo '<input type="number" id="goals_b" name="goals_b" class="form-control">';
                        echo '</div>';
                        echo '<button type="submit" class="btn btn-primary mb-3">Tipnout</button>';
                        echo '</form>';
                    }
                    else if($game["state"] == 1 && $game["goals_a"] == null)
                    {
                        echo '<h6 class="card-subtitle mb-2 text-body-secondary">Tipování skončilo.</h6>';
                    }
                    else if($game["state"] < 2 && $game["goals_a"] != null)
                    {
                        echo '<h6 class="card-subtitle mb-2 text-body-secondary">Můj tip</h6>';
                        echo '<h6 class="card-subtitle mb-2 text-body-secondary">'.$game["goals_a"].":".$game["goals_b"].'</h6>';
                    }
                    else
                    {
                        echo '<div class="row">';
                        echo '<div class="col">';
                        echo '<h6 class="card-subtitle mb-2 text-body-secondary">Můj tip</h6>';
                        if($game["goals_a"] != null)
                        {
                            echo '<h6 class="card-subtitle mb-2 text-body-secondary">'.$game["goals_a"].":".$game["goals_b"].'</h6>';
                        }
                        else
                        {
                            echo '<h6 class="card-subtitle mb-2 text-body-secondary">Netipováno</h6>';
                        }
                        echo '</div>';
                        echo '<div class="col">';
                        echo '<h6 class="card-subtitle mb-2 text-body-secondary">Výsledek</h6>';
                        echo '<h6 class="card-subtitle mb-2 text-body-secondary">'.$game["result_a"].":".$game["result_b"].'</h6>';
                        echo '</div>';
                        if($game["goals_a"] != null)
                        {
                            echo '<div class="col">';
                            echo '<h6 class="card-subtitle mb-2 text-body-secondary">Body za tip</h6>';
                            $points = count_points($game["result_a"], $game["result_b"], $game["goals_a"], $game["goals_b"]);
                            $account_points = $account_points + $points;
                            echo '<h6 class="card-subtitle mb-2 text-body-secondary">'. $points .'</h6>';
                            echo '</div>';
                        }
                        echo '</div>';
                    }

                    echo '</div></div>';
                }
            ?>
        <h3>Účet</h3>
        <div class="card mb-3">
            <div class="card-body">
                <?php
                    echo '<p>Vstupní kód: '.$_SESSION["entry_code"].'</p>';
                    echo '<p>Jméno: '.$_SESSION["entry_code"].'</p>';
                    echo '<p>Celkem bodů: '.$account_points.'</p>';
                ?>
                <br>
                <form method="post">
                    <input type="hidden" id="logout" name="logout" value="true">
                    <button class="btn btn-primary mb-3">Odhlásit se</button>
                <form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>