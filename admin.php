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
        if(!isset($_SESSION["entry_code"]) || $_SESSION["entry_code"] != "florbalovybuh")
        {
            header("Location: index.php");
            die();
        }
        include "data/dao.php";
        $dao = new Dao();
        if(isset($_POST["close"]))
        {
            $dao->close_game($_POST["close"]);
        }
        if(isset($_POST["result"]) && isset($_POST["goals_a"]) && isset($_POST["goals_b"]))
        {
            $dao->add_result($_POST["result"], $_POST["goals_a"], $_POST["goals_b"]);
        }
    ?>
    <div class="container">
        <h1><i class="vlogo">v</i> Tipšpit administrace</h1>
        <h3>Zápasy</h3>
        <form method="post">
            <?php
                include "data/dao.php";
                $dao = new Dao();
                $games = $dao->get_games($_SESSION["entry_code"]);
                foreach($games as $game)
                {
                    //game card
                    echo '<div class="card"><div class="card-body">';
                    echo '<h5 class="card-title">'.$game["team_a"].' vs '.$game["team_b"].'</h5>';
                    //different part according to state
                    if($game["state"] == 0)
                    {
                        echo '<input type="hidden" id="close" name="close" value="'.$game["id"].'">';
                        echo '<button type="submit" class="btn btn-primary mb-3">Uzavřít tipovaní</button>';
                    }
                    else if($game["state"] == 1)
                    {
                        echo '<input type="hidden" id="result" name="result" value="'.$game["id"].'">';
                        echo '<p>Góly týmu '.$game["team_a"].': </p>';
                        echo '<input type="number" id="goals_a" name="goals_a" class="form-control">';
                        echo '<p>Góly týmu '.$game["team_b"].': </p>';
                        echo '<input type="number" id="goals_b" name="goals_b" class="form-control">';
                        echo '<button type="submit" class="btn btn-primary mb-3">Zapsat výsledek</button>';
                    }
                    else
                    {
                        echo '<h6 class="card-subtitle mb-2 text-body-secondary">Výsledek</h6>';
                    }

                    echo '</div></div>';
                }
            ?>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>