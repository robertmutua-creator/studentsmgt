<?php

namespace Studentsmgt\Models;

use Studentsmgt\Models\Database;

class DatabaseSetup
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConn();
        $this->createAllTables();
    }

    private function createAllTables()
    {
        // 1. Schools
        $sql = "
            CREATE TABLE IF NOT EXISTS schools (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(150) NOT NULL,
                address VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        ";
        $this->conn->exec($sql);

        // 2. Users
        $sql = "
            CREATE TABLE IF NOT EXISTS users (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                school_id INT UNSIGNED,
                code VARCHAR(10) NULL,
                name VARCHAR(100) NOT NULL,
                email VARCHAR(100) NOT NULL UNIQUE,
                role ENUM('admin','teacher','parent') NOT NULL,
                password VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (school_id) REFERENCES schools(id)
            )
        ";
        $this->conn->exec($sql);

        // 3. Classes
        $sql = "
            CREATE TABLE IF NOT EXISTS classes (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                school_id INT UNSIGNED NOT NULL,
                name VARCHAR(50) NOT NULL,
                current_teacher_id INT UNSIGNED,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (school_id) REFERENCES schools(id),
                FOREIGN KEY (current_teacher_id) REFERENCES users(id)
            )
        ";
        $this->conn->exec($sql);

        // 4. Students
        $sql = "
            CREATE TABLE IF NOT EXISTS students (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                adm_no VARCHAR(50) NOT NULL,
                name VARCHAR(100) NOT NULL,
                date_of_birth DATE NOT NULL,
                current_class_id INT UNSIGNED,
                school_id INT UNSIGNED NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (current_class_id) REFERENCES classes(id),
                FOREIGN KEY (school_id) REFERENCES schools(id)
            )
        ";
        $this->conn->exec($sql);

        // 5. Parents
        $sql = "
            CREATE TABLE IF NOT EXISTS parents (
                user_id INT UNSIGNED PRIMARY KEY,
                phone_number VARCHAR(20),
                push_token VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id)
            )
        ";
        $this->conn->exec($sql);

        // 6. Student-Parent Mapping (new table)
        $sql = "
            CREATE TABLE IF NOT EXISTS student_parent_mapping (
                student_id INT UNSIGNED NOT NULL,
                parent_id INT UNSIGNED NOT NULL,
                PRIMARY KEY (student_id, parent_id),
                FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
                FOREIGN KEY (parent_id) REFERENCES users(id) ON DELETE CASCADE
            )
        ";
        $this->conn->exec($sql);

        // 7. AttendanceLogs
        $sql = "
            CREATE TABLE IF NOT EXISTS attendance_logs (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                student_id INT UNSIGNED NOT NULL,
                class_id INT UNSIGNED NOT NULL,
                teacher_id INT UNSIGNED NOT NULL,
                status ENUM('in','out') NOT NULL,
                message VARCHAR(255),
                timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (student_id) REFERENCES students(id),
                FOREIGN KEY (class_id) REFERENCES classes(id),
                FOREIGN KEY (teacher_id) REFERENCES users(id)
            )
        ";
        $this->conn->exec($sql);
    }
}
