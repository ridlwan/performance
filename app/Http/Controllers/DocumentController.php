<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Document;
use App\Models\Description;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $pagination = $request->get('pagination');

        if ($request->get('order') && $request->get('by')) {
            $order = $request->get('order');
            $by = $request->get('by');
        } else {
            $order = 'id';
            $by = 'desc';
        }
        
        if ($request->get('pagination')) {
            $pagination = $request->get('pagination');
        } else {
            $pagination = 10;
        }

        $filters = $request->only(['search']);
        $filters['pagination'] = $pagination;
        $filters['order'] = $order;
        $filters['by'] = $by;

        $documents = Document::when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('sender_nopeg', 'LIKE', "%{$search}%")
                            ->orWhere('sender_name', 'LIKE', "%{$search}%");
                });
            })
            ->when(($order && $by), function ($query) use ($order, $by) {
                $query->orderBy($order, $by);
            })->paginate($pagination);
        
        $queryString = [
            'search' => $search,
            'pagination' => $pagination,
            'order' => $order,
            'by' => $by,
        ];

        $documents->appends($queryString);

        return Inertia::render('Document/Index', [
            'documents' => $documents,
            'filters' => $filters
        ]);
    }
    
    public function create()
    {
        return Inertia::render('Document/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'sender_nopeg' => 'required',
            'sender_name' => 'required',
            'sender_unit' => 'required',
            'sender_date' => 'required',
            'receiver_unit' => 'required',
        ]);

        $data = $request->all();
        $data['created_by'] = Auth::user()->name;

        $document = Document::create($data);

        if ($request->descriptions) {
            foreach ($request->descriptions as $description) {
                Description::create([
                    'document_id' => $document->id,
                    'description' => $description['description'],
                    'remark' => $description['remark'],
                    'created_by' => Auth::user()->name
                ]);
            }
        }
        
        return redirect()->route('documents.index')->with('message', 'Data created successfully');
    }

    public function show($id)
    {
        //
    }

    public function edit()
    {
        return Inertia::render('Document/Edit');
    }

    public function update(Request $request, )
    {
        

        return redirect()->route('vehicle-types.index')->with('message', 'Data updated successfully');
    }

    public function destroy()
    {

        return redirect()->route('vehicle-types.index')->with('message', 'Data deleted successfully');
    }
}
