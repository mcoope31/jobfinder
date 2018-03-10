<?php

function suggestedJobs($seeker_id)
{
    $time_start = microtime(true);
    
    $jobs = \App\JobOpening::all();
    $seeker = \App\Seeker::find($seeker_id);
    
    $suggested = array();
    
    foreach($jobs as $job)
    {
        if($job->status == 0) //Accepting Applications
        {
            //Check SKILLS
            $points = 0;
            $skills_matches = 0;
            foreach($job->job_skills as $job_skill)
            {
                $job_points = 6-($job_skill->rating);
                foreach($seeker->seeker_skills as $seeker_skill)
                {
                    if($job_skill->skill_id == $seeker_skill->skill_id)
                    {
                        $skills_matches++;
                        $seeker_points = (11-($seeker_skill->rating))/2;

                        if($seeker_points < $job_points)
                            $points += $seeker_points;
                        else
                            $points += $job_points;

                        break;
                    }
                }
            }
            $percentage = round(($points/15.0)*100,2); //Skills Percentage

            //CHECK EDUCATION
            $matched_education = checkEducation($job,$seeker);

            //CHECK EXPERIENCE
            $meets_experience = checkExperience($job,$seeker);

            //Get Total Match Percentage
            $total_percentage = getTotalMatchPercentage($percentage,$matched_education,$meets_experience);
            $total_percentage += 10; //curve
            if($total_percentage > 100) $total_percentage = 100;
            
            $suggested[$job->id] = array($total_percentage,$skills_matches,$matched_education,$meets_experience,$percentage);
        }
    }

    arsort($suggested);
    
    $time_end = microtime(true);
    $time = $time_end - $time_start;
    $time = round($time,4);

    return array($suggested,$time);
}

function getSeekerTotalExperience($seeker) //Return in DAYS
{
    $total_experience = 0;
    foreach($seeker->seeker_experience as $experience)
    {
        $total_experience += $experience->days_experience;
    }
    return $total_experience;
}

function getApplicants($job_id)
{
    $job = \App\JobOpening::where('id',$job_id)->first();

    $applications = \App\Application::where('job_id',$job_id)->get();
    
    $applicants = array();
    
    foreach($applications as $applicant)
    {
        //Check SKILLS
        $points = 0;
        $skills_matches = 0;
        foreach($job->job_skills as $job_skill)
        {
            $job_points = 6-($job_skill->rating);
            foreach($applicant->seeker->seeker_skills as $seeker_skill)
            {
                if($job_skill->skill_id == $seeker_skill->skill_id)
                {
                    $skills_matches++;
                    $seeker_points = (11-($seeker_skill->rating))/2;
                    
                    if($seeker_points < $job_points)
                        $points += $seeker_points;
                    else
                        $points += $job_points;

                    break;
                }
            }
        }
        $percentage = round(($points/15.0)*100,2); //Skills Percentage
        
        //CHECK EDUCATION
        $matched_education = checkEducation($job,$applicant->seeker);
        
        //CHECK EXPERIENCE
        $meets_experience = checkExperience($job,$applicant->seeker);
        
        //Get Total Match Percentage
        $total_percentage = getTotalMatchPercentage($percentage,$matched_education,$meets_experience);
        $total_percentage += 10; //curve
        
        $applicants[$applicant->id] = array($total_percentage,$skills_matches,$matched_education,$meets_experience, $percentage);
    }
    
    arsort($applicants);
    
    return $applicants;
}

function getTotalMatchPercentage($percentage, $matched_education, $meets_experience)
{
    $total_percentage = $percentage/2.0;
    if($matched_education) $total_percentage+=25;
    if($meets_experience) $total_percentage+=25;
    return $total_percentage;
}

function checkEducation($job, $seeker)
{
    $matched_education = false;
    if($job->education == 0)
    {
        $matched_education = true;
    }
    else
    {
        $seeker_education = $seeker->highest_education($seeker->user_id);
        if(isset($seeker_education))
        {
            $seeker_highest = $seeker_education->education_id;
            if($job->education <= $seeker_highest)
            {
                $matched_education = true;
            }
        }
    }
    return $matched_education;
}

function checkExperience($job, $seeker)
{
    $meets_experience = false;
    $total_experience = getSeekerTotalExperience($seeker);
    if($job->experience == 'Not necessary')
        $meets_experience = true;
    else
    {
        if($job->experience == '1-3 Years' && $total_experience >= 365) 
            $meets_experience = true;
        else if($job->experience == '3-5 Years' && $total_experience >= (365*3))
            $meets_experience = true;
        else if($job->experience == '5-10 Years' && $total_experience >= (365*5))
            $meets_experience = true;
        else if($job->experience == '10-20 Years' && $total_experience >= (365*10))
            $meets_experience = true;
    }
    return $meets_experience;
}

function localize_us_number($phone) 
{
  $numbers_only = preg_replace("/[^\d]/", "", $phone);
  return preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "$1-$2-$3", $numbers_only);
}