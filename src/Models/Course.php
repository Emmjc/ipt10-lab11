<?php

namespace App\Models;

use App\Models\BaseModel;
use \PDO;

class Course extends BaseModel
{
    // Table name
    protected $table = 'courses';

    // Course properties
    public $id;
    public $course_name;
    public $course_code;
    public $description;
    public $credits;

    /**
     * Get course code.
     * 
     * @return string
     */
    public function getCourseCode()
    {
        return $this->course_code;
    }

    /**
     * Get course name.
     * 
     * @return string
     */
    public function getCourseName()
    {
        return $this->course_name;
    }

    /**
     * Find a course by its course code.
     * 
     * @param string $course_code
     * @return Course|null
     */
    public function find($course_code)
    {
        $query = "SELECT * FROM {$this->table} WHERE course_code = :course_code LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':course_code', $course_code);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // Populate the course object with data
            $this->populate($result);
            return $this;
        }

        return null;
    }

    /**
     * Retrieve all courses from the database with the number of enrolled students.
     * 
     * @return array
     */
    public function all()
    {
            $query = "
            SELECT c.*, COUNT(e.course_code) AS enrolled_students
            FROM {$this->table} c
            LEFT JOIN course_enrolments e ON c.course_code = e.course_code
            GROUP BY c.id, c.course_name;
        ";
        $stmt = $this->db->query($query);
        
        $courses = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $course = new Course();
            $course->populate($row);
            $course->enrolled_students = (int) $row['enrolled_students']; // Add enrolled students count
            $courses[] = $course;
        }

        return $courses;
    }

    /**
     * Retrieve all enrollees for a specific course.
     * 
     * @param string $course_code
     * @return array
     */
    public function getEnrollees($course_code)
    {
        $query = "SELECT students.id, students.first_name, students.last_name, students.student_code 
                FROM course_enrolments 
                JOIN students ON course_enrolments.student_code = students.student_code 
                WHERE course_enrolments.course_code = :course_code";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':course_code', $course_code);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Returns the list of enrolled students with their IDs
    }


    /**
     * Populate the course object with data.
     * 
     * @param array $data
     * @return void
     */
    protected function populate(array $data)
    {
        $this->id = $data['id'];
        $this->course_name = $data['course_name'];
        $this->course_code = $data['course_code'];
        $this->description = $data['description'];
        $this->credits = $data['credits'];
    }
}