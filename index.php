<!DOCTYPE html>
<html lang="ru" id="App_interface">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Abyssinica+SIL&display=swap" rel="stylesheet">
    <title> Саркисова Варвара лаб 9 </title>
    <script src="UpdateScript.js"> </script>
</head>
<body>
<header class="header" id="top">
    <div class="wrapper">
      <div class="header__wrapper">
        <nav class="nav">
          <ul class="menu">
            <li class="menu__item"><a href="">Саркисова Варя 211-362</a></li>
            <li class="menu__item"><a href="">Лаб 9</a></li>
          </ul>
        </nav>
      </div>
    </div>
</header>
<main>
    <div class="tables">
    <?php
    include "dbconnection.php";
    include "deviceCondition.php";
?>
</div>

</main>
<footer id = "footer" class="footer">
      
</footer>
</body>
</html>
