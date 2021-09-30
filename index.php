<?php
include "function.php";
include "connection.php";

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
    <link rel="stylesheet" href="style.css">
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