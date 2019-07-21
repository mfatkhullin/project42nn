<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Расчёт заявок на исполнение</title>
		<link href="concrete.css" rel="stylesheet">
	</head>
<body> 

<div class="row">
<div class="twelve columns">Выберите заявку</div>
<div class="twelve columns">
<form method="GET" action="loading.php"> <!--указание метода GET-->
Выберите услугу: 
<select name="service_id"> <!--выпадающий список-->
 <!--<select name="color" size=3> <!--список с прокруткой-->
 <option value="1">Антенна</option>
 <option value="2">Благоустройство дворовых территорий (МАФ, обрезка)</option>
 <option value="3">Водопроводное хозяйство</option>
 <option value="4">Вывоз ТБО</option>
 <option value="5">Горячее водоснабжение</option>
 <option value="6">Дератизация (дезинсекция)</option>
 <option value="7">Домофон</option>
 <option value="8">Канализационное хозяйство</option>
 <option value="9">Крыша и водосточная система</option>
 <option value="10">Лифт</option>
 <option value="11">Мусоропровод</option>
 <option value="12">Неисправность вентиляции, дымоходов</option>
 <option value="13">Неисправность сетей ГВС </option>
 <option value="14">Неисправность сетей канализации</option>
 <option value="15">Неисправность сетей отопления</option>
 <option value="16">Неисправность сетей ХВС</option>
 <option value="17">ОДН</option>
 <option value="18">Освещение МОП</option>
 <option value="19">Отопление</option>
 <option value="20">Подвальное помещение и техническое подполье</option>
 <option value="21">Подъезд</option>
 <option value="22">Уборка двора</option>
 <option value="23">Уборка подъезда</option>
 <option value="24">Фасад</option>
 <option value="25">Холодное водоснабжение</option>
 <option value="26">Чердак</option>
 <option value="27">Электроснабжение</option>
 <option value="28">Энергосбережение</option>
</select>
Количество: <input type="text" name="count">
Cтоимость: <input type="text" name="cost">
<input type="submit" value="Отправить">
</form>
</div>
</div>

<?php
//Подключение к БД

$firm_count= $_GET['count']; //Количество заявок
$firm_cost = $_GET['cost']; //Стоимость
$service_name_id_form = $_GET['service_id']; //ID Заявки
$start_count = 100;
$require_count = $start_count;
//$firm_count=6; //Количество заявок
//$firm_cost=5; //Количество заявок

$servername = "localhost"; // локалхост
$username = "p42"; // имя пользователя
$password = "p42"; // пароль если существует
$dbname = "p42"; // база данных
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) { 
   printf("Невозможно подключиться к базе данных. Код ошибки: %s\n", mysqli_connect_error()); 
   exit; 
} 

//Создание компании
$sql_new_company = "INSERT INTO  `p42`.`company_name_result` (`company_id`,`company_name`) VALUES (NULL, 'NEW COMPANY')";
			if ($conn->query($sql_new_company) === TRUE) {
			//echo "Успешно создана новая запись</br>";
			} else {
			//   echo "Ошибка: " . $sql_new_company . "<br>" . $conn->error;
			}
$new_company_id = $conn->insert_id;
//echo $new_company_id, "</br>";
//Создание цены для услуги
$sql_new_cost = "INSERT INTO  `p42`.`service_cost_result` (`service_cost_id`,`company_id`, `service_name_id`, `service_count`, `service_cost`) VALUES (NULL, ".$new_company_id.", ".$service_name_id_form.", ".$firm_count.", ".$firm_cost.")";
			if ($conn->query($sql_new_cost) === TRUE) {
			//echo "Успешно создана новая запись</br>";
			} else {
			//   echo "Ошибка: " . $sql_new_cost . "<br>" . $conn->error;
			}
$new_cost_id = $conn->insert_id;
//echo $new_cost_id;
//


//Выборка из БД по услуге service_name_id =
$query_service_cost = mysqli_query($conn, 'SELECT * FROM service_cost_result WHERE service_name_id = '.$service_name_id_form.' ORDER BY service_cost ASC LIMIT 6');

