<?php
include "inc_header.php";

$filter = "no filter";
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["filter"]) && $_GET["filter"] != "all") {
    $filter = $_GET["filter"];
    $sql = "SELECT * FROM orders WHERE OrderStatus='$filter' ";
    $result = mysqli_query($conn, $sql);
}else{
    $sql = "SELECT * FROM orders";
    $result = mysqli_query($conn, $sql);
    $filter = "All";
}


?>

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Orders</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Admin Panel</a></li>
                <li class="breadcrumb-item active">Orders</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="card">
            <div class="card-body">

                <div class="d-flex justify-content-start align-items-center mb-3 gap-3">
                    <h5 class="card-title">Orders</h5>
                        <form class="mt-3" method="get" action="<?php $_SERVER['PHP_SELF']?>">
                            <select class="form-select form-select-sm" name="filter" onchange="this.form.submit()">
                                <option value=""><?php echo "$filter"?></option>
                                <option value="all">All</option>
                                <option value="pending">Pending</option>
                                <option value="shipped">Shipped</option>
                                <option value="delivered">Delivered</option>
                            </select>
                        </form>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead style="opacity: .7; font-size: .8rem;">
                            <tr>
                                <th scope="col">Order #</th>
                                <th scope="col">Customer</th>
                                <th scope="col">Shipper</th>
                                <th scope="col">Order Date</th>
                                <th scope="col">Customer Address</th>
                                <th scope="col">Order Status</th>
                            </tr>
                        </thead>
                        <tbody class="fw-bold"  style="font-size: .85rem;">
                            <?php
                            while ($orders_row = mysqli_fetch_assoc($result)){
                                $sql = "SELECT * FROM customers WHERE CustomerID=$orders_row[CustomerID]";
                                $customer_result = mysqli_query($conn, $sql);
                                $customer_row = mysqli_fetch_array($customer_result);

                                $sql = "SELECT * FROM shippers WHERE ShipperID=$orders_row[ShipperID]";
                                $shipper_result = mysqli_query($conn, $sql);
                                $shipper_row = mysqli_fetch_array($shipper_result);
                            ?>
                            <tr class="flex align-middle">
                                <td scope="row"><?php echo "$orders_row[OrderID]";?></td>
                                <td><?php echo "$customer_row[CustomerName]";?></td>
                                <td><?php echo "$shipper_row[ShipperName]";?></td>
                                <td><?php echo "$orders_row[OrderDate]";?></td>
                                <td><?php echo "$customer_row[Address]" . ", " . " $customer_row[City]" . " " . " $customer_row[Country]";?></td>
                                <td>
                                    <?php
                                    if ($orders_row["OrderStatus"] == "pending") {
                                        $class = "bg-danger";
                                    }elseif($orders_row["OrderStatus"] == "shipped") {
                                        $class = "bg-warning";
                                    }elseif($orders_row["OrderStatus"] == "delivered") {
                                        $class = "bg-success";
                                    }
                                    ?>
                                    <h6><span class="badge badge-lg <?php echo "$class";?>"><?php echo "$orders_row[OrderStatus]";?></span></h6>
                                </td>
                                <td>
                                    <a class="text-black" href=""><span class="me-1"><i class="bi bi-eye"></i></span></a>
                                    <a class="text-black" href=""><span class="me-1"><i class="bi bi-pencil-square"></i></span></a>
                                    <a class="text-black" href=""><span><i class="bi bi-trash3"></i></span></a>
                                </td>
                            </tr>
                            <?php  } ?>
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