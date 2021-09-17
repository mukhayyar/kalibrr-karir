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

    $pageHTML = "<div class='container mt-4'>";
    $pageHTML .= '
        <form>
        <div class="input-group mb-3">
        <input type="text" name="text" class="form-control" placeholder="Cari Kerja">
        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Tenure</button>
        <ul class="dropdown-menu dropdown-menu-end">
        <li>
        <input class="form-check-input" type="checkbox" name="tenure[]" value="Full time" id="tenure1">
        <label class="form-check-label" for="tenure1">
                Full time
                </label>
                </li>
                <li>
                <input class="form-check-input" type="checkbox" name="tenure[]" value="Part time" id="tenure2>
                <label class="form-check-label" for="tenure2">
                Part time
                </label>
                </li>
                <li>
                <input class="form-check-input" type="checkbox" name="tenure[]" value="Contractual" id="tenure3>
                <label class="form-check-label" for="tenure3">
                Contractual
                </label>
                </li>
                <li>
                <input class="form-check-input" type="checkbox" name="tenure[]" value="Freelance" id="tenure4>
                <label class="form-check-label" for="tenure4">
                Freelance
                </label>
                </li>
                </ul>
        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Job Level</button>
        <ul class="dropdown-menu dropdown-menu-end">
                <li>
                <input class="form-check-input" type="checkbox" name="work_experience[]" value="100" id="work1">
                <label class="form-check-label" for="tenure1">
                Internship / OJT
                </label>
                </li>
                <li>
                <input class="form-check-input" type="checkbox" name="work_experience[]" value="200" id="work2>
                <label class="form-check-label" for="work2">
                Fresh Grad / Entry Level
                </label>
                </li>
                <li>
                <input class="form-check-input" type="checkbox" name="work_experience[]" value="300" id="work3>
                <label class="form-check-label" for="work3">
                Associate / Specialis
                </label>
                </li>
                <li>
                <input class="form-check-input" type="checkbox" name="work_experience[]" value="400" id="work4>
                <label class="form-check-label" for="work4">
                Mid-Senior Level
                </label>
                </li>
                <li>
                <input class="form-check-input" type="checkbox" name="work_experience[]" value="500" id="work4>
                <label class="form-check-label" for="work4">
                Director / Executive.
                </label>
                </li>
        </ul>
        <button class="btn btn-primary" type="submit">Cari</button>
        </div>
        </form>';
    $pageHTML .= "</div>";
    $pageHTML .= "<div class='row'>";
    $pageHTML .= "<div class='container p-4'>";

    foreach($jsonObj->jobs as $job){
        $url = "https://www.kalibrr.id/c/".$job->company->code."/jobs/".$job->id."/".$job->slug."'>";
        $pageHTML .= "<div class='card border-danger m-4'> <div class='row g-0'> <div class='col-md-4 p-4'> <div class='text-center'>";
        $pageHTML .= "<img class='rounded' src='".$job->company_info->logo_small."' /> </div> </div>";
        $pageHTML .= "<div class='col-md-8'> <div class='card-body'>";
        $pageHTML .= "<h3> <a target='_blank' href='".$url.$job->name."</a> - ".$job->tenure."</h3>"; 
        $old_date_timestamp = strtotime($job->application_end_date);
        $new_date = date('d M', $old_date_timestamp);   
        $pageHTML .= "<p  class='card-text'>Recruiter last seen ".timeago($job->es_recruiter_last_seen)."</p>";
        $pageHTML .= "<p  class='card-text'>".$job->company_name." ";
        $pageHTML .= $job->google_location->address_components->city.",".$job->google_location->address_components->country."</p>";
        $pageHTML .= "<p class='card-text'><small class='text-muted'>Apply before: ".$new_date."</small></p> </div> </div> <div class='card-footer text-muted'>";
        $pageHTML .= "Job created at ".timeago($job->created_at);
        $pageHTML .= "</div> </div> </div>";
    }
    $pageHTML .= "</div>";
    $pageHTML .= "</div>";
    $pageScript = "<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js' integrity='sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ' crossorigin='anonymous'></script>
    ";
    echo $pageCSS.$pageHTML.$pageScript;
}