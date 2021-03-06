<?php
# connection to api kalibrr
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

# function
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

# write the api object to an element page 

if($err) {
    if($debug) echo "cURL Error:". $err;
    else echo "Oops, Something wrong happened. Please Try Again";
} else {
    $jsonObj = json_decode($response);
    $pageHTML = "";
    if(isset($jsonObj->jobs)){
    foreach($jsonObj->jobs as $job){
        $url = "https://www.kalibrr.id/c/".$job->company->code."/jobs/".$job->id."/".$job->slug;
        $pageHTML .= "<div class='card m-4'>";
        $pageHTML .= "<div class='center'><img style='height:70px;' src='".$job->company_info->logo_160x90."'/></div>";
        $pageHTML .= "<img id='mobile' style='height:70px;' src='".$job->company_info->logo_160x90."'/>";
        $pageHTML .= "<div class='card-body'>";
        $pageHTML .= "<h3><strong>".$job->name."</strong></h3>";
        $pageHTML .= $job->company_info->name." | ".$job->google_location->address_components->city.", ".$job->google_location->address_components->country."<div></div>"; 
        if($job->company->verified_business){
            $pageHTML .= "Verified <i class='bi bi-check-circle'></i>"; 
        } else {
            $pageHTML .= "Unverified <i class='bi bi-dash-circle'></i>"; 
        }
        if($job->es_is_top_brand){
            $pageHTML .= " Top Brand <i class='bi bi-award'></i>"; 
        } 
        if($job->is_work_from_home){
            $pageHTML .= " Top Brand <i class='bi bi-house-door'></i>"; 
        } 
        $old_date_timestamp = strtotime($job->application_end_date);
        $new_date = date('d M Y', $old_date_timestamp);   
        // $pageHTML .= "<p  class='card-text'>".$job->qualifications."</p>";
        $pageHTML .= "<div class='mt-2'></div>";
        $pageHTML .= "<span class='badge badge-info'>".$job->tenure."</span>";
        $pageHTML .= "<span class='badge badge-info ml-1'>".codeToJob($job->work_experience)."</span>";
        $pageHTML .= "<span class='badge badge-info ml-1'>".codeToEdu($job->education_level)."</span>";
        $pageHTML .= "<span class='badge badge-success ml-1'>Apply before: ".$new_date."</span>";
        $pageHTML .= "<div class='m-2'></div><a id='apply-button' class='btn btn-danger' target='_blank'  role='button' href='".$url."'> Apply <i class='bi bi-arrow-up-right-square'></i></a> 
        <a class='btn btn-warning' target='_blank' href='".$job->company_info->url."'>Company Website <i class='bi bi-globe2'></i></a></div> </div>";
    }
    }  else {
        $pageHTML .= "<h3 class='text-center'>Pekerjaan tidak ditemukan</h3>";
    }
} ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' integrity='sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm' crossorigin='anonymous'>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css'>
    <style>
        .card {
            flex-direction: row;
        }
        .center {
            padding: 70px 0;
        }
        .card img {
            border-top-right-radius: 0;
            border-bottom-left-radius: calc(0.25rem - 1px);
        }

        img#mobile {
            display: none;
        }
        @media screen and (max-width: 768px) {
            .card {
                flex-direction:column;
                align-items: center;
            }
            .card img {
                width: 50%;
            }
            .center {
                display: none;
            }
            img#mobile {
                display: block;
            }
        }


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
    </style>
</head>
<body>
    <div class='container mt-4'>
    <form>
        <div class="input-group">
        <input type="text" name="text" class="form-control" placeholder="Cari Kerja">
        <div class="input-group-append">
            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Type</button>
            <ul class="dropdown-menu checkbox-menu allow-focus">
            <li>
                <input class="form-check-input" type="checkbox" name="tenure[]" value="Full time" id="tenure1>
                                    <label class=" form-check-label" for="tenure1"> Full time </label>
            </li>
            <li>
                <input class="form-check-input" type="checkbox" name="tenure[]" value="Part time" id="tenure2>
                                        <label class=" form-check-label" for="tenure2"> Part time </label>
            </li>
            <li>
                <input class="form-check-input" type="checkbox" name="tenure[]" value="Contractual" id="tenure3>
                                            <label class=" form-check-label" for="tenure3"> Contractual </label>
            </li>
            <li>
                <input class="form-check-input" type="checkbox" name="tenure[]" value="Freelance" id="tenure4>
                                                <label class=" form-check-label" for="tenure4"> Freelance </label>
            </li>
            </ul>
        </div>
        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Level</button>
        <ul class="dropdown-menu checkbox-menu allow-focus">
            <li>
            <input class="form-check-input" type="checkbox" name="work_experience[]" value="100" id="work1>
                                                <label class=" form-check-label" for="tenure1"> Internship / OJT </label>
            </li>
            <li>
            <input class="form-check-input" type="checkbox" name="work_experience[]" value="200" id="work2>
                                                    <label class=" form-check-label" for="work2"> Fresh Grad / Entry Level </label>
            </li>
            <li>
            <input class="form-check-input" type="checkbox" name="work_experience[]" value="300" id="work3>
                                                        <label class=" form-check-label" for="work3"> Associate / Specialis </label>
            </li>
            <li>
            <input class="form-check-input" type="checkbox" name="work_experience[]" value="400" id="work4>
                                                            <label class=" form-check-label" for="work4"> Mid-Senior Level </label>
            </li>
            <li>
            <input class="form-check-input" type="checkbox" name="work_experience[]" value="500" id="work4>
                                                                <label class=" form-check-label" for="work4"> Director / Executive. </label>
            </li>
        </ul>
        <button class="btn btn-warning" type="submit">Cari</button>
        </div>
    </form>
    </div>
    <div class='row'>
    <div class='container p-4'> <?php 
        if ($tenureQuery != '') {
            echo "<p>Employment Type: ".$tenureQuery."</p>";

        }
        if ($workQuery  != '') {
            echo "<p>Job Level: ".codeToJob($workQuery)."</p>";
        
        }
        if ($textQuery  != '') {
            echo "<p>Pencarian : ".$textQuery."</p>";
        }?> 
        <?php 
        if(isset($jsonObj->count)){
            echo "<h1>Showing ".$jsonObj->count." Jobs</h1>";
        } else {
            echo "<h1>Showing 0 Jobs</h1>";
        }
        ?>
        <?php echo $pageHTML ?> 
        </div>
    </div>

    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js' integrity='sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ' crossorigin='anonymous'></script>
</body>
</html>