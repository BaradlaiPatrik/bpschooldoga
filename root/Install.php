<?php

class Install
{
    public static function generateData(PDO $pdo)
    {
        // 1. TÁBLÁK LÉTREHOZÁSA (Ha még nem léteznének)
        try {
            $pdo->exec("CREATE TABLE IF NOT EXISTS subjects (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL UNIQUE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

            $pdo->exec("CREATE TABLE IF NOT EXISTS classes (
                id INT AUTO_INCREMENT PRIMARY KEY,
                grade INT NOT NULL,
                letter VARCHAR(5) NOT NULL,
                year INT NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

            $pdo->exec("CREATE TABLE IF NOT EXISTS students (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                birth_date DATE,
                class_id INT,
                FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

            $pdo->exec("CREATE TABLE IF NOT EXISTS marks (
                id INT AUTO_INCREMENT PRIMARY KEY,
                student_id INT,
                subject_id INT,
                mark INT NOT NULL,
                date DATE,
                FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
                FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        } catch (PDOException $e) {
            die("<p style='color:red;'>Hiba a táblák létrehozásakor: " . $e->getMessage() . "</p>");
        }

        // 2. ELLENŐRZÉS: Van-e már adat?
        $count = $pdo->query("SELECT COUNT(*) FROM classes")->fetchColumn();
        if ($count > 0) {
            echo "<p style='color:red;'>Az adatok már léteznek! (Ürítsd ki a táblákat, ha újra akarod generálni.)</p>";
            return;
        }

        // 3. TANTÁRGYAK
        $subjectNames = ['Matematika', 'Magyar nyelv és irodalom', 'Angol nyelv', 'Történelem', 'Fizika', 'Kémia', 'Biológia', 'Földrajz', 'Testnevelés'];
        foreach ($subjectNames as $name) {
            $stmt = $pdo->prepare("INSERT IGNORE INTO subjects (name) VALUES (:name)");
            $stmt->execute(['name' => $name]);
        }
        $subjects = $pdo->query("SELECT id FROM subjects")->fetchAll(PDO::FETCH_COLUMN);

        // 4. ADATGENERÁLÁSI LOGIKA
        $years = [2023, 2024, 2025];
        $letters = ['A', 'B', 'C', 'D', 'E'];
        $firstNames = ['Anna', 'Bence', 'Csilla', 'Dávid', 'Eszter', 'Fanni', 'Gábor', 'Hanna', 'István', 'Judit', 'Krisztián', 'Lilla', 'Máté', 'Nóra', 'Péter', 'Réka', 'Sándor', 'Tímea'];
        $lastNames = ['Kovács', 'Nagy', 'Szabó', 'Tóth', 'Varga', 'Horváth', 'Kiss', 'Molnár', 'Farkas', 'Papp', 'Simon', 'Balog'];

        $totalClasses = 0;
        foreach ($years as $year) {
            $numClasses = rand(4, 5);
            for ($c = 0; $c < $numClasses; $c++) {
                $grade = rand(9, 12);
                $letter = $letters[$c % count($letters)];

                $stmt = $pdo->prepare("INSERT INTO classes (grade, letter, year) VALUES (:grade, :letter, :year)");
                $stmt->execute(['grade' => $grade, 'letter' => $letter, 'year' => $year]);
                $classId = $pdo->lastInsertId();
                $totalClasses++;

                $numStudents = rand(12, 15);
                for ($s = 0; $s < $numStudents; $s++) {
                    $name = $lastNames[array_rand($lastNames)] . ' ' . $firstNames[array_rand($firstNames)];
                    $birth_date = date('Y-m-d', strtotime('-' . rand(14, 18) . ' years'));

                    $stmt = $pdo->prepare("INSERT INTO students (name, birth_date, class_id) VALUES (:name, :birth_date, :class_id)");
                    $stmt->execute(['name' => $name, 'birth_date' => $birth_date, 'class_id' => $classId]);
                    $studentId = $pdo->lastInsertId();

                    shuffle($subjects);
                    $numSub = min(5, count($subjects));
                    for ($subIdx = 0; $subIdx < $numSub; $subIdx++) {
                        $subId = $subjects[$subIdx];
                        $numGrades = rand(3, 4);
                        for ($g = 0; $g < $numGrades; $m = $g++) { // Javított ciklus
                            $mark = rand(1, 5);
                            $date = date('Y-m-d', strtotime($year . '-09-01 + ' . rand(0, 250) . ' days'));
                            $stmt = $pdo->prepare("INSERT INTO marks (student_id, subject_id, mark, date) VALUES (:student_id, :subject_id, :mark, :date)");
                            $stmt->execute(['student_id' => $studentId, 'subject_id' => $subId, 'mark' => $mark, 'date' => $date]);
                        }
                    }
                }
            }
        }

        echo "<p style='color:green;'><strong>Sikeresen legenerálva!</strong><br>3 tanév • " . $totalClasses . " osztály • Rengeteg diák és jegy beillesztve.</p>";
    }
}