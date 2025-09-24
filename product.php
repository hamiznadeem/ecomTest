<?php
include "inc_header.php";

$productName = $productCategory = $productPrice = $productStock = "";
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_add_btn'])){
    $productImg = $_FILES["product_img"]["name"];
    $targetFolder = "assets/img/productImg/";
    $targetImg = $targetFolder . $productImg;
    if(preg_match("/image\/(png)|(jpeg)|(webp)i/", $_FILES["product_img"]["type"])){

        if(move_uploaded_file( $_FILES["product_img"]["tmp_name"], $targetImg)){
            $productName = safe_input($_POST["product_name"]);
            $productCategory = safe_input($_POST["product_category"]);
            $productSupplier = safe_input($_POST["product_supplier"]);
            $productPrice = safe_input($_POST["product_price"]);
            $productStock = safe_input($_POST["product_stock"]);
            $productAddDate = date("Y-m-d");
            $sql = "INSERT INTO `products` 
            (`ProductName`, `product_img`, `productAddDate`, `SupplierID`, `CategoryID`, `Unit`, `Price`) 
            VALUES ( '$productName', '$targetImg', '$productAddDate', '$productSupplier', '$productCategory', '$productStock', '$productPrice');";
            if($result = mysqli_query($conn, $sql)){
                header("location:" . $_SERVER['PHP_SELF']);
            }
        }

    }else{
        echo "<script> alert('Invalid Image Format')</script>";
    }
}

if(isset($_GET["delete_product_id"])){
    $sql = "DELETE FROM products WHERE ProductID='$_GET[delete_product_id]'";
    $result = mysqli_query($conn, $sql);
    header("location:" . $_SERVER['PHP_SELF']);
}

$sql = "SELECT * FROM products WHERE product_img!=' ' ORDER BY ProductID DESC";
$result = mysqli_query($conn, $sql);

$sql = "SELECT * FROM suppliers";
$sup_result = mysqli_query($conn, $sql);

$sql = "SELECT * FROM categories";
$cate_result = mysqli_query($conn, $sql);

?>

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Products</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Admin Panel</a></li>
                <li class="breadcrumb-item active">Products</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="card">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title">Products</h5>
                    <button class="btn" style="background-color: #4154f1; color: antiquewhite;" data-bs-toggle="modal" data-bs-target="#addProductModal">
                        <i class="bi bi-plus-lg"></i> Add New Product
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead style="opacity: .7; font-size: .8rem;">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Product</th>
                                <th scope="col">Category</th>
                                <th scope="col">Added Date</th>
                                <th scope="col">Price</th>
                                <th scope="col">Unit</th>
                                <th scope="col">Status</th>
                                <th scope="col">action</th>
                            </tr>
                        </thead>
                        <tbody class="fw-bold"  style="font-size: .8rem;">
                            <?php
                            $ser = 1;
                            while ($product_row = mysqli_fetch_assoc($result)){
                                $sql = "SELECT * FROM categories WHERE CategoryID=$product_row[CategoryID]";
                                $result2 = mysqli_query($conn, $sql);
                                $category_row = mysqli_fetch_array($result2);
                            ?>
                            <tr class="flex align-middle">
                                <td scope="row"><?php echo "$ser";?></td>
                                <td>
                                    <img class="rounded me-1" src="<?php echo "$product_row[product_img]";?>" height="60px" width="60px" alt="<?php echo "$product_row[ProductName]";?>">
                                    <?php echo "$product_row[ProductName]";?>
                                </td>
                                <td><?php echo "$category_row[CategoryName]";?></td>
                                <td><?php echo "$product_row[productAddDate]";?></td>
                                <td>$ <?php echo "$product_row[Price]";?></td>
                                <td><?php echo "$product_row[Unit]";?></td>
                                <td>
                                    <?php
                                    $status = "Active";
                                    $activeIcon = '<i class="bi bi-eye"></i>';
                                    if ($product_row["Active"] == 1) {
                                        $class = "bg-success";
                                    }elseif($product_row["Active"] == 0) {
                                        $class = "bg-danger";
                                        $status = "Inactive";
                                        $activeIcon = '<i class="bi bi-eye-slash"></i>';
                                    }
                                    ?>
                                    <span style="font-size: .73rem;" class="badge <?php echo "$class"?>" > <?php echo "$status"?></span>
                                </td>
                                <td>
                                    <a class="text-black" href=""><span class="me-1"> <?php echo "$activeIcon"?></span></a>
                                    <a class="text-black" href=""><span><i class="bi bi-pencil-square "></i></span></a>
                                    <a class="text-black" href="<?php echo $_SERVER['PHP_SELF']."?delete_product_id=$product_row[ProductID]"?>"
                                    onclick="if(!confirm('Are you sure you want to delete this product?')) { event.preventDefault(); }">
                                        <span class="me-1">
                                            <i class="bi bi-trash3"></i>
                                        </span>
                                    </a>
                                </td>
                            </tr>
                            <?php $ser++; } ?>
                        </tbody>
                    </table>
                </div>


            </div>
        </div>
    </section>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="addProductForm" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="addProductName" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="addProductName" name="product_name" required>
                        </div>

                        <div class="mb-3">
                            <label for="addProductName" class="form-label">Product Image</label>
                            <input type="file" class="form-control" id="addProductName" name="product_img" required>
                        </div>

                        <div class="mb-3">
                            <label for="addProductCategory" class="form-label">Category</label>
                            <select class="form-control" name="product_category" id="addProductCategory">
                                <option value="">Categories</option>
                                <?php
                                while($cate_row = mysqli_fetch_assoc($cate_result)){ 
                                ?>
                                    <option value="<?php echo $cate_row['CategoryID']?>"><?php echo $cate_row['CategoryName']?></option>
                                <?php    
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="addProductCategory" class="form-label">supplier</label>
                            <select class="form-control" name="product_supplier" id="addProductCategory">
                                <option value="">Suppliers</option>
                                <?php
                                while($sup_row = mysqli_fetch_assoc($sup_result)){ 
                                ?>
                                    <option value="<?php echo $sup_row['SupplierID']?>"><?php echo $sup_row['SupplierName']?></option>
                                <?php    
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="addProductPrice" class="form-label">Price ($)</label>
                            <input type="number" step="0.1" class="form-control" id="addProductPrice" name="product_price" required>
                        </div>

                        <div class="mb-3">
                            <label for="addProductStock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="addProductStock" name="product_stock" required>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="product_add_btn" class="btn text-white" style="background-color: #4154f1;">Add Product</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


</main><!-- End #main -->



<?php
include "inc_footer.php";
?>