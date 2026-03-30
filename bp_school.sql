
CREATE DATABASE IF NOT EXISTS bp_school
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE bp_school;

CREATE TABLE IF NOT EXISTS subjects (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100) NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tantárgyak';

CREATE TABLE IF NOT EXISTS classes (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    grade       TINYINT UNSIGNED NOT NULL COMMENT 'Évfolyam (1-12)',
    letter      CHAR(1) NOT NULL COMMENT 'Osztály betűjele',
    year        SMALLINT UNSIGNED NOT NULL COMMENT 'Tanév kezdőéve',
    
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    UNIQUE KEY uk_class_unique (grade, letter, year),
    INDEX idx_year_grade (year, grade)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Osztályok';

CREATE TABLE IF NOT EXISTS students (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100) NOT NULL COMMENT 'Teljes név',
    birth_date  DATE NOT NULL COMMENT 'Születési dátum',
    class_id    INT UNSIGNED NULL COMMENT 'Osztályhoz tartozás',
    
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_name (name),
    INDEX idx_class (class_id),
    
    FOREIGN KEY (class_id) 
        REFERENCES classes(id) 
        ON DELETE SET NULL 
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Diákok';

CREATE TABLE IF NOT EXISTS marks (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id  INT UNSIGNED NOT NULL,
    subject_id  INT UNSIGNED NOT NULL,
    mark        TINYINT UNSIGNED NOT NULL COMMENT '1-5 jegy',
    date        DATE NOT NULL COMMENT 'A jegy dátuma',
    
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_student (student_id),
    INDEX idx_subject (subject_id),
    INDEX idx_date (date),
    
    FOREIGN KEY (student_id) 
        REFERENCES students(id) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE,
        
    FOREIGN KEY (subject_id) 
        REFERENCES subjects(id) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Érdemjegyek';
