<?php

class MarksView
{
    public static function list($marks)
    {
        echo <<<HTML
            <h1>Érdemjegyek</h1>

            <p><a href="index.php?view=add-mark">Új jegy beírása</a></p>

            <table border="1" cellpadding="5">
                <tr>
                    <th>ID</th>
                    <th>Diák</th>
                    <th>Tantárgy</th>
                    <th>Jegy</th>
                    <th>Dátum</th>
                    <th>Műveletek</th>
                </tr>
HTML;

        foreach ($marks as $m) {
            $id           = $m['id'];
            $student_name = htmlspecialchars($m['student_name'], ENT_QUOTES, 'UTF-8');
            $subject_name = htmlspecialchars($m['subject_name'], ENT_QUOTES, 'UTF-8');
            $mark         = $m['mark'];
            $date         = date('Y. m. d.', strtotime($m['date']));

            echo <<<HTML
                <tr>
                    <td>{$id}</td>
                    <td>{$student_name}</td>
                    <td>{$subject_name}</td>
                    <td>{$mark}</td>
                    <td>{$date}</td>
                    <td>
                        <a href="index.php?view=edit-mark&id={$id}">Módosítás</a> |
                        <a href="index.php?view=marks&delete={$id}"
                           onclick="return confirm('Biztos törlöd a jegyet?')">Törlés</a>
                    </td>
                </tr>
HTML;
        }

        echo "</table>";
    }

    public static function addForm($students, $subjects)
    {
        $today = date('Y-m-d');

        echo <<<HTML
            <h1>Új jegy beírása</h1>

            <form method="post" action="index.php?view=marks">
                <label>Diák:</label><br>
                <select name="student_id" required>
                    <option value="">-- válassz diákot --</option>
HTML;

        foreach ($students as $s) {
            echo "<option value=\"{$s['id']}\">{$s['name']}</option>";
        }

        echo <<<HTML
                </select><br><br>

                <label>Tantárgy:</label><br>
                <select name="subject_id" required>
                    <option value="">-- válassz tantárgyat --</option>
HTML;

        foreach ($subjects as $sub) {
            echo "<option value=\"{$sub['id']}\">{$sub['name']}</option>";
        }

        echo <<<HTML
                </select><br><br>

                <label>Jegy (1-5):</label><br>
                <input type="number" name="mark" min="1" max="5" required><br><br>

                <label>Dátum:</label><br>
                <input type="date" name="date" value="{$today}" required><br><br>

                <button type="submit" name="add-mark">Beírás</button>
                <a href="index.php?view=marks">Mégse</a>
            </form>
HTML;
    }

    public static function editForm($mark, $students, $subjects)
    {
        $id          = $mark['id'];
        $student_id  = $mark['student_id'];
        $subject_id  = $mark['subject_id'];
        $mark_value  = $mark['mark'];
        $date        = $mark['date'];

        echo <<<HTML
            <h1>Jegy módosítása</h1>

            <form method="post" action="index.php?view=marks">
                <input type="hidden" name="id" value="{$id}">

                <label>Diák:</label><br>
                <select name="student_id" required>
                    <option value="">-- válassz diákot --</option>
HTML;

        foreach ($students as $s) {
            $selected = ($s['id'] == $student_id) ? ' selected' : '';
            echo "<option value=\"{$s['id']}\"{$selected}>{$s['name']}</option>";
        }

        echo <<<HTML
                </select><br><br>

                <label>Tantárgy:</label><br>
                <select name="subject_id" required>
                    <option value="">-- válassz tantárgyat --</option>
HTML;

        foreach ($subjects as $sub) {
            $selected = ($sub['id'] == $subject_id) ? ' selected' : '';
            echo "<option value=\"{$sub['id']}\"{$selected}>{$sub['name']}</option>";
        }

        echo <<<HTML
                </select><br><br>

                <label>Jegy (1-5):</label><br>
                <input type="number" name="mark" value="{$mark_value}" min="1" max="5" required><br><br>

                <label>Dátum:</label><br>
                <input type="date" name="date" value="{$date}" required><br><br>

                <button type="submit" name="update-mark">Mentés</button>
                <a href="index.php?view=marks">Mégse</a>
            </form>
HTML;
    }
}