<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Jira;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Report;
use App\Models\Project;
use App\Models\Support;
use App\Models\Progress;
use Carbon\CarbonPeriod;
use App\Models\Assignment;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Models\Responsibility;
use App\Exports\ActivitiesExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Report::class, 'report');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!Auth::user()->can('manage-report')) {
            $publihed = true;
        } else {
            $publihed = false;
        }
        
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
            ->when($publihed, function ($query) {
                $query->where('published', Report::PUBLISHED_YES);
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

        $allProjects = Project::where('status', Project::STATUS_OPEN)->get();

        $users = User::where('reported', User::REPORTED_YES)
            ->where('teammate', User::TEAMMATE_YES)
            ->orderBy('order')->get();

        return Inertia::render('Report/Create', [
            'projects' => $projects,
            'allProjects' => $allProjects,
            'users' => $users
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
            'reportProgress.*.sla' => 'required|numeric|min:0|max:100',
            'waiting_for_support' => 'required|numeric|min:0',
            'waiting_for_customer' => 'required|numeric|min:0',
            'waiting_for_partner' => 'required|numeric|min:0',
            'escalated' => 'required|numeric|min:0',
            'pending' => 'required|numeric|min:0',
            'in_progress' => 'required|numeric|min:0',
            'resolved' => 'required|numeric|min:0',
            'completed' => 'required|numeric|min:0',
            'closed' => 'required|numeric|min:0',
            'canceled' => 'required|numeric|min:0',
            'sla' => 'required|numeric|min:0|max:100',
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
            'reportProgress.*.sla.required' => 'The sla percentage field is required.',
            'reportProgress.*.sla.numeric' => 'The sla percentage field must be a number.',
            'reportProgress.*.sla.min' => 'The sla percentage field must be at least 0.',
            'reportProgress.*.sla.max' => 'The sla percentage field must not be greater than 100.',
        ]);

        DB::beginTransaction();

        $report = Report::create($request->only(['name', 'start', 'end']));

        foreach ($request->reportProgress as $progress) {
            $progress['report_id'] = $report->id;
            Progress::create($progress);
        }

        Support::create([
            'report_id' => $report->id,
            'waiting_for_support' => $request->waiting_for_support,
            'waiting_for_customer' => $request->waiting_for_customer,
            'waiting_for_partner' => $request->waiting_for_partner,
            'escalated' => $request->escalated,
            'pending' => $request->pending,
            'in_progress' => $request->in_progress,
            'resolved' => $request->resolved,
            'completed' => $request->completed,
            'closed' => $request->closed,
            'canceled' => $request->canceled,
            'sla' => $request->sla
        ]);

        foreach ($request->responsibilities as $responsibility) {
            $responsibilityData = Responsibility::create([
                'report_id' => $report->id,
                'user_id' => $responsibility['user']['id']
            ]);

            foreach ($responsibility['assignments'] as $assignment) {
                Assignment::create([
                    'responsibility_id' => $responsibilityData->id,
                    'project_id' => $assignment['project_id']
                ]);
            }
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
                    ->where('teammate', User::TEAMMATE_YES)
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

        $users = User::where('reported', User::REPORTED_YES)
            ->where('teammate', User::TEAMMATE_YES)
            ->orderBy('order')->get();
        
        $userSeries = User::where('reported', User::REPORTED_YES)
            ->where('teammate', User::TEAMMATE_YES)
            ->when($user != 'All', function ($query) use ($user) {
                $query->where('id', $user);
            })
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            })
            ->orderBy('order')->get();

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

        $reportedUsers = $users->pluck('name');

        $performanceHoursSeries = [];
        $performancePercentageSeries = [];
        
        $performanceHours = [];
        $performancePercentage = [];
        foreach ($users as $user) {
            $duration = Attendance::where('user_id', $user->id)
                ->whereDate('created_at', '>=', $startDate)
                ->whereDate('created_at', '<=', $endDate)->sum('duration');

                $hours = floor($duration / 60);
                $percentage = floor(($duration / (22 * 8 * 60)) * 100);
                
                array_push($performanceHours, $hours);
                array_push($performancePercentage, $percentage);
        }

        $performanceHoursData = [
            'name' => 'Hours',
            'data' => $performanceHours
        ];

        $performancePercentageData = [
            'name' => 'Percentage',
            'data' => $performancePercentage
        ];

        array_push($performanceHoursSeries, $performanceHoursData);
        array_push($performancePercentageSeries, $performancePercentageData);

        $supportSeries = [];
        $supportData = [];

        if ($report->support->waiting_for_support > 0) {
            array_push($supportSeries, $report->support->waiting_for_support);
            array_push($supportData, 'Waiting For Support');
        }

        if ($report->support->waiting_for_customer > 0) {
            array_push($supportSeries, $report->support->waiting_for_customer);
            array_push($supportData, 'Waiting For Customer');
        }

        if ($report->support->waiting_for_partner > 0) {
            array_push($supportSeries, $report->support->waiting_for_partner);
            array_push($supportData, 'Waiting For Partner');
        }

        if ($report->support->escalated > 0) {
            array_push($supportSeries, $report->support->escalated);
            array_push($supportData, 'Escalated');
        }

        if ($report->support->pending > 0) {
            array_push($supportSeries, $report->support->pending);
            array_push($supportData, 'Pending');
        }

        if ($report->support->in_progress > 0) {
            array_push($supportSeries, $report->support->in_progress);
            array_push($supportData, 'In Progress');
        }

        if ($report->support->resolved > 0) {
            array_push($supportSeries, $report->support->resolved);
            array_push($supportData, 'Resolved');
        }

        if ($report->support->completed > 0) {
            array_push($supportSeries, $report->support->completed);
            array_push($supportData, 'Completed');
        }

        if ($report->support->closed > 0) {
            array_push($supportSeries, $report->support->closed);
            array_push($supportData, 'Closed');
        }

        if ($report->support->canceled > 0) {
            array_push($supportSeries, $report->support->canceled);
            array_push($supportData, 'Canceled');
        }

        $supportSla = $report->support->sla;

        $responsibilities = Responsibility::with('user', 'assignments.project')
            ->where('report_id', $report->id)->get();

        $assignments = Assignment::whereIn('responsibility_id', $responsibilities->pluck('id'))->get();

        $resourceSeries = [];
        $resourceData = [];
        foreach ($assignments as $assignment) {
            if (!in_array($assignment->project->name, $resourceData)) {
                array_push($resourceSeries, $assignments->where('project_id', $assignment->project_id)->count());
                array_push($resourceData, $assignment->project->name);
            }
        }

        $slaAverage = 0;
        
        if ($report->progresses->count() > 0 && $report->progresses->sum('sla') > 0) {
            $slaAverage = $report->progresses->sum('sla') / $report->progresses->count();
        }

        return Inertia::render('Report/Show', [
            'report' => $report,
            'attendances' => $attendances,
            'users' => $users,
            'reportedUsers' => $reportedUsers,
            'filters' => $filters,
            'showChart' => $showChart,
            'dailySeries' => $dailySeries,
            'dates' => $dates,
            'projects' => $projects,
            'jiraSeries' => $jiraSeries,
            'developmentSeries' => $developmentSeries,
            'testingSeries' => $testingSeries,
            'overallSeries' => $overallSeries,
            'performanceHoursSeries' => $performanceHoursSeries,
            'performancePercentageSeries' => $performancePercentageSeries,
            'supportSeries' => $supportSeries,
            'supportData' => $supportData,
            'supportSla' => $supportSla,
            'responsibilities' => $responsibilities,
            'resourceSeries' => $resourceSeries,
            'resourceData' => $resourceData,
            'slaAverage' => $slaAverage,
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
        $report->load('progresses', 'support', 'responsibilities.user', 'responsibilities.assignments.project');

        $projects = Project::whereIn('id', $report->progresses->pluck('project_id'))->get();

        $allProjects = Project::whereHas('assignments', function ($query) use ($report) {
            $query->whereIn('responsibility_id', $report->responsibilities->pluck('id'));
        })
        ->orWhere('status', Project::STATUS_OPEN)->get();
        
        return Inertia::render('Report/Edit', [
            'report' => $report,
            'projects' => $projects,
            'allProjects' => $allProjects,
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
            'start' => 'required',
            'end' => 'required',
            'reportProgress.*.project_id' => 'required',
            'reportProgress.*.jira' => 'required|numeric|min:0|max:100',
            'reportProgress.*.development' => 'required|numeric|min:0|max:100',
            'reportProgress.*.testing' => 'required|numeric|min:0|max:100',
            'reportProgress.*.overall' => 'required|numeric|min:0|max:100',
            'reportProgress.*.sla' => 'required|numeric|min:0|max:100',
            'waiting_for_support' => 'required|numeric|min:0',
            'waiting_for_customer' => 'required|numeric|min:0',
            'waiting_for_partner' => 'required|numeric|min:0',
            'escalated' => 'required|numeric|min:0',
            'pending' => 'required|numeric|min:0',
            'in_progress' => 'required|numeric|min:0',
            'resolved' => 'required|numeric|min:0',
            'completed' => 'required|numeric|min:0',
            'closed' => 'required|numeric|min:0',
            'canceled' => 'required|numeric|min:0',
            'sla' => 'required|numeric|min:0|max:100',
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
            'reportProgress.*.sla.required' => 'The sla percentage field is required.',
            'reportProgress.*.sla.numeric' => 'The sla percentage field must be a number.',
            'reportProgress.*.sla.min' => 'The sla percentage field must be at least 0.',
            'reportProgress.*.sla.max' => 'The sla percentage field must not be greater than 100.',
        ]);

        DB::beginTransaction();

        $report->update($request->only(['name', 'start', 'end']));

        foreach ($request->reportProgress as $progress) {
            if ($progressData = Progress::find($progress['id'])) {
                $progressData->update($progress);
            }
        }

        $support = $report->support;

        $support->update([
            'waiting_for_support' => $request->waiting_for_support,
            'waiting_for_customer' => $request->waiting_for_customer,
            'waiting_for_partner' => $request->waiting_for_partner,
            'escalated' => $request->escalated,
            'pending' => $request->pending,
            'in_progress' => $request->in_progress,
            'resolved' => $request->resolved,
            'completed' => $request->completed,
            'closed' => $request->closed,
            'canceled' => $request->canceled,
            'sla' => $request->sla
        ]);

        $report->responsibilities()->delete();

        foreach ($request->responsibilities as $responsibility) {

            $responsibilityData = Responsibility::create([
                'report_id' => $report->id,
                'user_id' => $responsibility['user']['id']
            ]);

            foreach ($responsibility['assignments'] as $assignment) {
                Assignment::create([
                    'responsibility_id' => $responsibilityData->id,
                    'project_id' => $assignment['project_id']
                ]);
            }
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

    public function export(Report $report) 
    {
        return (new ActivitiesExport($report->start, $report->end))->download($report->name . '.xlsx');
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