//Формирование массива компаний исполнителей $company_id[]
 while( $row = $query_service_cost->fetch_assoc() ){ 
        $company_id[] = $row["company_id"];
		$require_count = $require_count - $firm_count;
		if ($require_count<=0)
		{
			break;
		}
		//echo $require_count, "<br>";
    } 
	print_r($company_id);
		//echo $service_name_id_form, "</br>";
		//echo $new_company_id, "</br>";	
//
	
	//Вывод таблицы результатов с контрактами 
	echo '<div class="row">';
	echo '<div class="twelve columns">Исполнение заявки</div>';
	foreach ($company_id as $value) {
	//Отдельный подсчет для последнего контракта
    if($value == end($company_id) AND $value == $new_company_id) {
		echo $value, "</br>";
		echo $service_name_id_form, "</br>";
		echo $new_company_id, "</br>";
        $query_company_name = mysqli_query($conn, 'SELECT company_name FROM company_name_result WHERE company_id = '.$value.'');
		$result_company_name = mysqli_fetch_assoc($query_company_name);
		$query_service_count = mysqli_query($conn, 'SELECT service_count FROM service_cost_result WHERE company_id = '.$value.' AND service_name_id='.$service_name_id_form.'');
		$result_service_count = mysqli_fetch_assoc($query_service_count);
		$result_new_company_service_all = $start_count - $result_service_count["service_count"];
		echo '<div class="four columns">';
			echo $result_service_count["service_count"]+$require_count;
		echo '</div>';
		echo '<div class="four columns">';
			echo $start_count;
		echo '</div>';
		echo '<div class="four columns">';
			$percent_isp = floor((($result_service_count["service_count"]+$require_count) / $start_count) * 100);
			echo $percent_isp."%";
		echo '</div>';
	}
	//Подсчет всех остальных
	else {
		if ($value==$new_company_id)
		{
			echo $value, "</br>";
			echo $service_name_id_form, "</br>";
			echo $new_company_id, "</br>";
			$query_company_name = mysqli_query($conn, 'SELECT company_name FROM company_name_result WHERE company_id = '.$value.'');
			$result_company_name = mysqli_fetch_assoc($query_company_name);
			$query_service_count = mysqli_query($conn, 'SELECT service_count FROM service_cost_result WHERE company_id = '.$value.' AND service_name_id='.$service_name_id_form.'');
			$result_service_count = mysqli_fetch_assoc($query_service_count);
			echo '<div class="four columns">';
				echo $result_service_count["service_count"]+$require_count;
			echo '</div>';
			echo '<div class="four columns">';
				echo $start_count;
			echo '</div>';
			echo '<div class="four columns">';
				$percent_isp = floor((($result_service_count["service_count"]+$require_count) / $start_count) * 100);
				echo $percent_isp."%";
			echo '</div>';
	}
  }
	}
  //Проверка %
  if($percent_isp<100)
  {
	echo '<div class="twelve columns">Ваши цены высокие, другие компании решают те же проблемы за меньшие деньги. Попробуйте снизить цены на услуги, чтобы получить больше заявок</div>';
  }  
  if($percent_isp<100 AND $result_new_company_service_all>0)
  {
	echo '<div class="twelve columns">Ваше предложение не покрывается 100% заявок, даже по самой низкой цене. Попробуйте указать большее количество заявок, чтобы охватить больше жителей</div>';
  }
 
  echo '</div>';

//Удаление временной записи
$sql_delete_new_company = "DELETE FROM `p42`.`company_name_result` WHERE company_id = ".$new_company_id."";
			if ($conn->query($sql_delete_new_company) === TRUE) {
			//echo "Успешно создана новая запись</br>";
			} else {
			//   echo "Ошибка: " . $sql_delete_new_company . "<br>" . $conn->error;
			}

$sql_delete_new_company_cost = "DELETE FROM `p42`.`service_cost_result` WHERE company_id = ".$new_company_id."";
			if ($conn->query($sql_delete_new_company_cost) === TRUE) {
			//echo "Успешно создана новая запись</br>";
			} else {
			//   echo "Ошибка: " . $sql_delete_new_company_cost . "<br>" . $conn->error;
}
//

?>
</body>
</html>