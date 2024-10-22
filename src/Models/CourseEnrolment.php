<?php

namespace App\Models;

use App\Models\BaseModel;
use \PDO;

class CourseEnrolment extends BaseModel
{
    // Table name
    protected $table = 'course_enrolments';

    /**
     * Enroll a student in a course.
     * 
     * @param string $student_code
     * @param string $course_code
     * @param string $enrollment_date Optional, if not provided uses current date
     * @return bool
     */
    public function enroll($student_code, $course_code, $enrollment_date = null)
    {
        // Default to current date if no enrollment date provided
        if (is_null($enrollment_date)) {
            $enrollment_date = date('Y-m-d');
        }

        // Insert the enrollment into the database
        $query = "INSERT INTO {$this->table} (student_code, course_code, enrolment_date) 
                  VALUES (:student_code, :course_code, :enrollment_date)";
        $stmt = $this->db->prepare($query);
        
        // Bind parameters
        $stmt->bindParam(':student_code', $student_code);
        $stmt->bindParam(':course_code', $course_code);
        $stmt->bindParam(':enrollment_date', $enrollment_date);
        
        // Execute the statement
        return $stmt->execute();
    }
}