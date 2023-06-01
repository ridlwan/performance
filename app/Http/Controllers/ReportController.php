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
use App\Models\Activity;
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
            'mandays' => 'required|numeric|min:1',
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
        ]);

        DB::beginTransaction();

        $report = Report::create($request->only(['name', 'start', 'end', 'mandays']));

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

        $attendances = Attendance::with('activities.project', 'user')
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
                        array_push($dailySeries[$key]['data'], (float) number_format(($attendance->duration / 60), 1));
                    } else {
                        array_push($dailySeries[$key]['data'], 0);
                    }
                }
            }
        }

        $reportedUsers = $users->pluck('name');

        $performanceHoursSeries = [];
        $performancePercentageSeries = [];
        $outOfOfficeSeries = [];
        $outSickSeries = [];

        $performanceHours = [];
        $performancePercentage = [];
        $outOfOffice = [];
        $outSick = [];

        foreach ($users as $user) {
            $duration = Attendance::where('user_id', $user->id)
                ->whereDate('created_at', '>=', $startDate)
                ->whereDate('created_at', '<=', $endDate)->sum('duration');

            $hours = floor($duration / 60);

            if ($report->mandays > 0) {
                $mandays = $report->mandays * 8;
            } else {
                $mandays = 22 * 8;
            }

            $percentage = floor(($duration / ($mandays * 60)) * 100);

            $outOfOfficeItem = Attendance::where('user_id', $user->id)
                ->where('status', '=', Attendance::STATUS_OUT_OFFICE)
                ->whereDate('created_at', '>=', $startDate)
                ->whereDate('created_at', '<=', $endDate)->count();
            
            $outSickItem = Attendance::where('user_id', $user->id)
                ->where('status', '=', Attendance::STATUS_OUT_SICK)
                ->whereDate('created_at', '>=', $startDate)
                ->whereDate('created_at', '<=', $endDate)->count();

            array_push($performanceHours, $hours);
            array_push($performancePercentage, $percentage);
            array_push($outOfOffice, $outOfOfficeItem);
            array_push($outSick, $outSickItem);
        }

        $performanceHoursData = [
            'name' => 'Hours',
            'data' => $performanceHours
        ];

        $performancePercentageData = [
            'name' => 'Percentage',
            'data' => $performancePercentage
        ];
        
        $outOfOfficeData = [
            'name' => 'Out Office',
            'data' => $outOfOffice
        ];
        
        $outSickData = [
            'name' => 'Out Sick',
            'data' => $outSick
        ];

        array_push($performanceHoursSeries, $performanceHoursData);
        array_push($performancePercentageSeries, $performancePercentageData);
        array_push($outOfOfficeSeries, $outOfOfficeData);
        array_push($outSickSeries, $outSickData);

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

        $activities = Activity::with('project', 'attendance')
            ->whereHas('project')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)->get();

        $inchargeData = [];
        foreach ($activities as $activity) {
            if (!in_array($activity->project->name, $inchargeData)) {
                array_push($inchargeData, $activity->project->name);
            }
        }

        $inchargeSeries = [];
        $totalInchargeSeries = 0;
        foreach ($inchargeData as $incharge) {
            $projectDuration = 0;
            foreach ($activities as $activity) {
                if ($activity->project->name == $incharge) {
                    $projectDuration += $activity->duration;
                }
            }

            array_push($inchargeSeries, (float) number_format(($projectDuration / 60), 1));
            $totalInchargeSeries += (float) number_format(($projectDuration / 60), 1);
        }

        foreach ($users as $user) {
            $responsibilities = [];
            foreach ($activities as $activity) {
                if (!in_array($activity->project->name, $responsibilities)) {
                    if ($activity->attendance->user_id == $user->id) {
                        array_push($responsibilities, $activity->project->name);
                    }
                }
            }

            $user['responsibilities'] = $responsibilities;
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
            'performanceHoursSeries' => $performanceHoursSeries,
            'performancePercentageSeries' => $performancePercentageSeries,
            'outOfOfficeSeries' => $outOfOfficeSeries,
            'outSickSeries' => $outSickSeries,
            'supportSeries' => $supportSeries,
            'supportData' => $supportData,
            'supportSla' => $supportSla,
            'inchargeSeries' => $inchargeSeries,
            'inchargeData' => $inchargeData,
            'totalInchargeSeries' => $totalInchargeSeries,
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
        $report->load('progresses', 'support');

        return Inertia::render('Report/Edit', [
            'report' => $report,
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
            'mandays' => 'required|numeric|min:1',
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
        ]);

        DB::beginTransaction();

        $report->update($request->only(['name', 'start', 'end', 'mandays']));

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

    public function project(Report $report, Request $request)
    {
        if ($request->user && $request->project) {
            if ($request->project == 'General') {
                $activities = Activity::whereHas('attendance', function ($query) use ($request) {
                    $query->whereHas('user', function ($query) use ($request) {
                        $query->where('id', $request->user);
                    });
                })
                    ->whereDoesntHave('project')
                    ->whereDate('created_at', '>=', $report->start)
                    ->whereDate('created_at', '<=', $report->end)->get();
            } else {
                $activities = Activity::whereHas('attendance', function ($query) use ($request) {
                    $query->whereHas('user', function ($query) use ($request) {
                        $query->where('id', $request->user);
                    });
                })
                    ->whereHas('project', function ($query) use ($request) {
                        $query->where('name', $request->project);
                    })
                    ->whereDate('created_at', '>=', $report->start)
                    ->whereDate('created_at', '<=', $report->end)->get();
            }

            return $activities;
        } else {
            $personnels = User::whereHas('attendances', function ($query) use ($report, $request) {
                $query->whereHas('activities', function ($query) use ($report, $request) {
                    $query->whereHas('project', function ($query) use ($request) {
                        $query->where('name', $request->project);
                    })
                        ->whereDate('created_at', '>=', $report->start)
                        ->whereDate('created_at', '<=', $report->end);
                });
            })->get();

            $personnelInchargeSeries = [];
            foreach ($personnels as $personnel) {
                $activities = Activity::whereHas('attendance', function ($query) use ($personnel) {
                    $query->whereHas('user', function ($query) use ($personnel) {
                        $query->where('id', $personnel->id);
                    });
                })
                    ->whereHas('project', function ($query) use ($request) {
                        $query->where('name', $request->project);
                    })
                    ->whereDate('created_at', '>=', $report->start)
                    ->whereDate('created_at', '<=', $report->end)->get();

                $personnel['duration'] = $activities->sum('duration');

                array_push($personnelInchargeSeries, (float) number_format(($activities->sum('duration') / 60), 1));
            }

            $responsibilities = Responsibility::whereHas('users', function ($query) use ($report, $request) {
                $query->whereHas('attendances', function ($query) use ($report, $request) {
                    $query->whereHas('activities', function ($query) use ($report, $request) {
                        $query->whereHas('project', function ($query) use ($request) {
                            $query->where('name', $request->project);
                        })
                            ->whereDate('created_at', '>=', $report->start)
                            ->whereDate('created_at', '<=', $report->end);
                    });
                });
            })->get();

            $responsibilityInchargeSeries = [];
            foreach ($responsibilities as $responsibility) {
                $activities = Activity::whereHas('attendance', function ($query) use ($responsibility) {
                    $query->whereHas('user', function ($query) use ($responsibility) {
                        $query->whereHas('responsibility', function ($query) use ($responsibility) {
                            $query->where('id', $responsibility->id);
                        });
                    });
                })
                    ->whereHas('project', function ($query) use ($request) {
                        $query->where('name', $request->project);
                    })
                    ->whereDate('created_at', '>=', $report->start)
                    ->whereDate('created_at', '<=', $report->end)->get();

                array_push($responsibilityInchargeSeries, (float) number_format(($activities->sum('duration') / 60), 1));
            }

            return response()->json([
                'personnels' => $personnels,
                'personnelInchargeData' => $personnels->pluck('name'),
                'personnelInchargeSeries' => $personnelInchargeSeries,
                'responsibilityInchargeData' => $responsibilities->pluck('name'),
                'responsibilityInchargeSeries' => $responsibilityInchargeSeries
            ]);
        }
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
