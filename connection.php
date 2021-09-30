<?php

$curl = curl_init();

if(!isset($_GET["text"]))
{
    $textQuery = "";
} else {
    $textQuery = $_GET["text"];
}
if(!isset($_GET["tenure"]))
{
    $tenureQuery = "";
} else {
    $tenureQuery = implode(",",$_GET["tenure"]);
}
if(!isset($_GET["work_experience"]))
{
    $workQuery = "";
} else {
    $workQuery = implode(",",$_GET["work_experience"]);
}
curl_setopt_array($curl, array(
  CURLOPT_URL => 'www.kalibrr.com/api/job_board/search?country=Indonesia&function=Legal&work_experience='.$workQuery.'&text='.$textQuery." ".$tenureQuery,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);