<?php

class StudentsView
{
    public static function list($students)
    {
        echo <<<HTML
            <h1>Diákok</h1>

            <p><a href="index.php?view=add-student">Új diák hozzáadása</a></p>

            <table border="1" cellpadding="5">
                <tr>
                    <th>ID</th>
                    <th>Név</th>
                    <th>Születési dátum</th>
                    <th>Osztály</th>
                    <th>Műveletek</th>
                </tr>
HTML;

        foreach ($students as $s) {
            $id        = $s['id'];
            $name      = htmlspecialchars($s['name'], ENT_QUOTES, 'UTF-8');
            $birth     = $s['birth_date'] ? date('Y. m. d.', strtotime($s['birth_date'])) : '-';
            $class     = $s['grade'] ? $s['grade'] . '. ' . $s['letter'] . ' (' . $s['year'] . ')' : '-';

            echo <<<HTML
                <tr>
                    <td>{$id}</td>
                    <td>{$name}</td>
                    <td>{$birth}</td>
                    <td>{$class}</td>
                    <td>
                        <a href="index.php?view=edit-student&id={$id}">Módosítás</a> |
                        <a href="index.php?view=students&delete={$id}"
                           onclick="return confirm('Biztos törlöd a diákot?')">Törlés</a>
                    </td>
                </tr>
HTML;
        }

        echo "</table>";
    }

    public static function addForm($classes)
    {
        $currentYear = date('Y');
        $minYear = $currentYear - 21;
        $minDate = $minYear . '-01-01';
        $today   = date('Y-m-d');

        echo <<<HTML
            <h1>Új diák hozzáadása</h1>

            <form method="post" action="index.php?view=students">
                <label>Név:</label><br>
                <input type="text" name="name" required><br><br>

                <label>Születési dátum:</label><br>
                <input type="date" name="birth_date" min="{$minDate}" max="{$today}" required><br><br>

                <label>Osztály:</label><br>
                <select name="class_id">
                    <option value="">-- nincs --</option>
HTML;

        foreach ($classes as $c) {
            echo "<option value=\"{$c['id']}\">{$c['name']}</option>";
        }

        echo <<<HTML
                </select><br><br>

                <button type="submit" name="add-student">Hozzáadás</button>
                <a href="index.php?view=students">Mégse</a>
            </form>
HTML;
    }

    public static function editForm($student, $classes)
    {
        $id         = $student['id'];
        $name       = htmlspecialchars($student['name'], ENT_QUOTES, 'UTF-8');
        $birth_date = $student['birth_date'];
        $class_id   = $student['class_id'] ?? '';

        echo <<<HTML
            <h1>Diák módosítása</h1>

            <form method="post" action="index.php?view=students">
                <input type="hidden" name="id" value="{$id}">

                <label>Név:</label><br>
                <input type="text" name="name" value="{$name}" required><br><br>

                <label>Születési dátum:</label><br>
                <input type="date" name="birth_date" value="{$birth_date}" required><br><br>

                <label>Osztály:</label><br>
                <select name="class_id">
                    <option value="">-- nincs --</option>
HTML;

        foreach ($classes as $c) {
            $selected = ($c['id'] == $class_id) ? ' selected' : '';
            echo "<option value=\"{$c['id']}\"{$selected}>{$c['name']}</option>";
        }

        echo <<<HTML
                </select><br><br>

                <button type="submit" name="update-student">Mentés</button>
                <a href="index.php?view=students">Mégse</a>
            </form>
HTML;
    }
}