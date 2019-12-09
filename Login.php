<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <!-- Die 3 Meta-Tags oben *müssen* zuerst im head stehen; jeglicher sonstiger head-Inhalt muss *nach* diesen Tags kommen -->
        <title>Chateingang</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
              integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <!-- Eigene Styles -->
        <link rel="stylesheet" href="css/bootstrap-custom.css">
        <link rel="stylesheet" type="text/css" href="cssas.css">
        <!-- ACHTUNG: IE < 9 unterstützen wir nicht mehr! -->
    </head>
    <body style="background-color: #00cccc">

                <div class="container well" style="margin-top: 10%;">
                    <center><h1>Login</h1></center>
                    <?php
                    if (isset($_GET["pwd"])) {
                        file_put_contents("Account.txt", "");
                        $myfile = fopen("Account.txt", "w") or die("Unable to open file!");
                        $txt = password_hash($_GET["pwd"],PASSWORD_DEFAULT );
                        fwrite($myfile, $txt);
                        fclose($myfile);
                    }

                    ?>

                    <form action="Bildergalerie.php">
                    <div class="form-group">
                        <label for="pwd">Password:</label>
                        <input type="password" class="form-control" id="pwd" name="pwd">
                    </div>
                        <button type="submit" class="btn">Login</button>
                    </form>
                    <form action="Register.php">
                    <button type="submit" class="btn">Set Passwort</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
    <!-- jQuery (wird für Bootstrap JavaScript-Plugins benötigt) -->
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"
    integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>       
    <!-- Binde alle kompilierten Plugins zusammen ein (wie hier unten) oder such dir einzelne Dateien nach Bedarf aus -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
    integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>      
    <!-- Validator -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.5/validator.min.js" 
    integrity="sha256-IxYUmOOk74FUrcx5FEMOHVmTJDb7ZAwnC/ivo/OQGxg=" crossorigin="anonymous"></script>
    <!-- Eigene JavaScripts -->
</html>


