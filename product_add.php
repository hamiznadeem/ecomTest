<?php
include 'inc_header.php';
$ProductName = $ProductCategory = $ProductPrice = $ProductStock = $ProductSupplier = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addBtn'])) { 
    // collect & sanitize inputs
    $ProductName = safe_input($_POST['ProductName'] ?? '');
    $ProductCategory = safe_input($_POST['ProductCategory'] ?? '');
    $ProductPrice = safe_input($_POST['ProductPrice'] ?? '');
    $ProductStock = safe_input($_POST['ProductStock'] ?? '');
    $ProductSupplier = safe_input($_POST['ProductSupplier'] ?? '');
    $ProductAddDate = date('Y-m-d');

    // handle optional file upload
    $photoPath = null;
    if (isset($_FILES['FileToUpload']) && $_FILES['FileToUpload']['error'] == 0) {
        $FileName = basename($_FILES['FileToUpload']['name']);
        $TargetFolder = 'assets/img/product_img/';
        // ensure unique filename to avoid overwrite
        $UniqueName = time() . '_' . preg_replace('/[^A-Za-z0-9\._-]/', '_', $FileName);
        $TargetFile = $TargetFolder . $UniqueName;

        if (move_uploaded_file($_FILES['FileToUpload']['tmp_name'], $TargetFile)) {
            $photoPath = $TargetFile;
        }
    }

    // prepare and execute insert (photo is optional)
    if ($photoPath !== null) {
        $sql ="INSERT INTO `products` (`ProductName`, `product_img`, `productAddDate`, `SupplierID`, `CategoryID`, `Unit`, `Price`)  VALUES (?,?,?,?,?,?,?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'sssssss', $ProductName, $photoPath, $ProductAddDate, $ProductSupplier, $ProductCategory, $ProductStock, $ProductPrice);
    } else {
        $sql ="INSERT INTO `products` (`ProductName`, `productAddDate`, `SupplierID`, `CategoryID`, `Unit`, `Price`)  VALUES (?,?,?,?,?,?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ssssss', $ProductName, $ProductAddDate, $ProductSupplier, $ProductCategory, $ProductStock, $ProductPrice);
    }

    if ($stmt) {
        if (mysqli_stmt_execute($stmt)) {
            header('location: product.php');
            exit;
        } else {
            echo "Error: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Prepare failed: " . mysqli_error($conn);
    }
}


$sql = "SELECT * FROM suppliers";
$sup_result = mysqli_query($conn, $sql);

$sql = "SELECT * FROM categories";
$cate_result = mysqli_query($conn, $sql);

?>
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Add Product</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Admin Panel</a></li>
                <li class="breadcrumb-item"><a href="product.php">Products</a></li>
                <li class="breadcrumb-item active">Add Product</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Add Product Details</h5>

                        <!-- Add new Product -->
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" enctype="multipart/form-data">
                            <div class="row mb-3">
                                <label for="ProductName" class="col-sm-2 col-form-label">Product Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="ProductName" name="ProductName" placeholder="Name" value="<?php echo htmlspecialchars($ProductName); ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="ProductCategory" class="col-sm-2 col-form-label">Product Category</label>
                                <div class="col-sm-10">
                                <select class="form-control" name="ProductCategory" id="addProductCategory">
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
                            </div>
                            <div class="row mb-3">
                                <label for="ProductPrice" class="col-sm-2 col-form-label">Product Price</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="ProductPrice" name="ProductPrice" placeholder="Price" value="<?php echo htmlspecialchars($ProductPrice); ?>"></input>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="ProductStock" class="col-sm-2 col-form-label">Product Stock</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="ProductStock" name="ProductStock" placeholder="Stock" value="<?php echo htmlspecialchars($ProductStock); ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="ProductSupplier" class="col-sm-2 col-form-label">Product Supplier</label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="ProductSupplier" id="ProductSupplier">
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
                            </div>
                            <div class="row mb-3">
                                <label for="FileToUpload" class="col-sm-2 col-form-label">Product Image</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="file" id="FileToUpload" name="FileToUpload">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Submit Button</label>
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary" name="addBtn" id="addBtn">Submit Form</button>
                                </div>
                            </div>

                        </form><!-- End General Form Elements -->

                    </div>
                </div>

            </div>
        </div>
    </section>

</main><!-- End #main -->

<?php
include 'inc_footer.php';
?>