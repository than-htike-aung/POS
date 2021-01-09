<?php
require_once "connectdb.php";
error_reporting(0);
session_start();

if($_SESSION['useremail']=="" OR $_SESSION['role']=="User"){
    header('location:index.php');
  }

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

        <div class="box box-warning">
          <form action="" method="POST">
          <div class="box-header with-border">
              <h3 class="box-title">
                From : <?php echo $_POST['date_1'] ?> --
                To : <?php echo $_POST['date_2'] ?>
              </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
          
              <div class="box-body">
              <div class="row">
            <div class="col-md-5">

            <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right" id="datepicker1" name="date_1"
                   data-date-format="yyyy-mm-dd">
                </div>
            </div>
            <div class="col-md-5">
            <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right" id="datepicker2" name="date_2"
                   data-date-format="yyyy-mm-dd">
                </div>
            </div>
            
            <div class="col-md-2">
            <div align="left">
    <input type="submit" name="btndatefilter" value="Filter By Dater" class="btn btn-success">
  </div>
            </div>
            </div>

            <br><br>

<?php
    $select = $pdo->prepare("SELECT order_date, sum(total) as price
    FROM tbl_invoice WHERE order_date BETWEEN :fromdate AND :todate GROUP BY order_date");
    $select->bindParam(':fromdate', $_POST['date_1']);
    $select->bindParam(':todate', $_POST['date_2']);
    $select->execute();

    $total=[];
    $date=[];

    while($row=$select->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        $total[]= $price;
        $date[] = $order_date;
    }

    //echo json_encode($total);

?>
        <div class="chart">
        <canvas id="myChart" style="height: 250px;"></canvas>
        </div>

        <?php
    $select = $pdo->prepare("SELECT product_name, sum(qty) as q
    FROM tbl_invoice_details WHERE order_date BETWEEN :fromdate AND :todate GROUP BY product_id");
    $select->bindParam(':fromdate', $_POST['date_1']);
    $select->bindParam(':todate', $_POST['date_2']);
    $select->execute();

    $pname=[];
    $qty=[];

    while($row=$select->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        $pname[]= $product_name;
        $qty[] = $q;
    }

    //echo json_encode($total);

?>
 <div class="chart">
        <canvas id="bestsellingproduct" style="height: 250px;"></canvas>
</div>

                
              </div>
              </form>
          </div>

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
var ctx = document.getElementById('myChart');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($date); ?>,
        datasets: [{
            label: 'Total Earning',
            data:<?php echo json_encode($total) ?>,
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


<script>
var ctx = document.getElementById('bestsellingproduct');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($pname); ?>,
        datasets: [{
            label: 'Total Quantity',
            data:<?php echo json_encode($qty) ?>,
            backgroundColor: 'rgb(102, 255, 102)',
            borderColor: 'rgb(0, 102, 0)',
           
            
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




<script>
      //Date picker
  $('#datepicker1').datepicker({
      autoclose: true
    });

     //Date picker
     $('#datepicker2').datepicker({
      autoclose: true
    });

   
</script>

<?php
require_once  "footer.php";
?>


