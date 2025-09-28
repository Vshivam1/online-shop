<?php
include "db.php";

if (isset($_GET['q'])) {
    $keyword = trim($_GET['q']);

    if ($keyword !== '') {
        $sql = "SELECT p.product_id, p.product_title
                FROM products p
                LEFT JOIN categories c ON p.product_cat = c.cat_id
                LEFT JOIN brands b ON p.product_brand = b.brand_id
                WHERE p.product_title LIKE ?
                   OR p.product_keywords LIKE ?
                   OR p.product_desc LIKE ?
                   OR c.cat_title LIKE ?
                   OR b.brand_title LIKE ?
                LIMIT 10";

        $like = "%{$keyword}%";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sssss", $like, $like, $like, $like, $like);
        $stmt->execute();
        $result = $stmt->get_result();

        $suggestions = [];
        while ($row = $result->fetch_assoc()) {
            $suggestions[] = [
                "id" => $row['product_id'],
                "title" => $row['product_title']
            ];
        }
        echo json_encode($suggestions);
    }
}
?>
