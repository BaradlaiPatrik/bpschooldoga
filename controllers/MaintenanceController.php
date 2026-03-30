<?php
require_once 'root/Install.php';
class MaintenanceController
{
    private PDO $pdo;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    public function handleRequest(string $view)
    {
        $message = "";
        // Adatgenerálás kezelése
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate'])) {
            Install::generateData($this->pdo);
            $message = "Az adatok sikeresen legenerálva!";
        }
        // Itt közvetlenül kiírjuk a HTML-t, hogy ne keressen külső View osztályt
        echo "<h2>Karbantartás</h2>";
        
        if ($message) {
            echo "<p style='color:green; font-weight:bold;'>$message</p>";
        }
        echo '
        <div style="margin-bottom: 20px; padding: 15px; border: 1px solid #ccc; background: #f9f9f9;">
            <h3>inicializálás</h3>
            <p>Ez a gomb létrehozza a táblákat(3 évre).</p>
            <form method="post">
                <button type="submit" name="generate" style="padding: 10px; cursor: pointer;">Alapadatok generálása</button>
            </form>
        </div>
        <div style="padding: 15px; border: 1px solid #ccc;">
            <h3>Adattáblák kezelése (CRUD)</h3>
            <ul>
                <li><a href="index.php?view=subjects">Tantárgyak listázása / módosítása</a></li>
                <li><a href="index.php?view=classes">Osztályok listázása / módosítása</a></li>
                <li><a href="index.php?view=students">Diákok listázása / módosítása</a></li>
                <li><a href="index.php?view=marks">Jegyek listázása / módosítása</a></li>
            </ul>
        </div>';
    }
}