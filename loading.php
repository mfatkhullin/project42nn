<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>������ ������ �� ����������</title>
		<link href="concrete.css" rel="stylesheet">
	</head>
<body> 

<div class="row">
<div class="twelve columns">�������� ������</div>
<div class="twelve columns">
<form method="GET" action="loading.php"> <!--�������� ������ GET-->
�������� ������: 
<select name="service_id"> <!--���������� ������-->
 <!--<select name="color" size=3> <!--������ � ����������-->
 <option value="1">�������</option>
 <option value="2">��������������� �������� ���������� (���, �������)</option>
 <option value="3">������������� ���������</option>
 <option value="4">����� ���</option>
 <option value="5">������� �������������</option>
 <option value="6">����������� (�����������)</option>
 <option value="7">�������</option>
 <option value="8">��������������� ���������</option>
 <option value="9">����� � ����������� �������</option>
 <option value="10">����</option>
 <option value="11">������������</option>
 <option value="12">������������� ����������, ���������</option>
 <option value="13">������������� ����� ��� </option>
 <option value="14">������������� ����� �����������</option>
 <option value="15">������������� ����� ���������</option>
 <option value="16">������������� ����� ���</option>
 <option value="17">���</option>
 <option value="18">��������� ���</option>
 <option value="19">���������</option>
 <option value="20">���������� ��������� � ����������� ��������</option>
 <option value="21">�������</option>
 <option value="22">������ �����</option>
 <option value="23">������ ��������</option>
 <option value="24">�����</option>
 <option value="25">�������� �������������</option>
 <option value="26">������</option>
 <option value="27">����������������</option>
 <option value="28">����������������</option>
</select>
����������: <input type="text" name="count">
C��������: <input type="text" name="cost">
<input type="submit" value="���������">
</form>
</div>
</div>

<?php
//����������� � ��

$firm_count= $_GET['count']; //���������� ������
$firm_cost = $_GET['cost']; //���������
$service_name_id_form = $_GET['service_id']; //ID ������
$start_count = 100;
$require_count = $start_count;
//$firm_count=6; //���������� ������
//$firm_cost=5; //���������� ������

$servername = "localhost"; // ���������
$username = "p42"; // ��� ������������
$password = "p42"; // ������ ���� ����������
$dbname = "p42"; // ���� ������
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) { 
   printf("���������� ������������ � ���� ������. ��� ������: %s\n", mysqli_connect_error()); 
   exit; 
} 

//�������� ��������
$sql_new_company = "INSERT INTO  `p42`.`company_name_result` (`company_id`,`company_name`) VALUES (NULL, 'NEW COMPANY')";
			if ($conn->query($sql_new_company) === TRUE) {
			//echo "������� ������� ����� ������</br>";
			} else {
			//   echo "������: " . $sql_new_company . "<br>" . $conn->error;
			}
$new_company_id = $conn->insert_id;
//echo $new_company_id, "</br>";
//�������� ���� ��� ������
$sql_new_cost = "INSERT INTO  `p42`.`service_cost_result` (`service_cost_id`,`company_id`, `service_name_id`, `service_count`, `service_cost`) VALUES (NULL, ".$new_company_id.", ".$service_name_id_form.", ".$firm_count.", ".$firm_cost.")";
			if ($conn->query($sql_new_cost) === TRUE) {
			//echo "������� ������� ����� ������</br>";
			} else {
			//   echo "������: " . $sql_new_cost . "<br>" . $conn->error;
			}
$new_cost_id = $conn->insert_id;
//echo $new_cost_id;
//


//������� �� �� �� ������ service_name_id =
$query_service_cost = mysqli_query($conn, 'SELECT * FROM service_cost_result WHERE service_name_id = '.$service_name_id_form.' ORDER BY service_cost ASC LIMIT 6');

//������������ ������� �������� ������������ $company_id[]
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
	
	//����� ������� ����������� � ����������� 
	echo '<div class="row">';
	echo '<div class="twelve columns">���������� ������</div>';
	foreach ($company_id as $value) {
	//��������� ������� ��� ���������� ���������
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
	//������� ���� ���������
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
  //�������� %
  if($percent_isp<100)
  {
	echo '<div class="twelve columns">���� ���� �������, ������ �������� ������ �� �� �������� �� ������� ������. ���������� ������� ���� �� ������, ����� �������� ������ ������</div>';
  }  
  if($percent_isp<100 AND $result_new_company_service_all>0)
  {
	echo '<div class="twelve columns">���� ����������� �� ����������� 100% ������, ���� �� ����� ������ ����. ���������� ������� ������� ���������� ������, ����� �������� ������ �������</div>';
  }
 
  echo '</div>';

//�������� ��������� ������
$sql_delete_new_company = "DELETE FROM `p42`.`company_name_result` WHERE company_id = ".$new_company_id."";
			if ($conn->query($sql_delete_new_company) === TRUE) {
			//echo "������� ������� ����� ������</br>";
			} else {
			//   echo "������: " . $sql_delete_new_company . "<br>" . $conn->error;
			}

$sql_delete_new_company_cost = "DELETE FROM `p42`.`service_cost_result` WHERE company_id = ".$new_company_id."";
			if ($conn->query($sql_delete_new_company_cost) === TRUE) {
			//echo "������� ������� ����� ������</br>";
			} else {
			//   echo "������: " . $sql_delete_new_company_cost . "<br>" . $conn->error;
}
//

?>
</body>
</html>