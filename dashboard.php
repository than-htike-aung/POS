<?php

require_once "connectdb.php";
session_start();

if($_SESSION['useremail'] == ""){
    header('location:index.php');
}

$select = $pdo->prepare("SELECT sum(total) as t, count(invoice_id) as inv 
        FROM tbl_invoice");
    
    $select->execute();
    
    $row=$select->fetch(PDO::FETCH_OBJ);

    $total_order = $row->inv;
    $net_total = $row->t;

require_once "header.php";
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Admin Dashboard
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

        <!--------------------------
          | Your Page Content Here |
          -------------------------->
        <div class="box-body">
        <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo $total_order; ?></h3>

              <p>Total Orders</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php echo "$".number_format($net_total,2); ?><sup style="font-size: 20px"></sup></h3>

              <p>Total Revenue</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

<?php
    $select = $pdo->prepare("SELECT count(pname) as p FROM tbl_product");
    $select->execute();
    $row = $select->fetch(PDO::FETCH_OBJ);
    $total_prodcut = $row->p
?>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?php echo $total_prodcut ?></h3>

              <p>Total Product</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
<?php 
    $select = $pdo->prepare("SELECT count(category) as cate FROM tbl_category");
    $select->execute();
    $row = $select->fetch(PDO::FETCH_OBJ);
    $total_category = $row->cate;


    $select = $pdo->prepare("SELECT order_date, total from tbl_invoice 
    GROUP BY order_date LIMIT 30");
    $select->execute();

    $ttl = [];
    $date = [];

    while($row = $select->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        $ttl[] = $total;
        $date[] = $order_date;
    }
 
   
?>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3><?php echo $total_category; ?></h3>

              <p>Total Category</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>

      <div class="box box-warning">
        <div class="box-header with-border">
            <h3 class="box-title">Earning By Date</h3>
        </div>

    <div class="box-body">
        <div class="chart">
         <canvas id="earningbydate" style="height: 250px;"></canvas>
        </div>
    </div>

      </div>

    <div class="row">
        <div class="col-md-6">
          <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Best Selling Product</h3>
        </div>

    <div class="box-body">
    <table id="bestsellingproductlist" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Product name</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Total</th>                                                
                           
                        </tr>
                    </thead>

                    <tbody>
                        <?php 

$select = $pdo->prepare("SELECT product_id, product_name, price, sum(qty) as q, sum(qty*price) as total
FROM tbl_invoice_details GROUP BY product_id ORDER BY sum(qty) DESC LIMIT 15");         
                        $select->execute();
                        while($row=$select->fetch(PDO::FETCH_OBJ)){
                            echo '
                                <tr>
                                    <td>'.$row->product_id.'</td>
                                    <td>'.$row->product_name.'</td>
                                    <td><span class="label label-info">'.$row->q.'</span></td>
                                    <td><span class="label label-success">'."$".$row->price.'</span></td>    
                                    <td><span class="label label-danger">'."$".$row->total.'</span></td>                                
                                               
                            ';
                        }

                    
                        
                        ?>
                    </tbody>

                </table>  
    </div>

      </div>
        </div>
          <div class="col-md-6">
          <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Recent Orders</h3>
        </div>

    <div class="box-body">
    <table id="orderlisttable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Invoice ID</th>
                            <th>Customer name</th>
                            <th>OrderDate</th>
                            <th>Total</th>
                           
                            <th>Payment Type</th>
                                                 
                    
                        </tr>
                    </thead>

                    <tbody>
                        <?php 

                        $select = $pdo->prepare("SELECT * FROM tbl_invoice ORDER BY invoice_id DESC LIMIT 15");
                        $select->execute();
                        while($row=$select->fetch(PDO::FETCH_OBJ)){
                            echo '
                                <tr>
                        <td><a href="editorder.php?id='.$row->invoice_id.'">'.$row->invoice_id.'</a></td>
                                    <td>'.$row->customer_name.'</td>
                                    <td>'.$row->order_date.'</td>
                       <td><span class="label label-danger">'."$".$row->total.'</span></td>
                       
                       ';
                                   
                      if($row->payment_type == "Cash"){
                        echo '<td><span class="label label-warning">'.$row->payment_type.'</span></td>';
                      }else if($row->payment_type == "Card"){
                        echo '<td><span class="label label-success">'.$row->payment_type.'</span></td>'; 
                      }else{
                        echo '<td><span class="label label-primary">'.$row->payment_type.'</span></td>';
                      }
                                   
                
                            
                           
                        }
                  
                        ?>
                    </tbody>

                </table>
    </div>

      </div>
          </div>
      </div>


        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
var ctx = document.getElementById('earningbydate');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($date); ?>,
        datasets: [{
            label: 'Total Earning',
            data:<?php echo json_encode($ttl) ?>,
            backgroundColor: 'rgb(255, 99, 132)',
            borderColor: 'rgb(255, 99, 132)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});
</script>
<!-- <script>
  $(document).ready( function () {
    $('#bestsellingproductlist').DataTable({
        "order":[[0,"desc"]]
    });
} );
</script>

<script>
  $(document).ready( function () {
    $('#orderlisttable').DataTable({
        "order":[[0,"desc"]]
    });
} );
</script> -->

<?php
require_once  "footer.php";
?>

