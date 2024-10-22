<?php

namespace App\Controllers;

use App\Models\Course;
use App\Controllers\BaseController;
use Fpdf\Fpdf;

class CourseController extends BaseController
{
    public function list()
    {
        $obj = new Course();
        $courses = $obj->all();

        $template = 'courses';
        $data = [
            'items' => $courses // This will include enrolled_students
        ];

        $output = $this->render($template, $data);

        return $output;
    }

    public function viewCourse($course_code)
    {
        $courseObj = new Course();
        $course = $courseObj->find($course_code); // Find the specific course by code
        $enrollees = [];
    
        if ($course) {
            $enrollees = $courseObj->getEnrollees($course_code); // Fetch enrollees for the course
        }
    
        $template = 'single-course';
        $data = [
            'course' => $course,
            'enrollees' => $enrollees
        ];
    
        $output = $this->render($template, $data);
    
        return $output;
    }

    public function exportCourseEnrollees($course_code)
    {
        // Fetch course details
        $courseObj = new Course();
        $course = $courseObj->find($course_code);

        // Fetch enrolled students
        $enrollees = $courseObj->getEnrollees($course_code);

        // Create PDF
        $pdf = new FPDF();
        $pdf->AddPage();

        // Set title and course information
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Course Information', 0, 1, 'C');
        $pdf->Ln(10); // Line break

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(40, 10, 'Course Code: ', 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $course->getCourseCode(), 0, 1);
        
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(40, 10, 'Course Name: ', 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $course->getCourseName(), 0, 1);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(40, 10, 'Description: ', 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 10, $course->description); // Use MultiCell for multiline

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(40, 10, 'Credits: ', 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $course->credits, 0, 1);

        // Enrolled Students
        $pdf->Ln(10); // Line break
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Enrolled Students', 0, 1, 'C');
        $pdf->Ln(5); // Line break

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(20, 10, 'ID', 1);
        $pdf->Cell(50, 10, 'First Name', 1);
        $pdf->Cell(50, 10, 'Last Name', 1);
        $pdf->Cell(30, 10, 'Student Code', 1);
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 12);
        foreach ($enrollees as $student) {
            $pdf->Cell(20, 10, $student['id'], 1);
            $pdf->Cell(50, 10, $student['first_name'], 1);
            $pdf->Cell(50, 10, $student['last_name'], 1);
            $pdf->Cell(30, 10, $student['student_code'], 1);
            $pdf->Ln();
        }

        // Output the PDF
        $pdf->Output('D', "Course_{$course->getCourseCode()}_Enrollees.pdf");
        exit;
    }
}
