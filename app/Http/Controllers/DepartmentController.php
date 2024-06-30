<?php

namespace App\Http\Controllers;


use Illuminate\View\View;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:department-list|department-create|department-edit|department-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:department-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:department-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:department-delete', ['only' => ['destroy']]);
    }

    public function index(): View
    {
        $departments = Department::latest()->paginate(5);
        return view('departments.index', compact('departments'))->with('1', (request()->input('page', 1) - 1) * 5);
    }

    public function create(): View
    {
        return view('product.create');
    }

    public function store(Request $request): RedirectResponse
    {
        request()->validate([
            'department_name' => 'required',
            'status' => 'required',
        ]);

        Department::create($request->all());

        return redirect()->route('departments.index')->with('success', 'Department created successfully.');
    }

    public function show(Department $department): View
    {
        return view('departments.show', compact('department'));
    }

    public function edit(Department $department): View
    {
        return view('departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department): RedirectResponse
    {
        request()->validate([
            'department_name' => 'required',
            'status' => 'required',
        ]);

        $department->update($request->all());

        return redirect()->route('departments.index')->with('success', 'Department updated successfully');
    }

    public function destroy(Department $department): RedirectResponse
    {
        $department->delete();

        return redirect()->route('departments.index')->with('success', 'Department deleted successfully');
    }
}
