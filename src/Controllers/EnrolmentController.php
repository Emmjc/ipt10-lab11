<?php

namespace App\Controllers;

use App\Models\Course;
use App\Models\CourseEnrolment;
use App\Models\Student;
use App\Controllers\BaseController;

class EnrolmentController extends BaseController
{
    public function enrollmentForm()
    {
        $courseObj = new Course();
        $studentObj = new Student();

        $template = 'enrollment-form';
        $data = [
            'courses' => $courseObj->all(),
            'students' => $studentObj->all()
        ];

        $output = $this->render($template, $data);

        return $output;
    }

    public function enroll()
    {
        // Capture data from the form
        $course_code = $_POST['course_code'];
        $student_code = $_POST['student_code'];
        $enrollment_date = $_POST['enrollment_date']; 

        // Create an instance of the CourseEnrolment model
        $enrollment = new CourseEnrolment();

        // Enroll the student in the course (inserting into the database)
        $enrollment->enroll($student_code, $course_code);

        // Redirect to the course page after successful enrollment
        header("Location: /courses/{$course_code}");
        exit;  // Ensure no further code is executed
    }
}