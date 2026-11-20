<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $departments = Department::with('courses')->latest()->get();

        $courses = \App\Models\Course::with('departments')->latest()->get();

        $categories = \App\Models\Category::latest()->get();

        $activeTab = $request->query('tab', 'jurusan');

        return view('admin.master-data.index', compact(
            'departments',
            'courses',
            'categories',
            'activeTab'
        ));
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:20',
                'unique:departments,code',
            ],
            'name' => [
                'required',
                'string',
                'max:100',
                'unique:departments,name',
            ],
        ], [
            'code.required' => 'Kode jurusan wajib diisi.',
            'code.string' => 'Kode jurusan harus berupa teks.',
            'code.max' => 'Kode jurusan maksimal 20 karakter.',
            'code.unique' => 'Kode jurusan sudah digunakan.',

            'name.required' => 'Nama jurusan wajib diisi.',
            'name.string' => 'Nama jurusan harus berupa teks.',
            'name.max' => 'Nama jurusan maksimal 100 karakter.',
            'name.unique' => 'Nama jurusan sudah digunakan.',
        ]);

        Department::create($validated);

        return redirect()
            ->route('admin.master-data.index', ['tab' => 'jurusan'])
            ->with('success', 'Jurusan berhasil ditambahkan.');
    }

    public function update(Request $request, Department $department)
    {

        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('departments', 'code')->ignore($department->id),
            ],
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('departments', 'name')->ignore($department->id),
            ],
        ], [
            'code.required' => 'Kode jurusan wajib diisi.',
            'code.string' => 'Kode jurusan harus berupa teks.',
            'code.max' => 'Kode jurusan maksimal 20 karakter.',
            'code.unique' => 'Kode jurusan sudah digunakan.',

            'name.required' => 'Nama jurusan wajib diisi.',
            'name.string' => 'Nama jurusan harus berupa teks.',
            'name.max' => 'Nama jurusan maksimal 100 karakter.',
            'name.unique' => 'Nama jurusan sudah digunakan.',
        ]);


        $department->update($validated);

        return redirect()
            ->route('admin.master-data.index', ['tab' => 'jurusan'])
            ->with('success', 'Jurusan berhasil diperbarui.');
    }

    public function destroy(Department $department)
    {
        $department->delete();

        return redirect()
            ->route('admin.master-data.index', ['tab' => 'jurusan'])
            ->with('success', 'Jurusan berhasil dihapus.');
    }
}
