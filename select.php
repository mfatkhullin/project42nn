<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Расчёт контрактов</title>
		<link href="concrete.css" rel="stylesheet">
	</head>
<body> 
<div class="row">
<div class="twelve columns">Выберите заявку</div>
<div class="twelve columns">
<form method="GET" action="select.php"> <!--указание метода GET-->
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
<input type="submit" value="Отправить">
</form>
</div>
</div>
<?php
//Подключение к БД
//$require_count = $_POST['service_id'];
//$service_name_id_form = $_POST['service_count'];
$require_count= $_GET['count']; //Количество заявок
$service_name_id_form = $_GET['service_id']; //ID Заявки
$servername = "localhost"; // локалхост
$username = "p42"; // имя пользователя
$password = "p42"; // пароль если существует
$dbname = "p42"; // база данных
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) { 
   printf("Невозможно подключиться к базе данных. Код ошибки: %s\n", mysqli_connect_error()); 
   exit; 
} 
//

if($require_count == TRUE AND $service_name_id_form==TRUE){
	

//Выборка из БД по услуге service_name_id =
$query_service_cost = mysqli_query($conn, 'SELECT * FROM service_cost WHERE service_name_id = '.$service_name_id_form.' ORDER BY service_cost ASC LIMIT 5');

//Формирование массива компаний исполнителей $company_id[]
 while( $row = $query_service_cost->fetch_assoc() ){ 
        $company_id[] = $row["company_id"];
		$require_count = $require_count - $row["service_count"];
		if ($require_count<=0)
		{
			break;
		}
		//echo $require_count, "<br>";
    } 
	
//

	$l=0; //счетчик для таблицы
	
	//Вывод таблицы результатов с контрактами 
	echo '<div class="row">';
	echo '<div class="twelve columns">Исполнители</div>';
	foreach ($company_id as $value) {
	//Отдельный подсчет для последнего контракта
    if($value == end($company_id)) {
        $query_company_name = mysqli_query($conn, "SELECT company_name FROM company_name WHERE company_id = ".$value."");
		$result_company_name = mysqli_fetch_assoc($query_company_name);
		$query_service_count = mysqli_query($conn, "SELECT service_count FROM service_cost WHERE company_id = ".$value." AND service_name_id=".$service_name_id_form."");
		$result_service_count = mysqli_fetch_assoc($query_service_count);
		//echo $result_service_count["service_count"];
		if($result_service_count["service_count"]!=0)
		{
		$l++;
		echo '<div class="four columns">'.$l.'.</div>';
		echo '<div class="four columns">';
		echo $result_company_name["company_name"];
		echo '</div>';
		echo '<div class="four columns">';
		if($require_count<0)
		{
			echo $require_count+$result_service_count["service_count"];

		}
		else {
			echo $result_service_count["service_count"];

		}
		echo '</div>';
		}
	}
	//Подсчет всех остальных
	else {
	$query_company_name = mysqli_query($conn, "SELECT company_name FROM company_name WHERE company_id = ".$value."");
	$result_company_name = mysqli_fetch_assoc($query_company_name);
	$query_service_count = mysqli_query($conn, "SELECT service_count FROM service_cost WHERE company_id = ".$value." AND service_name_id=".$service_name_id_form."");
	$result_service_count = mysqli_fetch_assoc($query_service_count);
	if($result_service_count["service_count"]!=0){
	$l++;
	echo '<div class="four columns">'.$l.'.</div>';
	echo '<div class="four columns">';
	echo $result_company_name["company_name"];
	echo '</div>';
	echo '<div class="four columns">';
	echo $result_service_count["service_count"];
	echo '</div>';
	}
	}
  }
  //Проверка сколько заявок может быть не закрыто
  if($require_count>0)
  {
	  echo '<div class="twelve columns">Заявок не будет закрыто: '.$require_count.'</div>';
  }
  echo '</div>';
}
?>
</body>
</html>