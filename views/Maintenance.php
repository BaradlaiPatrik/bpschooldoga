<?php

class MaintenanceView
{
    public static function show()
    {
        echo <<<HTML
            <h1>Karbantartás</h1>

            <form method="post" action="index.php?view=maintenance">
                <button type="submit" name="generate" 
                        onclick="return confirm('Biztosan legenerálod a teljes mintadatbázist?')
                </button>
            </form>

            <hr>

            <h2>Adattáblák kezelése</h2>
            <p><a href="index.php?view=subjects">Tantárgyak</a></p>
            <p><a href="index.php?view=classes">Osztályok</a></p>
            <p><a href="index.php?view=students">Diákok</a></p>
            <p><a href="index.php?view=marks">Jegyek</a></p>
HTML;
    }
}