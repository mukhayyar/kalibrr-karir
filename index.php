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

function codeToJob($code)
{
    if($code == 100)
    {
        return "Internship";
    } else if($code == 200)
    {
        return "Fresh Grad";
    } else if($code == 300)
    {
        return "Associate";
    } else if($code == 400)
    {
        return "Mid-Senior Level";
    } else if($code == 500)
    {
        return "Director / Executive";
    }
}

function codeToEdu($code)
{
    if($code == 100)
    {
        return "min. below High School";
    } else if($code == 150)
    {
        return "High Scool(Pursued)";
    } else if($code == 200)
    {
        return "High School(Graduated)";
    } else if($code == 300)
    {
        return "Vocational Course";
    } else if($code == 400)
    {
        return "Vocational Course(Completed)";
    }
    else if($code == 450)
    {
        return "Associated Degree(Completed)";
    }
    else if($code == 500)
    {
        return "Bachelor Degree(Pursued)";
    }
    else if($code == 550)
    {
        return "Bachelor Degree(Completed)";
    }
    else if($code == 600)
    {
        return "Masters Degree(Pursued)";
    }
    else if($code == 650)
    {
        return "Masters Degree(Completed)";
    }
    else if($code == 700)
    {
        return "PhD Degree(Pursued)";
    }
    else if($code == 750)
    {
        return "PhD Degree(Completed)";
    }
}

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

    $pageCSS = "<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' integrity='sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm' crossorigin='anonymous'>";

    $pageCSS .= "<style>
    .checkbox-menu li label {
        display: block;
        padding: 3px 10px;
        clear: both;
        font-weight: normal;
        line-height: 1.42857143;
        color: #333;
        white-space: nowrap;
        margin:0;
        transition: background-color .4s ease;
    }
    .checkbox-menu li input {
        margin: 0px 5px;
        top: 2px;
        position: relative;
    }
    
    .checkbox-menu li.active label {
        background-color: #cbcbff;
        font-weight:bold;
    }
    
    .checkbox-menu li label:hover,
    .checkbox-menu li label:focus {
        background-color: #f5f5f5;
    }
    
    .checkbox-menu li.active label:hover,
    .checkbox-menu li.active label:focus {
        background-color: #b8b8ff;
    }
    </style>";

    $pageHTML = "<div class='container mt-4'>";
    $pageHTML .= '
        <form>
        <div class="input-group">
        <input type="text" name="text" class="form-control" placeholder="Cari Kerja">
        <div class="input-group-append">
        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Tenure</button>
                <ul class="dropdown-menu checkbox-menu allow-focus">
                <li>
                <input class="form-check-input" type="checkbox" name="tenure[]" value="Full time" id="tenure1>
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
        </div>
        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Job Level</button>
        <ul class="dropdown-menu checkbox-menu allow-focus">
                <li>
                <input class="form-check-input" type="checkbox" name="work_experience[]" value="100" id="work1>
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
    if(isset($jsonObj->count)){
        $pageHTML .= "<h1>Showing ".$jsonObj->count." Jobs</h1>";
    } else {
        $pageHTML .= "<h1>Showing 0 Jobs</h1>";
    }

    if(isset($jsonObj->jobs)){
    foreach($jsonObj->jobs as $job){
        $url = "https://www.kalibrr.id/c/".$job->company->code."/jobs/".$job->id."/".$job->slug;
        $pageHTML .= "<div class='card border-danger m-4'> <div class='row g-0'> <div class='col-md-4 p-4'> <div class='text-center'>";
        $pageHTML .= "<img class='rounded' src='".$job->company_info->logo_small."' /> </div> </div>";
        $pageHTML .= "<div class='col-md-8'> <div class='card-body'>";
        $pageHTML .= "<h3><strong>".$job->name."</strong></h3>"; 
        $old_date_timestamp = strtotime($job->application_end_date);
        $new_date = date('d M Y', $old_date_timestamp);   
        $pageHTML .= "<p  class='card-text'>".$job->qualifications."</p>";
        $pageHTML .= "<span class='badge badge-info'>".$job->tenure."</span>";
        $pageHTML .= "<span class='badge badge-info ml-1'>".codeToJob($job->work_experience)."</span>";
        $pageHTML .= "<span class='badge badge-info ml-1'>".codeToEdu($job->education_level)."</span>";
        $pageHTML .= "<span class='badge badge-warning ml-1'>Apply before: ".$new_date."</span>";
        $pageHTML .= "<div class='mt-2'></div><a class='btn btn-primary' target='_blank'  role='button' href='".$url."'>Apply</a> 
        <a class='btn btn-secondary' target='_blank' href='".$job->company_info->url."'>Company Website</a></div> </div>";
        $pageHTML .= "</div> </div>";
    }
    }  else {
        $pageHTML .= "<h3 class='text-center'>Pekerjaan tidak ditemukan</h3>";
    }
    $pageHTML .= "</div>";
    $pageHTML .= "</div>";
    $pageScript = "<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js' integrity='sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ' crossorigin='anonymous'></script>
    ";
    echo $pageCSS.$pageHTML.$pageScript;
}