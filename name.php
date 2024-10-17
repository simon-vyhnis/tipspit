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
    <div class="container">
        <br>
        <h1><i class="vlogo">v</i> Tipšpit</h1>
        <br>
        <p>Než začneš tipovat, přidej prosím svoje jméno.</p>
        <?php
            session_start();
            if(isset($_SESSION["entry_code"]))
            {
                include "data/dao.php";
                $dao = new Dao();
                $name = $dao->get_name($_SESSION["entry_code"]);
                if($name != null && $name != "")
                {
                    $_SESSION["name"] = $name;
                    header("Location: home.php");
                    die();
                }
                if(isset($_POST["name"]) && $_POST["name"] != "")
                {
                   $dao->add_name($entry_code, $name);
                }
            }
            else
            {
                header("Location: index.php");
                die();
            }
        ?>
        <form method="post">
            <div class="mb-3">
                <label for="entry_code" class="form-label">Jméno</label>
                <input type="text" class="form-control" id="entry_code" name="entry_code" placeholder="AAA0000">
            </div>
            <button type="submit" class="btn btn-primary">Začít tipovat!</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>