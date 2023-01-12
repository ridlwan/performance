<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Report;
use Inertia\Inertia;
use Illuminate\Http\Request;

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
        $reports = Project::where('status', Project::STATUS_OPEN)->get();

        return Inertia::render('Report/Create', [
            'reports' => $reports
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
            'jira.*.report_id' => 'required',
            'jira.*.value' => 'required|numeric|min:0|max:100',
        ], [
            'jira.*.report_id.required' => 'The report field is required.',
            'jira.*.value.required' => 'The percentage field is required.',
            'jira.*.value.numeric' => 'The percentage field must be a number.',
            'jira.*.value.min' => 'The percentage field must be at least 0.',
            'jira.*.value.max' => 'The percentage field must not be greater than 100.',
        ]);

        
        Report::create($request->only(['name', 'start', 'end']));

        return redirect('/reports')->with('created', 'Report created successfully');;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Report $report)
    {
        $statuses = Report::PUBLISHED_ARRAY;

        return Inertia::render('Report/Edit', [
            'report' => $report,
            'statuses' => $statuses
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

        $report->update($request->all());

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
