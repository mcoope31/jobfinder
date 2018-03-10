<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Application;
use App\JobOpening;
use App\User;
use App\Seeker;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        if(auth()->user()->user_type == 1)
        {
            $algorithm = suggestedJobs(auth()->user()->id);
            $suggestedJobs = $algorithm[0];
            $time = $algorithm[1];
            
            return view('seeker.dashboard', compact('suggestedJobs'));
        }
        else if(auth()->user()->user_type == 2)
        {
            $new_seekers = Seeker::latest()->limit(10)->get();
            $job_skills = \DB::table('job_skills')
                ->join('skills', 'job_skills.skill_id','=','skills.id')
                ->selectRaw('skill_id,name,category,AVG(rating) AS average_rating, COUNT(job_id) as num_seekers')
                ->whereRaw('job_skills.skill_id=skills.id')
                ->groupBy('skill_id','name','category')
                ->orderByRaw('average_rating DESC')->get();
            return view('company.dashboard', compact('new_seekers', 'job_skills'));
        }
        else if(auth()->user()->user_type == 3)
        {
            $users = User::orderBy('created_at','desc')->limit(10)->get();
            $jobs = JobOpening::orderBy('created_at','desc')->limit(10)->get();
            
            return view('admin.dashboard', compact('users', 'jobs'));
        }
    }
    
    public function applications()
    {
        //Is a seeker
        if(auth()->user()->user_type != 1)
            return back();
        
        $applications = Application::where('seeker_id',auth()->user()->id)->get();
        return view('seeker.applications', compact('applications'));
    }
    
    public function company_jobs()
    {
        //Is a company
        if(auth()->user()->user_type != 2)
            return back();
        
        $jobs = JobOpening::where('company_id',auth()->user()->id)->orderBy('created_at','desc')->get();
        return view('company.company_jobs', compact('jobs'));
    }
    
    public function delete_application($application_id)
    {
        //Is a seeker
        if(auth()->user()->user_type != 1)
            return back();
        
        $app = Application::where('id',$application_id)->first();

        if($app->seeker_id == auth()->user()->id)
        {
            Application::where('id',$application_id)->delete();
            
            \Session::flash('delete', "Application has been deleted.");
        
            return back();
        }
        else
            return back();
    }
}
