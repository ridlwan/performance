<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Inertia\Inertia;
use Illuminate\Http\Request;

class ProjectController extends Controller
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

        $projects = Project::with('progresses')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%");
            })
            ->orderByDesc('id')->paginate($paginate);;

        $queryString = [
            'search' => $search,
            'paginate' => $paginate,
        ];

        $projects->appends($queryString);

        return Inertia::render('Project/Index', [
            'projects' => $projects,
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
        $types = Project::TYPE_ARRAY;
        $statuses = Project::STATUS_ARRAY;

        return Inertia::render('Project/Create', [
            'types' => $types,
            'statuses' => $statuses
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
            'name' => 'required|unique:projects,name',
            'type' => 'required',
            'status' => 'required'
        ]);

        Project::create($request->all());

        return redirect('/projects')->with('created', 'Project created successfully');;
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
    public function edit(Project $project)
    {
        $types = Project::TYPE_ARRAY;
        $statuses = Project::STATUS_ARRAY;

        return Inertia::render('Project/Edit', [
            'project' => $project,
            'types' => $types,
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
    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|unique:projects,name,' . $project->id . ',id',
            'type' => 'required',
            'status' => 'required'
        ]);

        $project->update($request->all());

        return redirect('/projects')->with('updated', 'Project updated successfully');
    }

    public function open(Project $project)
    {
        $project->status = Project::STATUS_OPEN;
        $project->save();

        return redirect('/projects')->with('created', 'Project opened successfully');
    }
    
    public function close(Project $project)
    {
        $project->status = Project::STATUS_CLOSE;
        $project->save();

        return redirect('/projects')->with('updated', 'Project closed successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return redirect('/projects')->with('deleted', 'Project deleted successfully');
    }
}
