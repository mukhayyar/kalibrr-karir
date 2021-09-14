<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'www.kalibrr.com/api/job_board/search?country=Indonesia&function=Legal&level=&text=',
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

function timeago($date) {
    $timestamp = strtotime($date);	
    
    $strTime = array("second", "minute", "hour", "day", "month", "year");
    $length = array("60","60","24","30","12","10");

    $currentTime = time();
    if($currentTime >= $timestamp) {
         $diff     = time()- $timestamp;
         for($i = 0; $diff >= $length[$i] && $i <  count($length)-1; $i++) {
         $diff = $diff / $length[$i];
         }

         $diff = round($diff);
         return $diff . " " . $strTime[$i] . "(s) ago ";
    }
 }

if($err) {
    if($debug) echo "cURL Error:". $err;
    else echo "Oops, Something wrong happened. Please Try Again";
} else {
    $jsonObj = json_decode($response);

    $pageCSS = "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU' crossorigin='anonymous'>";

    $pageHTML = "<h2>Search Legal Jobs</h2>";
    $pageHTML = "<div class='row'>";
    $pageHTML = "<div class='container'>";

    foreach($jsonObj->jobs as $job){
        $url = "https://www.kalibrr.id/c/".$job->company->code."/jobs/".$job->id."/".$job->slug."'>";
        $pageHTML .= "<div class='card border-danger m-4'>";
        $pageHTML .= "<div class='row g-0'>";
        $pageHTML .= "<div class='col-md-4 p-4'>";
        $pageHTML .= "<div class='text-center'>";
        $pageHTML .= "<img class='rounded' src='".$job->company_info->logo_small."' />";
        $pageHTML .= "</div>";
        $pageHTML .= "</div>";
        $pageHTML .= "<div class='col-md-8'>";
        $pageHTML .= "<div class='card-body'>";
        $pageHTML .= "<h3> <a href='".$job->name."</a></h3>"; 
        $old_date_timestamp = strtotime($job->application_end_date);
        $new_date = date('d M', $old_date_timestamp);   
        $pageHTML .= "<p  class='card-text'>Recruiter last seen ".timeago($job->es_recruiter_last_seen)."</p>";
        $pageHTML .= "<p  class='card-text'>".$job->company_name." ";
        $pageHTML .= $job->google_location->address_components->city.",".$job->google_location->address_components->country."</p>";
        $pageHTML .= "<p class='card-text'><small class='text-muted'>Apply before: ".$new_date."</small></p>";
        $pageHTML .= "</div>";
        $pageHTML .= "</div>";
        $pageHTML .= "<div class='card-footer text-muted'>";
        $pageHTML .= "Job created at ".timeago($job->created_at);
        $pageHTML .= "</div>";
        $pageHTML .= "</div>";
        $pageHTML .= "</div>";
    }
    $pageHTML .= "</div>";
    $pageHTML .= "</div>";
    $pageScript = "<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js' integrity='sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ' crossorigin='anonymous'></script>
    ";
    echo $pageCSS.$pageHTML.$pageScript;
}