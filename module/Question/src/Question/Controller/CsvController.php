<?php
ini_set("display_errors", "1");
/* This is a controller class to read Question from csv file */
class CsvController
{
function csvimport()     //Funtion to improt csv file by browser
{
	echo "hgedkjhkdhkhgfdkf";
	die("dvfd");
$uploads_dir = getcwd() . '/public/uploads/csvimport.csv';
 $file = $_FILES["csv"]["tmp_name"];
if(move_uploaded_file($file, $uploads_dir))
{
echo "imported";
chmod($uploads_dir,0777);
$file1['file'] = $uploads_dir;
loadModel('csvread','addcsv',$file1);

}
else
{
echo "not imported";die;
}

}

}
?>