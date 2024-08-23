<?php
require_once("../db_connect.php");

$city_id = isset($_POST['city_id']) ? (int)$_POST['city_id'] : 0;

if ($city_id > 0) {
    $stmt = $conn->prepare("SELECT address_cityarea_id, address_cityarea_name FROM address_cityarea WHERE address_city_id = ?");
    $stmt->bind_param("i", $city_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo '<option value="'.$row['address_cityarea_id'].'">'.$row['address_cityarea_name'].'</option>';
    }
    
    $stmt->close();
}
$conn->close();
?>
