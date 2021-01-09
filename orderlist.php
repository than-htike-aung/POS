<?php
require_once "connectdb.php";

session_start();
if($_SESSION['useremail']=="" OR $_SESSION['role']==""){
    header('location:index.php');
  }


  if($_SESSION['role'] == 'Admin'){
    require_once "header.php";
  }else{
      require_once "headeruser.php";
  }

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
           Order List
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
            <div class="box-header with-border">
              <h3 class="box-title">Order List</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
           
              <div class="box-body">
              <div style="overflow-x:auto;">
              <table id="orderlisttable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Invoice ID</th>
                            <th>Customer name</th>
                            <th>OrderDate</th>
                            <th>Total</th>
                            <th>Paid</th>
                            <th>Due</th>
                            <th>Payment Type</th>
                            <th>Print</th>
                            
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php 

                        $select = $pdo->prepare("SELECT * FROM tbl_invoice ORDER BY invoice_id DESC");
                        $select->execute();
                        while($row=$select->fetch(PDO::FETCH_OBJ)){
                            echo '
                                <tr>
                                    <td>'.$row->invoice_id.'</td>
                                    <td>'.$row->customer_name.'</td>
                                    <td>'.$row->order_date.'</td>
                                    <td>'.$row->total.'</td>
                                    <td>'.$row->paid.'</td>
                                    <td>'.$row->due.'</td>
                                    <td>'.$row->payment_type.'</td>
                                   
                             <td>      
                        <a href="invoice_80mm.php?id='.$row->invoice_id.'" class="btn btn-warning" role="button">
                          <span class="glyphicon glyphicon-print" style="color:#ffffff" data-toogle="tooltip" title="Print Invoice"></span>
                        </a>            
                            </td>

                            <td>      
                            <a href="editorder.php?id='.$row->invoice_id.'" class="btn btn-info" role="button">
                              <span class="glyphicon glyphicon-edit" style="color:#ffffff" data-toggle="tooltip" title="Edit Order"></span>
                            </a>            
                            </td>

                            <td>      
                        <button id='.$row->invoice_id.' class="btn btn-danger btndelete">
                            <span class="glyphicon glyphicon-trash" style="color:#ffffff" data-toggle="tooltip" title="Delete Order"></span>
                            </button>            
                            </td>

                                </tr>
                            
                            
                            ';
                        }

                    
                        
                        ?>
                    </tbody>

                </table>
        </div>
              </div>
          </div>

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
  $(document).ready( function () {
    $('#orderlisttable').DataTable({
        "order":[[0,"desc"]]
    });
} );
</script>

<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });


    $(document).ready(function(){
        $('.btndelete').click(function(){
            var tdh = $(this);
            var id = $(this).attr("id");

        swal({
            title: "Do you want to delete Order?",
            text: "Once order is deleted, you will not able to recover this product!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete)=>{
            if(willDelete){
                $.ajax({
                url:'orderdelete.php',
                type:'post',
                data:{
                    pidd:id
                },
                success:function(data){
                    tdh.parents('tr').hide();
                }
            })
                swal("Your order has been deleted", {
                    icon: "success",
                });
            }else{
                swal("Your order is safe!")
            }
        });

            //alert(id);
           
        });
    });

</script>


<?php
require_once  "footer.php";
?>


