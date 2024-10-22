<?php

namespace App\Controllers;

use App\Models\Student;
use App\Controllers\BaseController;

class StudentController extends BaseController
{
    public function list()
    {
        $studentModel = new Student();
        $students = $studentModel->all(); // Fetch all students
        
        // Render the Mustache view and pass the students data
        echo $this->render('students.mustache', ['students' => $students]);
    }
}