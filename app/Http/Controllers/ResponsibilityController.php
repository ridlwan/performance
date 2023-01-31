<?php

namespace App\Http\Controllers;

use App\Models\Responsibility;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class ResponsibilityController extends Controller
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

        $responsibilities = Responsibility::with('users')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%");
            })
            ->paginate($paginate);;

        $queryString = [
            'search' => $search,
            'paginate' => $paginate,
        ];

        $responsibilities->appends($queryString);

        return Inertia::render('Responsibility/Index', [
            'responsibilities' => $responsibilities,
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
        return Inertia::render('Responsibility/Create');
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
            'name' => 'required|unique:responsibilities,name'
        ]);
        
        Responsibility::create($request->all());

        return redirect('/responsibilities')->with('created', 'Responsibility created successfully');;
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
    public function edit(Responsibility $responsibility)
    {
        return Inertia::render('Responsibility/Edit', [
            'responsibility' => $responsibility,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Responsibility $responsibility)
    {
        $request->validate([
            'name' => 'required|unique:responsibilities,name,' . $responsibility->id . ',id'
        ]);

        $responsibility->update($request->all());

        return redirect('/responsibilities')->with('updated', 'Responsibility updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Responsibility $responsibility)
    {
        $responsibility->delete();

        return redirect('/responsibilities')->with('deleted', 'Responsibility deleted successfully');
    }
}
