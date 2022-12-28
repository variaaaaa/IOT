<?php
$query_id = "SELECT * FROM device_table";
$result_id = mysqli_query($conn, $query_id);
while ($row_id = mysqli_fetch_assoc($result_id)) {
    $id = $row_id['device_id'];
    //-----------------Получаем из БД все данные об устройстве-------------------
    $query = "SELECT * FROM device_table WHERE device_id= '$id'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 1) { //Если в БД есть данные о имени для этого устройства
        $Arr = mysqli_fetch_array($result);
        $device_name = $Arr['name'];
    } else { //Если в БД нет данных о имени для этого устройства
        $device_name = '?';
    }

    $query = "SELECT * FROM temperature_table WHERE device_id = '$id'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 1) { //Если в БД есть данные о температуре для этого устройства
        $Arr = mysqli_fetch_array($result);
        $temperature = $Arr['temperature'];
        $temperature_dt = $Arr['date_time'];
    } else { //Если в БД нет данных о температуре для этого устройства
        $temperature = '?';
        $temperature_dt = '?';
    }

    $query = "SELECT * FROM out_state_table WHERE device_id = '$id'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 1) { //Если в БД есть данные о реле для этого устройства
        $Arr = mysqli_fetch_array($result);
        $out_state = $Arr['out_state'];
        $out_state_dt = $Arr['date_time'];
    } else { //Если в БД нет данных о реле для этого устройства
        $out_state = '?';
        $out_state_dt = '?';
    }
    //----------------------------------------------------------------------------------------

    
    //------Формируем таблицу для вывода значния для каждого устройства в бд---------------------
    echo '
    <tr>
    <td width=100px margin-top=10px margin-bottom=10px> Устройство:
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
    <form action="index.php">
    <button formmethod="POST" name="button_on" value="' . $id . '">Включить реле</button>
    </form>
    <form>
    <button formmethod="POST" name="button_off" value="' . $id . '">Выключить реле</button>
    </form>';
    //-----------------------------------------------------------------------
}

//------Проверяем данные, полученные от пользователя---------------------

if (isset($_POST['button_on'])) {
    $date_today = date("Y-m-d H:i:s");
    $query = "UPDATE command_table SET command='1', date_time='$date_today' WHERE device_id = '$_POST[button_on]'";
    $result = mysqli_query($conn, $query);
    if (mysqli_affected_rows($conn) != 1) //Если не смогли обновить - значит в таблице просто нет данных о команде для этого устройства
    { //вставляем в таблицу строчку с данными о команде для устройства
        $query = "INSERT command_table SET device_id='$id', command='1', date_time='$date_today'";
        $result = mysqli_query($conn, $query);
    }

    $query_command = "SELECT * FROM command_table WHERE device_id = '$_POST[button_on]'";
    $result_command = mysqli_query($conn, $query_command);

    $row_command = mysqli_fetch_assoc($result_command);
    $command = $row_command['command'];
    $date_today = $row_command['date_time'];

    $query = "SELECT out_state FROM out_state_table WHERE device_id= '$_POST[button_on]'";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) == 1){ //Если в таблице есть данные для этого устройства - обновляем
        $query = "UPDATE out_state_table SET out_state='$command', date_time='$date_today' WHERE device_id = '$_POST[button_on]'";
        $result = mysqli_query($conn, $query);
    } else { //Если данных для такого устройства нет - добавляем
        $query = "INSERT out_state_table SET device_id='$_POST[button_on]', out_state='$command', date_time='$date_today'"; //Записать данные
        $result = mysqli_query($conn, $query);
    }
}

if (isset($_POST['button_off'])) {
    $date_today = date("Y-m-d H:i:s");
    $query = "UPDATE command_table SET command='0', date_time='$date_today' WHERE device_id = '$_POST[button_off]'";
    $result = mysqli_query($conn, $query);
    if (mysqli_affected_rows($conn) != 1) //Если не смогли обновить - значит в таблице просто нет данных о команде для этого устройства
    { //вставляем в таблицу строчку с данными о команде для устройства
        $query = "INSERT command_table SET device_id='$id', command='0', date_time='$date_today'";
        $result = mysqli_query($conn, $query);
    }

    $query_command = "SELECT * FROM command_table WHERE device_id = '$_POST[button_off]'";
    $result_command = mysqli_query($conn, $query_command);

    $row_command = mysqli_fetch_assoc($result_command);
    $command = $row_command['command'];
    $date_today = $row_command['date_time'];

    $query = "SELECT out_state FROM out_state_table WHERE device_id = '$_POST[button_off]'";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) == 1){ //Если в таблице есть данные для этого устройства - обновляем
        $query = "UPDATE out_state_table SET out_state='$command', date_time='$date_today' WHERE device_id = '$_POST[button_off]'";
        $result = mysqli_query($conn, $query);
    } else { //Если данных для такого устройства нет - добавляем
        $query = "INSERT out_state_table SET device_id='$_POST[button_off]', out_state='$command', date_time='$date_today'"; //Записать данные
        $result = mysqli_query($conn, $query);
    }
}

?>