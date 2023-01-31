<?php

namespace App\Http\Controllers;

use App\Models\Responsibility;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserController extends Controller
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

        $users = User::with('roles', 'attendances', 'responsibility')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('position', 'LIKE', "%{$search}%");
            })
            ->orderBy('order')->paginate($paginate);;

        $queryString = [
            'search' => $search,
            'paginate' => $paginate,
        ];

        $users->appends($queryString);

        return Inertia::render('User/Index', [
            'users' => $users,
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
        $roles = Role::get();
        $responsibilities = Responsibility::get();
        $teammate = User::TEAMMATE_ARRAY;
        $reported = User::REPORTED_ARRAY;

        return Inertia::render('User/Create', [
            'roles' => $roles,
            'responsibilities' => $responsibilities,
            'teammate' => $teammate,
            'reported' => $reported
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
            'name' => 'required|unique:users,name',
            'position' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'role' => 'required',
            'teammate' => 'required',
            'reported' => 'required',
            'order' => 'required|numeric|min:0',
        ]);
        
        DB::beginTransaction();

        $data = $request->only([
            'name',
            'position',
            'responsibility_id',
            'email',
            'teammate',
            'reported',
            'order'
        ]);

        $data['password'] = bcrypt($request->password);

        $user = User::create($data);

        $user->assignRole($request->role);

        DB::commit();

        return redirect('/users')->with('created', 'Account created successfully');;
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
    public function edit(User $user)
    {
        $roles = Role::get();
        $responsibilities = Responsibility::get();
        $userRole = $user->getRoleNames()->first();
        $teammate = User::TEAMMATE_ARRAY;
        $reported = User::REPORTED_ARRAY;

        return Inertia::render('User/Edit', [
            'user' => $user,
            'roles' => $roles,
            'responsibilities' => $responsibilities,
            'userRole' => $userRole,
            'teammate' => $teammate,
            'reported' => $reported
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|unique:users,name,' . $user->id . ',id',
            'position' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id . ',id',
            'role' => 'required',
            'teammate' => 'required',
            'reported' => 'required',
            'order' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        $data = $request->only([
            'name',
            'position',
            'responsibility_id',
            'email',
            'teammate',
            'reported',
            'order'
        ]);

        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        $user->syncRoles($request->role);

        DB::commit();

        return redirect('/users')->with('updated', 'Account updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect('/users')->with('deleted', 'Account deleted successfully');
    }
}
