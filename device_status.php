<?php


if(isset($_GET["ID"])){ //Если запрос от устройства содержит идентификатор
$query = "SELECT * FROM device_table WHERE device_id='".$_GET['ID']."'";
$result = mysqli_query($conn, $query);
if(mysqli_num_rows($result) == 1){ //Если найдено устройство с таким ID в БД

if(isset($_GET['Rele'])) { //Если устройство передало новое состояние реле
//проверяем есть ли в БД предыдущее значение этого параметра
$query = "SELECT out_state FROM out_state_table WHERE device_id = '".$_GET['ID']."'";
$result = mysqli_query($conn, $query);
$date_today = date("Y-m-d H:i:s"); //текущее время
if(mysqli_num_rows($result) == 1){ //Если в таблице есть данные для этого устройства - обновляем
$query = "UPDATE out_state_table SET out_state='".$_GET['Rele']."', date_time='$date_today' WHERE device_id = '".$_GET['ID']."'";
$result = mysqli_query($conn, $query);
} else { //Если данных для такого устройства нет - добавляем
$query = "INSERT out_state_table SET device_id='".$_GET['ID']."', out_state='".$_GET['Rele']."', date_time='$date_today'"; //Записать данные
$result = mysqli_query($conn, $query);
}
}

if(isset($_GET['Term'])) { //Если устройство передало новое значение температуры
//проверяем есть ли в БД предыдущее значение этого параметра
$query = "SELECT temperature FROM temperature_table WHERE device_id='".$_GET['ID']."'";
$result = mysqli_query($conn, $query);
$date_today = date("Y-m-d H:i:s"); //текущее время
if(mysqli_num_rows($result) == 1){ //Если в таблице есть данные для этого устройства - обновляем
$query = "UPDATE temperature_table SET temperature='".$_GET['Term']."', date_time='$date_today' WHERE device_id = '".$_GET['id']."'";
$result = mysqli_query($conn, $query);
} else { //Если данных для этого устройства нет - добавляем
$query = "INSERT temperature_table SET device_id='".$_GET['id']."', temperature='".$_GET['Term']."', date_time='$date_today'"; //Записать данные
$result = mysqli_query($conn, $query);
}
}

//Достаём из БД текущую команду управления реле
$query = "SELECT command FROM command_table WHERE device_id = '".$_GET['id']."'";
$result = mysqli_query($conn, $query);
if(mysqli_num_rows($result) == 1){ //Если в таблице есть данные для этого устройства
$Arr = mysqli_fetch_array($result);
$Command = $Arr['command'];
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