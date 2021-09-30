<?php

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