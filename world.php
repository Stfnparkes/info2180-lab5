<?php
$host = 'localhost';
$username = 'lab5_user'; 
$password = 'password123'; 
$dbname = 'world';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$country = filter_input(INPUT_GET, 'country', FILTER_SANITIZE_STRING);
$country = $country ? $country : ''; 
$search_term = "%$country%"; 
$lookup_type = filter_input(INPUT_GET, 'lookup', FILTER_SANITIZE_STRING);




$query = "";
$headers = [];

if ($lookup_type === 'cities') {
    $query = "
        SELECT
            c.name AS CityName,
            c.district AS District,
            c.population AS Population
        FROM cities c
        JOIN countries cs ON c.country_code = cs.code
        WHERE cs.name LIKE :countryName
    ";
    $headers = ['Name', 'District', 'Population'];
} else {
    $query = "
        SELECT
            name AS CountryName,
            continent AS Continent,
            indepyear AS IndependenceYear,
            head_of_state AS HeadOfState
        FROM countries
        WHERE name LIKE :countryName
    ";
    $headers = ['Country Name', 'Continent', 'Independence Year', 'Head of State'];
}


try {
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':countryName', $search_term, PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $results = []; 
    echo "<p style='color: red;'>Database Query Error: " . $e->getMessage() . "</p>";
}

?>

<table>
    <thead>
        <tr>
            <?php foreach ($headers as $header): ?>
                <th><?= $header ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php if (count($results) > 0): ?>
            <?php foreach ($results as $row): ?>
                <tr>
                    <?php foreach ($row as $data): ?>
                        <td><?= htmlspecialchars($data) ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="<?= count($headers) ?>">No results found for "<?= htmlspecialchars($country) ?>"</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>