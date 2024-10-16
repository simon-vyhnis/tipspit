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
    ?>
    <div class="container">
        <br>
        <h1><i class="vlogo">v</i> Tipšpit administrace</h1>
        <br>
        <h3>Výsledky tipování</h3>
        <table class="table">
            <thead>
                <th>Jméno</th>
                <th>Vstupní kód</th>
                <th>Výsledné body</th>
            </thead>
            <tbody>
                <?php
                    $results = [];
                    $accounts = $dao->get_accounts();
                    $points = 0;
                    include "evaluator.php";
                    foreach($accounts as $account)
                    {
                        $games = $dao->get_games($account["entry_code"]);
                        foreach($games as $game)
                        {
                            if($game["state"] == 2)
                            {
                                $points = $points + count_points($game["result_a"], $game["result_b"], $game["goals_a"], $game["goals_b"]);
                            }
                        }
                        array_push($results, new Result($account["name"], $account["entry_code"], $points));
                    }

                    usort($results, function($a, $b) {
                        return $a->points <=> $b->points; // Spaceship operator for comparison
                    });

                    foreach($results as $result)
                    {
                        echo "<tr>";
                        echo "<td>".$result->name."</td>";
                        echo "<td>".$result->entry_code."</td>";
                        echo "<td>".$result->points."</td>";
                        echo "</tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>