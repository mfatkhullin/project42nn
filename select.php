<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>������ ����������</title>
		<link href="concrete.css" rel="stylesheet">
	</head>
<body> 
<div class="row">
<div class="twelve columns">�������� ������</div>
<div class="twelve columns">
<form method="GET" action="select.php"> <!--�������� ������ GET-->
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
<input type="submit" value="���������">
</form>
</div>
</div>
<?php
//����������� � ��
//$require_count = $_POST['service_id'];
//$service_name_id_form = $_POST['service_count'];
$require_count= $_GET['count']; //���������� ������
$service_name_id_form = $_GET['service_id']; //ID ������
$servername = "localhost"; // ���������
$username = "p42"; // ��� ������������
$password = "p42"; // ������ ���� ����������
$dbname = "p42"; // ���� ������
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) { 
   printf("���������� ������������ � ���� ������. ��� ������: %s\n", mysqli_connect_error()); 
   exit; 
} 
//

if($require_count == TRUE AND $service_name_id_form==TRUE){
	

//������� �� �� �� ������ service_name_id =
$query_service_cost = mysqli_query($conn, 'SELECT * FROM service_cost WHERE service_name_id = '.$service_name_id_form.' ORDER BY service_cost ASC LIMIT 5');

//������������ ������� �������� ������������ $company_id[]
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

	$l=0; //������� ��� �������
	
	//����� ������� ����������� � ����������� 
	echo '<div class="row">';
	echo '<div class="twelve columns">�����������</div>';
	foreach ($company_id as $value) {
	//��������� ������� ��� ���������� ���������
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
	//������� ���� ���������
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
  //�������� ������� ������ ����� ���� �� �������
  if($require_count>0)
  {
	  echo '<div class="twelve columns">������ �� ����� �������: '.$require_count.'</div>';
  }
  echo '</div>';
}
?>
</body>
</html>