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
          Sales Report -> Table Report
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
<form action="" method="POST">
          <div class="box box-warning">
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
  $select = $pdo->prepare("SELECT sum(total) as total, sum(subtotal) as stotal, count(invoice_id) as invoice
    FROM tbl_invoice WHERE order_date BETWEEN :fromdate AND :todate");
    $select->bindParam(':fromdate', $_POST['date_1']);
    $select->bindParam(':todate', $_POST['date_2']);

    $select->execute();

    $row= $select->fetch(PDO::FETCH_OBJ);
    $net_total = $row->total;
    $stotal = $row->stotal;
    $invoice = $row->invoice;


?>



            <!-- Info boxes -->
      <div class="row">
        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-files-o"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Invoice</span>
              <span class="info-box-number"><h2><?php echo number_format( $invoice);?></h2></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
       
        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Sub Total</span>
              <span class="info-box-number"><h2><?php echo number_format($stotal,2) ; ?></h2></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-usd"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Net</span>
              <span class="info-box-number"><h2><?php echo number_format($net_total,2); ?></h2></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <table id="salesreporttable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Invoice ID</th>
                            <th>Customer name</th>
                            <th>Subtotal</th>
                            <th>Tax(5%)</th>
                            <th>Discount</th>
                            <th>Total</th>
                            <th>Paid</th>
                            <th>Due</th>
                            
                            <th>OrderDate</th>
                            <th>Payment Type</th>
                         
                            
                            
                        </tr>
                    </thead>

                    <tbody>
                    <?php 
 // select = $pdo->prepare("SELECT * FROM tbl_invoice ORDER BY invoice_id DESC");
$select = $pdo->prepare("SELECT * FROM tbl_invoice WHERE order_date BETWEEN :fromdate AND :todate");
$select->bindParam(':fromdate',$_POST['date_1']);
$select->bindParam(':todate', $_POST['date_2']);

$select->execute();

while($row=$select->fetch(PDO::FETCH_OBJ)){
    echo '
        <tr>
            <td>'.$row->invoice_id.'</td>
            <td>'.$row->customer_name.'</td>
            <td>'.$row->subtotal.'</td>
            <td>'.$row->tax.'</td>
            <td>'.$row->discount.'</td>
            <td><span class="label label-danger">'."$ ".$row->total.'</span></td>
            <td>'.$row->paid.'</td>
            <td>'.$row->due.'</td>
           



            <td>'.$row->order_date.'</td>
    
        
    ';

    if($row->payment_type == "Cash"){
      echo '<td><span class="label label-primary">'.$row->payment_type.'</span></td>';
    }else if($row->payment_type == "Card"){
      echo '<td><span class="label label-warning">'.$row->payment_type.'</span></td>';
    }else{
      echo '<td><span class="label label-info">'.$row->payment_type.'</span></td>';
    }
}

?>
                    </tbody>

                </table>

        </div>

              </div>
              </form>
          </div>

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
      //Date picker
  $('#datepicker1').datepicker({
      autoclose: true
    });

     //Date picker
     $('#datepicker2').datepicker({
      autoclose: true
    });

    $(document).ready( function () {
    $('#salesreporttable').DataTable({
        "order":[[0,"desc"]]
    });
} );
</script>


<?php
require_once  "footer.php";
?>


