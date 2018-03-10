<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Skill;
use App\User;
use App\Seeker;
use App\Company;
use App\JobOpening;
use App\Application;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function skills()
    {
        if(auth()->user()->user_type != 3)
            return back();
        
        $skills = Skill::all();
        
        $seeker_skills = \DB::table('seeker_skills')
            ->join('skills', 'seeker_skills.skill_id','=','skills.id')
            ->selectRaw('skill_id,name,category,AVG(rating) AS average_rating, COUNT(seeker_id) as num_seekers')
            ->whereRaw('seeker_skills.skill_id=skills.id')
            ->groupBy('skill_id')
            ->orderByRaw('average_rating DESC')->get();
        
        $job_skills = \DB::table('job_skills')
            ->join('skills', 'job_skills.skill_id','=','skills.id')
            ->selectRaw('skill_id,name,category,AVG(rating) AS average_rating, COUNT(job_id) as num_seekers')
            ->whereRaw('job_skills.skill_id=skills.id')
            ->groupBy('skill_id')
            ->orderByRaw('average_rating DESC')->get();

        return view('admin.skills', compact('skills', 'seeker_skills', 'job_skills'));
    }
    
    public function all_users()
    {
        if(auth()->user()->user_type != 3)
            return back();
        
        $users = User::orderBy('created_at','desc')->paginate(20);
        
        return view('admin.all_users', compact('users'));
    }
    
    public function seekers()
    {
        if(auth()->user()->user_type != 3)
            return back();
        
        $seekers = Seeker::orderBy('created_at','desc')->paginate(7);
        
        return view('admin.seekers', compact('seekers'));
    }
    
    public function companies()
    {
        if(auth()->user()->user_type != 3)
            return back();
        
        $companies = Company::orderBy('created_at','desc')->paginate(7);
        
        return view('admin.companies', compact('companies'));
    }
    
    public function job_openings()
    {
        if(auth()->user()->user_type != 3)
            return back();
        
        $jobs = JobOpening::orderBy('created_at','desc')->paginate(7);
        
        return view('admin.job_openings', compact('jobs'));
    }
    
    public function applications()
    {
        if(auth()->user()->user_type != 3)
            return back();
        
        $applications = Application::orderBy('created_at','desc')->paginate(7);
        
        return view('admin.applications', compact('applications'));
    }
    
    public function algorithms()
    {
        if(auth()->user()->user_type != 3)
            return back();
        
        if(request()->has('seeker'))
        {
            $algorithm = suggestedJobs(request()->seeker);
            $suggestedJobs = $algorithm[0];
            $time = $algorithm[1];
        }
        
        $seekers = Seeker::all();
        
        return view('admin.algorithms', compact('seekers', 'suggestedJobs', 'time'));
    }
    
    public function freeze($user_id)
    {
        if(auth()->user()->user_type != 3)
            return back();
        
        $user = User::find($user_id);
        $user->status = 1;
        $user->save();
        
        \Session::flash('freeze', "User ".$user->type->name."'s account has been frozen.");
        
        return back();
    }
    
    public function unfreeze($user_id)
    {
        if(auth()->user()->user_type != 3)
            return back();
        
        $user = User::find($user_id);
        $user->status = 0;
        $user->save();
        
        \Session::flash('freeze', "User ".$user->type->name."'s account has been unfrozen.");
        
        return back();
    }
}
