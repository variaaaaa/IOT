<?php
//Настройки подключения к БД
$db_host = 'std-mysql.ist.mospolytech.ru';
$db_user = 'std_1920_internet'; //имя пользователя совпадает с именем БД
$db_password = 'passwordpassword'; //пароль, указаный при создании БД
$database = 'std_1920_internet'; //имя БД, которое было указано при создании
$conn = mysqli_connect($db_host, $db_user, $db_password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "";

if(isset($_GET["ID"])){ //Если запрос от устройства содержит идентификатор
$query = "SELECT * FROM DEVICE_TABLE WHERE DEVICE_ID='".$_GET['ID']."'";
$result = mysqli_query($conn, $query);
if(mysqli_num_rows($result) == 1){ //Если найдено устройство с таким ID в БД

if(isset($_GET['Rele'])) { //Если устройство передало новое состояние реле
//проверяем есть ли в БД предыдущее значение этого параметра
$query = "SELECT OUT_STATE FROM OUT_STATE_TABLE WHERE DEVICE_ID = '".$_GET['ID']."'";
$result = mysqli_query($conn, $query);
$date_today = date("Y-m-d H:i:s"); //текущее время
if(mysqli_num_rows($result) == 1){ //Если в таблице есть данные для этого устройства - обновляем
$query = "UPDATE OUT_STATE_TABLE SET OUT_STATE='".$_GET['Rele']."', DATE_TIME='$date_today' WHERE DEVICE_ID = '".$_GET['ID']."'";
$result = mysqli_query($conn, $query);
} else { //Если данных для такого устройства нет - добавляем
$query = "INSERT OUT_STATE_TABLE SET DEVICE_ID='".$_GET['ID']."', OUT_STATE='".$_GET['Rele']."', DATE_TIME='$date_today'"; //Записать данные
$result = mysqli_query($conn, $query);
}
}

if(isset($_GET['Term'])) { //Если устройство передало новое значение температуры
//проверяем есть ли в БД предыдущее значение этого параметра
$query = "SELECT TEMPERATURE FROM TEMPERATURE_TABLE WHERE DEVICE_ID='".$_GET['ID']."'";
$result = mysqli_query($conn, $query);
$date_today = date("Y-m-d H:i:s"); //текущее время
if(mysqli_num_rows($result) == 1){ //Если в таблице есть данные для этого устройства - обновляем
$query = "UPDATE TEMPERATURE_TABLE SET TEMPERATURE='".$_GET['Term']."', DATE_TIME='$date_today' WHERE DEVICE_ID = '".$_GET['ID']."'";
$result = mysqli_query($conn, $query);
} else { //Если данных для этого устройства нет - добавляем
$query = "INSERT TEMPERATURE_TABLE SET DEVICE_ID='".$_GET['ID']."', TEMPERATURE='".$_GET['Term']."', DATE_TIME='$date_today'"; //Записать данные
$result = mysqli_query($conn, $query);
}
}

//Достаём из БД текущую команду управления реле
$query = "SELECT COMMAND FROM COMMAND_TABLE WHERE DEVICE_ID = '".$_GET['ID']."'";
$result = mysqli_query($conn, $query);
if(mysqli_num_rows($result) == 1){ //Если в таблице есть данные для этого устройства
$Arr = mysqli_fetch_array($result);
$Command = $Arr['COMMAND'];
}

//Отвечаем на запрос текущей командой
if($Command != -1) //Есть данные для этого устройства
{
echo "COMMAND $Command EOC";
}
else
{
echo "COMMAND ? EOC";
}
}
}

?>