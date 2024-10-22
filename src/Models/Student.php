<?php

namespace App\Models;

use App\Models\BaseModel;
use \PDO;

class Student extends BaseModel
{
    // Table name
    protected $table = 'students';
    
    // Student properties
    public $id;
    public $student_code;
    public $first_name;
    public $last_name;
    public $email;
    public $date_of_birth;
    public $sex;

    /**
     * Get student code.
     * 
     * @return string
     */
    public function getStudentCode()
    {
        return $this->student_code;
    }

    /**
     * Get student email.
     * 
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get full name of the student.
     * 
     * @return string
     */
    public function getFullName()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Find a student by their student code.
     * 
     * @param string $student_code
     * @return Student|null
     */
    public function find($student_code)
    {
        $query = "SELECT * FROM {$this->table} WHERE student_code = :student_code LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':student_code', $student_code);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // Populate the student object with data
            $this->populate($result);
            return $this;
        }

        return null;
    }

    /**
     * Retrieve all students from the database.
     * 
     * @return array
     */
    public function all()
    {
        $query = "SELECT * FROM {$this->table}";
        $stmt = $this->db->query($query);
        
        $students = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $student = new Student();
            $student->populate($row);
            $students[] = $student;
        }

        return $students;
    }

    /**
     * Populate the student object with data.
     * 
     * @param array $data
     * @return void
     */
    protected function populate(array $data)
    {
        $this->id = $data['id'];
        $this->student_code = $data['student_code'];
        $this->first_name = $data['first_name'];
        $this->last_name = $data['last_name'];
        $this->email = $data['email'];
        $this->date_of_birth = $data['date_of_birth'];
        $this->sex = $data['sex'];
    }
}