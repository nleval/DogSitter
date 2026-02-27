<?php
/**
 * @file backupBD.php 
 * @brief Script permettant de sauvegarder la BD régulièrement
 * @date 23/01/2026
 * @author LÉVAL Noah
 */

require_once __DIR__ . '/../vendor/autoload.php';
use Symfony\Component\Yaml\Yaml;

// On charge les constantes
$configPath = __DIR__ . '/../config/constantes.yaml';
if (!file_exists($configPath)) {
    die("Erreur : Le fichier constantes.yaml est introuvable.\n");
}
$config = Yaml::parseFile($configPath);
$dbConfig = $config['DB_'];
$prefixeTable = $config['PREFIXE_TABLE'];

// Connexion à la BD
$host = $dbConfig['HOST'];
$dbName = $dbConfig['NAME'];
$user = $dbConfig['USER'];
$password = $dbConfig['PASS'];
$conn = new mysqli($host, $user, $password, $dbName);
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Configurer l'encodage UTF-8 (pour les accents)
$conn->set_charset("utf8mb4");

// Nom du fichier de sauvegarde avec année-mois-etc...
$date = date('Y-m-d-H-i-s');
$backupDir = __DIR__ . '/../backupBD/';
$backupFile = $backupDir . $date . '.sql';

// Joli mise en page :)
$backupSql = "-- Sauvegarde complète des tables préfixées par $prefixeTable dans $dbName\n";
$backupSql .= "-- Générée le $date\n\n";

// Récupérer toutes les tables de la base
$tables = $conn->query("SHOW TABLES");
if (!$tables) {
    die("Erreur lors de la récupération des tables : " . $conn->error);
}

// Récupérer les relations entre tables
$tableDependencies = [];
while ($row = $tables->fetch_row()) {
    $table = $row[0];
    if (strpos($table, $prefixeTable) === 0) {
        // Analyser la structure de la table pour extraire les contraintes FOREIGN KEY
        $createTable = $conn->query("SHOW CREATE TABLE $table")->fetch_row()[1];
        preg_match_all("/FOREIGN KEY.*?REFERENCES `(.*?)`/", $createTable, $matches);
        $tableDependencies[$table] = $matches[1] ?? [];
    }
}

// Trier les tables par ordre de dépendance (tri topologique)
$sortedTables = [];
$visited = [];

function sortTables($table, &$visited, &$sortedTables, $tableDependencies) {
    if (!isset($visited[$table])) {
        $visited[$table] = true;
        foreach ($tableDependencies[$table] ?? [] as $dependency) {
            sortTables($dependency, $visited, $sortedTables, $tableDependencies);
        }
        $sortedTables[] = $table;
    }
}

foreach (array_keys($tableDependencies) as $table) {
    sortTables($table, $visited, $sortedTables, $tableDependencies);
}

// Exporter les table et données
foreach ($sortedTables as $table) {
    // Exporter la structure de la table (genre pour avoir le bon ordre)
    $createTable = $conn->query("SHOW CREATE TABLE $table")->fetch_row()[1];
    $backupSql .= "-- Structure de la table $table\n";
    $backupSql .= "$createTable;\n\n";

    $backupSql .= "-- Données de la table $table\n";
    $rows = $conn->query("SELECT * FROM $table");
    while ($data = $rows->fetch_assoc()) {
        $escapedValues = [];
        foreach ($data as $value) {
            if (is_null($value)) {
                $escapedValues[] = 'NULL';
            } else {
                // Remplace les apostrophes simples par deux apostrophes pour SQL
                $escapedValue = str_replace("'", "''", $value);
                $escapedValues[] = "'" . $escapedValue . "'";
            }
        }
        $backupSql .= "INSERT INTO $table VALUES (" . implode(',', $escapedValues) . ");\n";
    }
    $backupSql .= "\n";
}

// ==============================
// Export des triggers
// ==============================

$backupSql .= "-- -------------------------------------------\n";
$backupSql .= "-- Triggers\n";
$backupSql .= "-- -------------------------------------------\n\n";

// Désactiver les vérifications pour éviter les erreurs à l'import
$backupSql .= "SET FOREIGN_KEY_CHECKS=0;\n";
$backupSql .= "DELIMITER $$\n\n";

$triggersResult = $conn->query("SHOW TRIGGERS");

if ($triggersResult) {
    while ($trigger = $triggersResult->fetch_assoc()) {

        $triggerTable = $trigger['Table'];

        // On vérifie que la table du trigger a le bon préfixe
        if (strpos($triggerTable, $prefixeTable) === 0) {

            $triggerName = $trigger['Trigger'];

            $createTrigger = $conn->query("SHOW CREATE TRIGGER `$triggerName`");
            if ($createTrigger) {
                $row = $createTrigger->fetch_assoc();

                $backupSql .= "-- Trigger : $triggerName\n";
                $backupSql .= "DROP TRIGGER IF EXISTS `$triggerName` $$\n";
                $backupSql .= $row['SQL Original Statement'] . " $$\n\n";
            }
        }
    }
}

$backupSql .= "DELIMITER ;\n";
$backupSql .= "SET FOREIGN_KEY_CHECKS=1;\n\n";

file_put_contents($backupFile, $backupSql);

// Garder maximum 3 fichiers de sauvegarde
$files = glob($backupDir . '*.sql');

// Trier par date de modification (du plus ancien au plus récent)
usort($files, function ($a, $b) {
    return filemtime($a) - filemtime($b);
});

// Supprimer les fichiers en trop
while (count($files) > 3) {
    $oldestFile = array_shift($files);
    unlink($oldestFile);
}

// Insérer la date de sauvegarde dans la table `dog_derniereSave`
$conn->query("INSERT INTO dog_derniereSave (date_save) VALUES ('$date')");

// Limiter la table à 3 lignes maximum
$result = $conn->query("SELECT id FROM dog_derniereSave ORDER BY id ASC");
$rows = $result->fetch_all(MYSQLI_ASSOC);

while (count($rows) > 3) {
    // Supprimer la plus ancienne ligne (la première dans l'ordre ASC)
    $oldestId = $rows[0]['id'];
    $conn->query("DELETE FROM dog_derniereSave WHERE id = $oldestId");
    array_shift($rows); // Mettre à jour le tableau
}

// Terminer le script
echo "Sauvegarde complète des tables avec préfixe \"$prefixeTable\" terminée dans : $backupFile\n";

// Fermer la connexion
$conn->close();
?>
