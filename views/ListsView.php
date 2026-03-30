<?php

class ListsView
{
    public static function selectForm($classes)
    {
        echo <<<HTML
            <h1>Listák</h1>
            <form method="get" action="index.php">
                <input type="hidden" name="view" value="lists">
                <label>Válassz osztályt:</label><br>
                <select name="class_id" required>
                    <option value="">-- válassz osztályt --</option>
HTML;

        foreach ($classes as $c) {
            echo "<option value=\"{$c['id']}\">{$c['display']}</option>";
        }

        echo <<<HTML
                </select><br><br>
                <button type="submit">Megjelenítés</button>
            </form>
HTML;
    }

    public static function classList($class, $students, $overall, $subjectAvgs)
    {
        $display = $class['year'] . ' ' . $class['grade'] . '. ' . $class['letter'];

        echo <<<HTML
            <h1>{$display} osztály</h1>
            <p><a href="index.php?view=lists">← Vissza</a></p>

            <h2>Osztály átlaga: {$overall}</h2>

            <h3>Tantárgyankénti átlagok</h3>
            <ul>
HTML;

        foreach ($subjectAvgs as $sa) {
            echo "<li>{$sa['subject_name']}: {$sa['avg']}</li>";
        }

        echo <<<HTML
            </ul>

            <h2>Diákok átlaga</h2>
            <table border="1" cellpadding="5">
                <tr>
                    <th>Név</th>
                    <th>Átlag</th>
                    <th>Tantárgyankénti átlagok</th>
                </tr>
HTML;

        foreach ($students as $st) {
            $name = htmlspecialchars($st['name'], ENT_QUOTES, 'UTF-8');
            $avg = $st['student_avg'] ?? '-';

            echo <<<HTML
                <tr>
                    <td>{$name}</td>
                    <td>{$avg}</td>
                    <td>
                        <ul style="margin:0; padding-left:15px;">
HTML;

            foreach ($st['subject_avgs'] as $sa) {
                echo "<li>{$sa['subject_name']}: {$sa['avg']}</li>";
            }

            echo <<<HTML
                        </ul>
                    </td>
                </tr>
HTML;
        }

        echo "</table>";
    }
}