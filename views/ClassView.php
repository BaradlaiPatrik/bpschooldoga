<?php

class ClassView
{
    public static function list($classes)
    {
        echo <<<HTML
            <h1>Osztályok</h1>

            <p><a href="index.php?view=add-class">Új osztály</a></p>

            <table border="1" cellpadding="5">
                <tr>
                    <th>ID</th>
                    <th>Évfolyam</th>
                    <th>Osztály betűjele</th>
                    <th>Tanév</th>
                    <th>Műveletek</th>
                </tr>
        HTML;
        foreach ($classes as $c) {
            $id    = $c['id'];
            $grade = $c['grade'];
            $letter = htmlspecialchars($c['letter'], ENT_QUOTES, 'UTF-8');
            $year  = $c['year'];
            echo <<<HTML
                <tr>
                    <td>{$id}</td>
                    <td>{$grade}.</td>
                    <td>{$letter}</td>
                    <td>{$year}</td>
                    <td>
                        <a href="index.php?view=edit-class&id={$id}">Módosítás</a> |
                        <a href="index.php?view=classes&delete={$id}"
                           onclick="return confirm('Biztos törlöd az osztályt?')">Törlés</a>
                    </td>
                </tr>
            HTML;
        }
        echo "</table>";
    }

    public static function addForm()
    {
        $currentYear = date('Y');
        echo <<<HTML
            <h1>Új osztály hozzáadása</h1>
            <form method="post" action="index.php?view=classes">
                <label>Tanév (pl. {$currentYear}):</label><br>
                <input type="number" name="year" value="{$currentYear}" min="2000" max="2100" required><br><br>
                <label>Évfolyam:</label><br>
                <input type="number" name="grade" min="1" max="12" required><br><br>
                <label>Osztály betűjele:</label><br>
                <input type="text" name="letter" maxlength="1" style="text-transform:uppercase;" required><br><br>
                <button type="submit" name="add-class">Hozzáadás</button>
                <a href="index.php?view=classes">Mégse</a>
            </form>
        HTML;
    }

    public static function editForm($class)
    {
        $id     = $class['id'];
        $grade  = $class['grade'];
        $letter = htmlspecialchars($class['letter'], ENT_QUOTES, 'UTF-8');
        $year   = $class['year'];
        echo <<<HTML
            <h1>Osztály módosítása</h1>
            <form method="post" action="index.php?view=classes">
                <input type="hidden" name="id" value="{$id}">
                <label>Tanév:</label><br>
                <input type="number" name="year" value="{$year}" min="2000" max="2100" required><br><br>
                <label>Évfolyam:</label><br>
                <input type="number" name="grade" value="{$grade}" min="1" max="12" required><br><br>
                <label>Osztály betűjele:</label><br>
                <input type="text" name="letter" value="{$letter}" maxlength="1" style="text-transform:uppercase;" required><br><br>
                <button type="submit" name="update-class">Mentés</button>
                <a href="index.php?view=classes">Mégse</a>
            </form>
        HTML;
    }
}