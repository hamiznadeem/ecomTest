<?php
include "inc_header.php";

if(isset($_GET["delete_product_id"])){
    $sql = "DELETE FROM products WHERE ProductID='$_GET[delete_product_id]'";
    $result = mysqli_query($conn, $sql);
    header("location:" . $_SERVER['PHP_SELF']);
}

if(isset($_GET["active_product_id"])){
    $sql = "SELECT * FROM products WHERE ProductID='$_GET[active_product_id]'";
    $result = mysqli_query($conn, $sql);
    $activeCheck_row = mysqli_fetch_array($result);
    if($activeCheck_row["Active"] == 0){
        $sql = "UPDATE `products` SET `Active`=1 WHERE `ProductID`='$_GET[active_product_id]'";
    }else if($activeCheck_row["Active"] == 1){
        $sql = "UPDATE `products` SET `Active`= 0  WHERE `ProductID`='$_GET[active_product_id]'";
    }
    $result = mysqli_query($conn, $sql);
    header("location:" . $_SERVER['PHP_SELF']);
}

$product_fil_id = "";
$filter = false;
if(isset($_GET['product_cate_fil']) && $_GET['product_cate_fil'] != 'All'){
    $product_fil_id = $_GET['product_cate_fil'];
    $sql = "SELECT * FROM products WHERE CategoryID='$product_fil_id' ORDER BY ProductID DESC";
    $filter = true;
}else{
    $sql = "SELECT * FROM products ORDER BY ProductID DESC";
}
$product_result = mysqli_query($conn, $sql);

$sql = "SELECT * FROM categories";
$cate_result = mysqli_query($conn, $sql);

$selected_cate_name = "All";
$filter_options = "";
while($cate_row = mysqli_fetch_assoc($cate_result)){
    if($filter){
        if($cate_row["CategoryID"] == $product_fil_id){
            $selected_cate_name = $cate_row["CategoryName"];
        }
    }

    if($product_fil_id == $cate_row['CategoryID']){
        $filter_options .= '<option value="All">All</option>';
        continue;
    }
    $filter_options .= '<option value="'.$cate_row['CategoryID']. '">' .$cate_row['CategoryName'].'</option>';
} 

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
                    <div class="d-flex justify-content-start align-items-center gap-3">
                        <h5 class="card-title">Products</h5>
                        <form class="mt-3" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="GET">
                            <select class="form-control" name="product_cate_fil" id="product_cate_fil" onchange="this.form.submit()">
                                <option value="" > <?php echo $selected_cate_name ?></option>
                                <?php echo $filter_options ?>
                            </select>
                        </form>
                    </div>
                    <a href="product_add.php" class="btn" style="background-color: #4154f1; color: antiquewhite;">
                        <i class="bi bi-plus-lg"></i> Add New Product
                    </a>
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
<<<<<<< HEAD
                            while ($product_row = mysqli_fetch_assoc($result)){
                                $categoryID = $product_row["CategoryID"];
                                $sql = "SELECT * FROM categories WHERE CategoryID='$categoryID'";
=======
                            while ($product_row = mysqli_fetch_assoc($product_result)){
                                $sql = "SELECT * FROM categories WHERE CategoryID=$product_row[CategoryID]";
>>>>>>> 3df8e0e573a9a88bebab09cfc378c9b36473b500
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
<<<<<<< HEAD
                                    <a class="text-black" href=""><span class="me-1"> <?php echo "$activeIcon"?></span></a>
                                    <a class="text-black" href=""><span><i class="bi bi-pencil-square "></i></span></a>
                                    <a class="text-black" href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?delete_product_id=' . urlencode($product_row['ProductID'])); ?>"
=======
                                    <a class="text-black" href="<?php echo $_SERVER['PHP_SELF']."?active_product_id=".urlencode($product_row['ProductID'])?>"><span class="me-1"> <?php echo "$activeIcon"?></span></a>
                                    <a class="text-black" href="product_edit.php?product_edit_id=<?php echo urlencode($product_row['ProductID'])?>" ><span><i class="bi bi-pencil-square "></i></span></a>
                                    <a class="text-black" href="<?php echo $_SERVER['PHP_SELF']."?delete_product_id=".urlencode($product_row['ProductID'])?>"
>>>>>>> 3df8e0e573a9a88bebab09cfc378c9b36473b500
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

</main><!-- End #main -->


<?php
include "inc_footer.php";
?>