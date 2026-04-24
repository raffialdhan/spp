<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Fee;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with(['classRoom', 'fee']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('nisn', 'like', '%' . $request->search . '%')
                  ->orWhere('nis', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('class_id') && $request->class_id !== 'all') {
            $query->where('class_room_id', $request->class_id);
        }

        if ($request->filled('year') && $request->year !== 'all') {
            $query->where('academic_year', $request->year);
        }

        $students = $query->orderBy('name')->get();
        $classes = ClassRoom::orderBy('name')->get();
        $years = Student::select('academic_year')->distinct()->orderBy('academic_year', 'desc')->pluck('academic_year');

        return view('admin.students', compact('students', 'classes', 'years'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nisn' => 'required|string|size:10|unique:students,nisn',
            'nis' => 'required|string|size:8|unique:students,nis',
            'class_room_id' => 'required|exists:class_rooms,id',
            'academic_year' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'create_user' => 'nullable|boolean',
            'password' => 'required_if:create_user,1|nullable|string|min:6',
        ]);

        $student = Student::create([
            'name' => $validated['name'],
            'nisn' => $validated['nisn'],
            'nis' => $validated['nis'],
            'class_room_id' => $validated['class_room_id'],
            'academic_year' => $validated['academic_year'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
        ]);

        if ($request->has('create_user') && $request->create_user) {
            $user = User::create([
                'name' => $student->name,
                'username' => $student->nisn, // Default username is NISN
                'email' => $student->nisn . '@student.com', // Dummy email
                'password' => Hash::make($validated['password']),
                'role' => 'student',
            ]);
            $student->update(['user_id' => $user->id]);
        }

        return redirect()->route('admin.students')->with('success', 'Data siswa berhasil ditambahkan!');
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nisn' => ['required', 'string', 'size:10', Rule::unique('students', 'nisn')->ignore($student->id)],
            'nis' => ['required', 'string', 'size:8', Rule::unique('students', 'nis')->ignore($student->id)],
            'class_room_id' => 'required|exists:class_rooms,id',
            'academic_year' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $student->update($validated);

        return redirect()->route('admin.students')->with('success', 'Data siswa berhasil diperbarui!');
    }

    public function destroy(Student $student)
    {
        if ($student->user) {
            $student->user->delete();
        }
        $student->delete();

        return redirect()->route('admin.students')->with('success', 'Data siswa berhasil dihapus!');
    }
}
