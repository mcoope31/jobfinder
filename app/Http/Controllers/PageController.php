<?php

namespace App\Http\Controllers;

use App\Mail\ReportMessage;
use App\Mail\ContactMessage;
use Illuminate\Http\Request;
use App\Company;
use App\JobOpening;
use App\Seeker;
use App\User;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
    public function index()
    {
        $new_jobs = JobOpening::latest()->limit(5)->get();
        $new_seekers = Seeker::latest()->limit(5)->get();
        $new_companies = Company::latest()->limit(5)->get();
        return view('pages.index', compact('new_jobs','new_seekers', 'new_companies'));
    }
    
    public function companies()
    {
        if(request()->has('order') && request()->has('sort'))
        {
            if(request()->order == "Joined") $field = 'created_at';
            else if(request()->order == "Founded") $field = 'founded';
            else if(request()->order == "Employees") $field = "size";
            else $field = 'created_at';
            $companies = Company::orderBy($field,request()->sort)->paginate(5)
                ->appends([
                    'order' => request('order'),
                    'sort' => request('sort')
                ]);
        }
        else if(request()->has('search') && request()->has('search_field'))
        {
            if(request()->search_field == "Name") $field = "name";
            else $field = "name";
            $search = '%'.request()->search.'%';
            $companies = Company::where($field,'like',$search)->paginate(5)
                ->appends([
                    'search' => request('search'),
                    'search_field' => request('search_field')
                ]);
        }
        else
            $companies = Company::orderBy('created_at', 'desc')->paginate(5);

        return view('pages.companies', compact('companies'));
    }
    
    public function job_openings()
    {
        if(request()->has('order') && request()->has('sort'))
        {
            if(request()->order == "Created") $field = 'created_at';
            else if(request()->order == "Salary") $field = 'salary';
            else if(request()->order == "Openings") $field = "openings";
            else $field = 'created_at';
            $jobs = JobOpening::where('status',0)->orderBy($field,request()->sort)->paginate(5)
                ->appends([
                    'order' => request('order'),
                    'sort' => request('sort')
                ]);
        }
        else if(request()->has('search') && request()->has('search_field'))
        {
            if(request()->search_field == "Title") $field = "title";
            else $field = "title";
            $search = '%'.request()->search.'%';
            $jobs = JobOpening::where('status',0)->where($field,'like',$search)->paginate(5)
                ->appends([
                    'search' => request('search'),
                    'search_field' => request('search_field')
                ]);
        }
        else
            $jobs = JobOpening::where('status',0)->orderBy('created_at', 'desc')->paginate(5);
        
        return view('pages.job_openings', compact('jobs'));
    }
    
    public function about()
    {
        return view('pages.about');
    }
    
    public function contact()
    {
        return view('pages.contact');
    }

    public function report_user($user_id)
    {
        $info=User::find($user_id);
        return view('pages.report_user',compact('info','user_id'));
    }
    public function report_job($job_id)
    {
        $info=JobOpening::find($job_id);
        return view('pages.report_job',compact('info','job_id'));
    }

    public function report_user_sent(Request $request, $user_id)
    {
        Mail::send(new ReportMessage($request, $user_id, 'user'));
        return view('pages.report_sent');
    }

    public function report_job_sent(Request $request, $job_id)
    {
        Mail::send(new ReportMessage($request, $job_id, 'job'));
        return view('pages.report_sent');
    }
  
    public function contact_sent(Request $request)
    {
        Mail::send(new ContactMessage($request));
        return view('pages.contact_sent');
    }
}
