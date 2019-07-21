<?php

$servername = "localhost"; // локалхост
$username = "p42"; // имя пользователя
$password = "p42"; // пароль если существует
$dbname = "p42"; // база данных
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
   die("Ошибка подключения: " . $conn->connect_error);
}
for($i=1;$i<29;$i++)
{
	$count = rand(20,30);
	$sql = "INSERT INTO  `p42`.`service_require` (`service_name_id` ,`service_require_count`) VALUES (".$i.", ".$count.")";
			if ($conn->query($sql) === TRUE) {
			echo "Успешно создана новая запись</br>";
			} else {
			   echo "Ошибка: " . $sql . "<br>" . $conn->error;
			}
}

/*
for($j=1;$j<6;$j++)
{
	for($k=1;$k<29;$k++)
	{
		$i++;
		$cost = rand(1,10);
		$count = rand(0,10);
		$sql = "INSERT INTO  `p42`.`service_cost` (`service_cost_id` ,`company_id` ,`service_name_id` ,`service_count` ,`service_cost`) VALUES (".$i.",  ".$j.",  ".$k.",  ".$count.",  ".$cost.")";
		if ($conn->query($sql) === TRUE) {
		echo "Успешно создана новая запись</br>";
		} else {
		   echo "Ошибка: " . $sql . "<br>" . $conn->error;
		}
	}
}

*/

// Закрыть подключение

?>