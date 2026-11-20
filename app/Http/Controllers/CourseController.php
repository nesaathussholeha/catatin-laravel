<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('departments')
            ->latest()
            ->get();

        $departments = Department::orderBy('name')->get();

        return view('admin.master-data.index', compact(
            'courses',
            'departments'
        ));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:20',
                'unique:courses,code',
            ],
            'name' => [
                'required',
                'string',
                'max:100',
            ],
            'departments' => [
                'required',
                'array',
                'min:1',
            ],
            'departments.*' => [
                'required',
                'exists:departments,id',
            ],
        ], [
            'code.required' => 'Kode mata kuliah wajib diisi.',
            'code.string' => 'Kode mata kuliah harus berupa teks.',
            'code.max' => 'Kode mata kuliah maksimal 20 karakter.',
            'code.unique' => 'Kode mata kuliah sudah digunakan.',

            'name.required' => 'Nama mata kuliah wajib diisi.',
            'name.string' => 'Nama mata kuliah harus berupa teks.',
            'name.max' => 'Nama mata kuliah maksimal 100 karakter.',

            'departments.required' => 'Pilih minimal satu jurusan.',
            'departments.array' => 'Format pilihan jurusan tidak valid.',
            'departments.min' => 'Pilih minimal satu jurusan.',
            'departments.*.exists' => 'Jurusan yang dipilih tidak valid.',
        ]);

        $course = Course::create([
            'code' => $validated['code'],
            'name' => $validated['name'],
        ]);

        $course->departments()->sync($validated['departments']);

        return redirect()
            ->route('admin.master-data.index', ['tab' => 'matakuliah'])
            ->with('success', 'Mata kuliah berhasil ditambahkan.');
    }

    public function show(Course $course)
    {
        //
    }

    public function edit(Course $course)
    {
        //
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('courses', 'code')->ignore($course->id),
            ],
            'name' => [
                'required',
                'string',
                'max:100',
            ],
            'departments' => [
                'required',
                'array',
                'min:1',
            ],
            'departments.*' => [
                'required',
                'exists:departments,id',
            ],
        ], [
            'code.required' => 'Kode mata kuliah wajib diisi.',
            'code.string' => 'Kode mata kuliah harus berupa teks.',
            'code.max' => 'Kode mata kuliah maksimal 20 karakter.',
            'code.unique' => 'Kode mata kuliah sudah digunakan.',

            'name.required' => 'Nama mata kuliah wajib diisi.',
            'name.string' => 'Nama mata kuliah harus berupa teks.',
            'name.max' => 'Nama mata kuliah maksimal 100 karakter.',

            'departments.required' => 'Pilih minimal satu jurusan.',
            'departments.array' => 'Format pilihan jurusan tidak valid.',
            'departments.min' => 'Pilih minimal satu jurusan.',
            'departments.*.exists' => 'Jurusan yang dipilih tidak valid.',
        ]);

        $course->update([
            'code' => $validated['code'],
            'name' => $validated['name'],
        ]);

        $course->departments()->sync($validated['departments']);

        return redirect()
            ->route('admin.master-data.index', ['tab' => 'matakuliah'])
            ->with('success', 'Mata kuliah berhasil diperbarui.');
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()
            ->route('admin.master-data.index', ['tab' => 'matakuliah'])
            ->with('success', 'Mata kuliah berhasil dihapus.');
    }
}
