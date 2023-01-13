<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Jira;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Report;
use App\Models\Project;
use Carbon\CarbonPeriod;
use App\Models\Attendance;
use App\Models\Progress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        if ($request->get('paginate')) {
            $paginate = $request->get('paginate');
        } else {
            $paginate = 10;
        }

        $filters = $request->only(['search']);
        $filters['paginate'] = $paginate;

        $reports = Report::when($search, function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%");
            })
            ->orderByDesc('id')->paginate($paginate);;

        $queryString = [
            'search' => $search,
            'paginate' => $paginate,
        ];

        $reports->appends($queryString);

        return Inertia::render('Report/Index', [
            'reports' => $reports,
            'filters' => $filters,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $projects = Project::where('type', Project::TYPE_PROJECT)
            ->where('status', Project::STATUS_OPEN)->get();

        return Inertia::render('Report/Create', [
            'projects' => $projects
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:reports,name',
            'start' => 'required',
            'end' => 'required',
            'reportProgress.*.project_id' => 'required',
            'reportProgress.*.jira' => 'required|numeric|min:0|max:100',
            'reportProgress.*.development' => 'required|numeric|min:0|max:100',
            'reportProgress.*.testing' => 'required|numeric|min:0|max:100',
            'reportProgress.*.overall' => 'required|numeric|min:0|max:100',
        ], [
            'reportProgress.*.project_id.required' => 'The project field is required.',
            'reportProgress.*.jira.required' => 'The jira percentage field is required.',
            'reportProgress.*.jira.numeric' => 'The jira percentage field must be a number.',
            'reportProgress.*.jira.min' => 'The jira percentage field must be at least 0.',
            'reportProgress.*.jira.max' => 'The jira percentage field must not be greater than 100.',
            'reportProgress.*.development.required' => 'The development percentage field is required.',
            'reportProgress.*.development.numeric' => 'The development percentage field must be a number.',
            'reportProgress.*.development.min' => 'The development percentage field must be at least 0.',
            'reportProgress.*.development.max' => 'The development percentage field must not be greater than 100.',
            'reportProgress.*.testing.required' => 'The testing percentage field is required.',
            'reportProgress.*.testing.numeric' => 'The testing percentage field must be a number.',
            'reportProgress.*.testing.min' => 'The testing percentage field must be at least 0.',
            'reportProgress.*.testing.max' => 'The testing percentage field must not be greater than 100.',
            'reportProgress.*.overall.required' => 'The overall percentage field is required.',
            'reportProgress.*.overall.numeric' => 'The overall percentage field must be a number.',
            'reportProgress.*.overall.min' => 'The overall percentage field must be at least 0.',
            'reportProgress.*.overall.max' => 'The overall percentage field must not be greater than 100.',
        ]);

        DB::beginTransaction();

        $report = Report::create($request->only(['name', 'start', 'end']));

        foreach ($request->reportProgress as $progress) {
            $progress['report_id'] = $report->id;
            Progress::create($progress);
        }

        DB::commit();

        return redirect('/reports')->with('created', 'Report created successfully');;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Report $report, Request $request)
    {
        $search = $request->get('search');
        $user = $request->get('user');

        if ($request->get('user')) {
            $user = $request->get('user');
        } else {
            $user = 'All';
        }

        $startDate = $report->start;
        $endDate = $report->end;

        if ($request->get('paginate')) {
            $paginate = $request->get('paginate');
        } else {
            $paginate = 10;
        }

        $filters = $request->only(['search']);
        $filters['startDate'] = $startDate;
        $filters['endDate'] = $endDate;
        $filters['user'] = $user;
        $filters['paginate'] = $paginate;

        $attendances = Attendance::with('activities', 'user')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('user', function ($subQuery) use ($search) {
                    $subQuery->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%");
                });
            })
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->whereHas('user', function ($query) use ($user) {
                $query->where('reported', User::REPORTED_YES)
                    ->when($user != 'All', function ($query) use ($user) {
                        $query->where('id', $user);
                    });
            })
            ->orderByDesc('id')->paginate($paginate);

        $queryString = [
            'search' => $search,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'user' => $user,
            'paginate' => $paginate,
        ];

        $attendances->appends($queryString);

        $users = User::where('reported', User::REPORTED_YES)->get();

        $userSeries = User::where('reported', User::REPORTED_YES)
            ->when($user != 'All', function ($query) use ($user) {
                $query->where('id', $user);
            })
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            })->get();

        $dailySeries = [];
        foreach ($userSeries as $user) {
            $data = [
                'name' => $user->name,
                'data' => []
            ];
            array_push($dailySeries, $data);
        }

        $dates = [];
        if ($startDate == $endDate || $startDate > $endDate || $attendances->count() < 1) {
            $showChart = false;
        } else {
            $showChart = true;

            $range = CarbonPeriod::create($startDate, $endDate);
            foreach ($range as $period) {
                array_push($dates, $period->format('D d M y'));

                foreach ($dailySeries as $key => $value) {
                    if ($attendance = Attendance::where('user_id', $userSeries[$key]->id)->whereDate('created_at', $period)->first()) {
                        array_push($dailySeries[$key]['data'], number_format((float) ($attendance->duration / 60), 1));
                    } else {
                        array_push($dailySeries[$key]['data'], 0);
                    }
                }
            }
        }

        $projects = $report->progresses->pluck('project.name');
        $reportId = $report->progresses->pluck('project_id');

        $reports = Report::where('id', '<=', $report->id)
            ->orderByDesc('id')->take(2)->get();

        $reportsReverse = array_reverse($reports->toArray());
        
        $jiraSeries = [];
        $developmentSeries = [];
        $testingSeries = [];
        $overallSeries = [];
        foreach ($reportsReverse as $reportData) {
            $jira = [];
            $development = [];
            $testing = [];
            $overall = [];
            foreach ($reportId as $id) {
                if ($progress = Progress::where('report_id', $reportData['id'])->where('project_id', $id)->first()) {
                    array_push($jira, $progress->jira);
                    array_push($development, $progress->development);
                    array_push($testing, $progress->testing);
                    array_push($overall, $progress->overall);
                } else {
                    array_push($jira, 0);
                    array_push($development, 0);
                    array_push($testing, 0);
                    array_push($overall, 0);
                }
            }

            $jiraData = [
                'name' => $reportData['name'],
                'data' => $jira
            ];

            array_push($jiraSeries, $jiraData);

            $developmentData = [
                'name' => $reportData['name'],
                'data' => $development
            ];

            array_push($developmentSeries, $developmentData);
            
            $testingData = [
                'name' => $reportData['name'],
                'data' => $testing
            ];
            
            array_push($testingSeries, $testingData);

            $overallData = [
                'name' => $reportData['name'],
                'data' => $overall
            ];

            array_push($overallSeries, $overallData);
        }

        return Inertia::render('Report/Show', [
            'report' => $report,
            'attendances' => $attendances,
            'users' => $users,
            'filters' => $filters,
            'showChart' => $showChart,
            'dailySeries' => $dailySeries,
            'dates' => $dates,
            'projects' => $projects,
            'jiraSeries' => $jiraSeries,
            'developmentSeries' => $developmentSeries,
            'testingSeries' => $testingSeries,
            'overallSeries' => $overallSeries,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Report $report)
    {
        $projects = Project::where('status', Project::STATUS_OPEN)->get();

        return Inertia::render('Report/Edit', [
            'projects' => $projects
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Report $report)
    {
        $request->validate([
            'name' => 'required|unique:reports,name,' . $report->id . ',id',
            'status' => 'required'
        ]);

        $request->validate([
            'name' => 'required|unique:reports,name',
            'start' => 'required',
            'end' => 'required',
            'jira.*.project_id' => 'required',
            'jira.*.value' => 'required|numeric|min:0|max:100',
        ], [
            'jira.*.project_id.required' => 'The project field is required.',
            'jira.*.value.required' => 'The percentage field is required.',
            'jira.*.value.numeric' => 'The percentage field must be a number.',
            'jira.*.value.min' => 'The percentage field must be at least 0.',
            'jira.*.value.max' => 'The percentage field must not be greater than 100.',
        ]);

        DB::beginTransaction();

        $report->update($request->only(['name', 'start', 'end']));

        foreach ($request->jira as $jira) {
            $jira['report_id'] = $report->id;
            Jira::create($jira);
        }

        DB::commit();

        return redirect('/reports')->with('updated', 'Report updated successfully');
    }
    
    public function publish(Report $report)
    {
        $report->published = Report::PUBLISHED_YES;
        $report->save();

        return redirect('/reports')->with('created', 'Report published successfully');
    }
    
    public function unpublish(Report $report)
    {
        $report->published = Report::PUBLISHED_NO;
        $report->save();

        return redirect('/reports')->with('updated', 'Report unpublished successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Report $report)
    {
        $report->delete();

        return redirect('/reports')->with('deleted', 'Report deleted successfully');
    }
}