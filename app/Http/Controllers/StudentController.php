<?php

namespace App\Http\Controllers;

use App\Jobs\ImportStudentsJob;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $file = $request->file('file');
        $data = array_map('str_getcsv', file($file));

        $students = [];
        foreach ($data as $index => $row) {
            if ($index == 0) continue;

            $email = $row[2];
            $phone = $row[3];
            // Validate each row's email uniqueness
            $rowValidator = Validator::make([
                'email' => $email,
                'phone' => $phone
            ], [
                'email' => 'email|unique:students,email',
                'phone' => 'numeric|unique:students,phone'
            ]);

            if ($rowValidator->fails()) {
                $errors[] = [
                    'row' => $index + 1,
                    'email' => $email,
                    'message' => $rowValidator->errors()->first()
                ];
                continue; // Skip this row
            }

            $students[] = [
                'name'  => $row[1] ?? '',
                'email' => $row[2] ?? '',
                'phone' => $row[3] ?? '',
                'standard' => $row[4] ?? '',
                'section' => $row[5] ?? ''
            ];
        }

        ImportStudentsJob::dispatch($students);

        return response()->json(['message' => 'Student data import started!']);
    }

    public function showUploadForm()
    {
        return view('import');
    }

    // view
    // Show the students list page
    public function viewData()
    {
        return view('viewData'); // Load view only
    }

    // Fetch students data as JSON
    public function getStudents()
    {
        //    return response()->json(Student::all()); // Return JSON data
        $perPage = 10; // Number of records per page
        $students = Student::paginate($perPage);

        return response()->json([
            'data' => $students->items(),
            'current_page' => $students->currentPage(),
            'last_page' => $students->lastPage(),
            'total' => $students->total(),
        ]);
    }

    //delete
    public function deleteStudent($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json(['message' => 'Student not found!', 'success' => false], 404);
        }

        $student->forceDelete();

        return response()->json(['message' => 'Student deleted successfully!', 'success' => true]);
    }
}
