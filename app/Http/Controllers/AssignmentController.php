<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Project;
use App\Models\Assignment;
use Illuminate\Http\Request;
use App\Events\AssignmentEvent;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::with('assignments.project')
            ->where('teammate', User::TEAMMATE_YES)
            ->orderBy('order')->get();

        $projects = Project::where('status', Project::STATUS_OPEN)->get();

        return Inertia::render('Assignment/Index', [
            'users' => $users,
            'projects' => $projects,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'user_id' => 'required',
            'project_id' => 'required'
        ], [
            'project_id.required' => 'The project field is required.'
        ]);

        Assignment::create($request->all());


        event(new AssignmentEvent(User::find($request->user_id)->name, 'just assigned to ' . Project::find($request->project_id)->name, 'assign'));

        return redirect()->back();
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
    public function edit(Assignment $assignment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Assignment $assignment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Assignment $assignment)
    {
        event(new AssignmentEvent($assignment->user->name, 'just unassigned from ' . $assignment->project->name, 'unassign'));
        
        $assignment->delete();

        return redirect()->back();
    }
}
