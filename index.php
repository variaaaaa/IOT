<?php

//--------------------------Настройки подключения к БД-----------------------
$db_host = 'std-mysql';
$db_user = 'std_1920_internet'; //имя пользователя совпадает с именем БД
$db_password = 'passwordpassword'; //пароль, указанный при создании БД
$database = 'std_1920_internet'; //имя БД, которое было указано при создании
$link = mysqli_connect($db_host, $db_user, $db_password, $database);
if ($link == False) {  
    die("Cannot connect DB");
}

//----------------------------------------------------------------------------------------
$id = 1;

//-----------------Получаем из БД все данные об устройстве-------------------
$query = "SELECT * FROM DEVICE_TABLE WHERE DEVICE_ID = '$id'";
$result = mysqli_query($link, $query);
if (mysqli_num_rows($result) == 1) { //Если в БД есть данные о имени для этого устройства
    $Arr = mysqli_fetch_array($result);
    $device_name = $Arr['NAME'];
} else { //Если в БД нет данных о имени для этого устройства
    $device_name = '?';
}

$query = "SELECT * FROM TEMPERATURE_TABLE WHERE DEVICE_ID = '$id'";
$result = mysqli_query($link, $query);
if (mysqli_num_rows($result) == 1) { //Если в БД есть данные о температуре для этого устройства
    $Arr = mysqli_fetch_array($result);
    $temperature = $Arr['TEMPERATURE'];
    $temperature_dt = $Arr['DATE_TIME'];
} else { //Если в БД нет данных о температуре для этого устройства
    $temperature = '?';
    $temperature_dt = '?';
}

$query = "SELECT * FROM OUT_STATE_TABLE WHERE DEVICE_ID = '1'";
$result = mysqli_query($link, $query);
if (mysqli_num_rows($result) == 1) { //Если в БД есть данные о реле для этого устройства
    $Arr = mysqli_fetch_array($result);
    $out_state = $Arr['OUT_STATE'];
    $out_state_dt = $Arr['DATE_TIME'];
} else { //Если в БД нет данных о реле для этого устройства
    $out_state = '?';
    $out_state_dt = '?';
}
//----------------------------------------------------------------------------------------

//------Проверяем данные, полученные от пользователя---------------------

if (isset($_POST['button_on'])) {
    $date_today = date("Y-m-d H:i:s");
    $query = "UPDATE COMMAND_TABLE SET COMMAND='1', DATE_TIME='$date_today' WHERE DEVICE_ID = '$id'";
    $result = mysqli_query($link, $query);
    if (mysqli_affected_rows($link) != 1) //Если не смогли обновить - значит в таблице просто нет данных о команде для этого устройства
    { //вставляем в таблицу строчку с данными о команде для устройства
        $query = "INSERT COMMAND_TABLE SET DEVICE_ID='$id', COMMAND='1', DATE_TIME='$date_today'";
        $result = mysqli_query($link, $query);
    }
}

if (isset($_POST['button_off'])) {
    $date_today = date("Y-m-d H:i:s");
    $query = "UPDATE COMMAND_TABLE SET COMMAND='0', DATE_TIME='$date_today' WHERE DEVICE_ID = '$id'";
    $result = mysqli_query($link, $query);
    if (mysqli_affected_rows($link) != 1) //Если не смогли обновить - значит в таблице просто нет данных о команде для этого устройства
    { //вставляем в таблицу строчку с данными о команде для устройства
        $query = "INSERT COMMAND_TABLE SET DEVICE_ID='$id', COMMAND='0', DATE_TIME='$date_today'";
        $result = mysqli_query($link, $query);
    }
}
//-----------------------------------------------------------------------

//-------Формируем интерфейс приложения для браузера---------------------
echo '
<!DOCTYPE HTML>
<html id="App_interface">
<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>MyApp</title>
<script src="UpdateScript.js"> </script>
</head>
<body>
<table>
<tr>
<td width=100px> Устройство:
</td>
<td width=40px>' . $device_name . '
</td>
</tr>
</table>

<table border=1>
<tr>
<td width=100px> Tемпература
</td>
<td width=40px>' . $temperature . '
</td>
<td width=150px>' . $temperature_dt . '
</td>
</tr>
<tr>
<td width=100px> Реле
</td>
<td width=40px>' . $out_state . '
</td>
<td width=150px> ' . $out_state_dt . '
</td>
</tr>
</table>

<form>
<button formmethod=POST name=button_on value=1>Включить реле</button>
</form>
<form>
<button formmethod=POST name=button_off value=1>Выключить реле</button>
</form>

</body>
</html>';
//----------------------------------------------------------------------
