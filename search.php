<?php
include "header.php";
include "db.php";

/**
 * Search products by keyword and return HTML of product cards.
 */
function searchProducts($con, $keyword)
{
    // sanitize input
    $keyword = trim($keyword);
    if ($keyword === '') {
        return "<h3>Please enter a search keyword</h3>";
    }

    // prepare query (safe against SQL injection)
    $sql = "SELECT product_id, product_title, product_price, product_image
            FROM products
            WHERE product_title   LIKE ?
               OR product_keywords LIKE ?";
    $like = "%{$keyword}%";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();

    // build HTML
    if ($result->num_rows === 0) {
        return "<h3>No products found for <b>".htmlspecialchars($keyword)."</b></h3>";
    }

    $html = "";
    while ($row = $result->fetch_assoc()) {
        $pro_id    = $row['product_id'];
        $pro_title = htmlspecialchars($row['product_title']);
        $pro_price = htmlspecialchars($row['product_price']);
        $pro_image = htmlspecialchars($row['product_image']);

        $html .= "
        <div class='col-md-4 col-xs-6'>
            <div class='product'>
                <div class='product-img'>
                    <img src='product_images/$pro_image' style='height:250px;' alt='$pro_title'>
                </div>
                <div class='product-body'>
                    <h3 class='product-name'><a href='product.php?pid=$pro_id'>$pro_title</a></h3>
                    <h4 class='product-price'>₹$pro_price</h4>
                </div>
                <div class='add-to-cart'>
                    <button pid='$pro_id' id='product' class='add-to-cart-btn'>
                        <i class='fa fa-shopping-cart'></i> Add to cart
                    </button>
                </div>
            </div>
        </div>";
    }
    return $html;
}
?>

<div class="main main-raised"> 
    <div class="section">
        <div class="container">
            <div class="row">

                <!-- ASIDE -->
                <div id="aside" class="col-md-3">
                    <div id="get_category"></div>
                    <div id="get_brand"></div>
                    <div class="aside">
                        <h3 class="aside-title">Top selling</h3>
                        <div id="get_product_home"></div>
                    </div>
                </div>
                <!-- /ASIDE -->

                <!-- STORE -->
                <div id="store" class="col-md-9">
                    <div class="store-filter clearfix">
                        <ul class="store-grid">
                            <li class="active"><i class="fa fa-th"></i></li>
                            <li><a href="#"><i class="fa fa-th-list"></i></a></li>
                        </ul>
                    </div>

                    <!-- store products -->
                    <div class="row" id="product-row">
                        <div class="col-md-12 col-xs-12" id="product_msg"></div>

                        <?php
                        if (isset($_GET['search'])) {
                            echo searchProducts($con, $_GET['search']);
                        } else {
                            echo "<h3>Please enter a search keyword</h3>";
                        }
                        ?>
                    </div>
                    <!-- /store products -->

                    <div class="store-filter clearfix">
                        <span class="store-qty">Search Results</span>
                    </div>
                </div>
                <!-- /STORE -->

            </div>
        </div>
    </div>
</div>

<?php
include "newslettter.php";
include "footer.php";
include "header.php";
?>
