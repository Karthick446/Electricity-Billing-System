<?php
require_once("../DBConnection.php");
if(isset($_GET['id'])){
$qry = $conn->query("SELECT b.*,(c.lastname || ', ' || c.firstname || ' ' || c.middlename) as fullname, cl.code FROM bill_list b inner join connection_list cl on b.connection_id = cl.connection_id inner join client_list c on cl.client_id = c.client_id where b.bill_id = '{$_GET['id']}'");
    foreach($qry->fetchArray() as $k => $v){
        $$k = $v;
    }
}
?>
<style>
    #uni_modal .modal-footer{
        display:none !important;
    }
</style>
<div class="container-fluid">
    <div class="col-12">
        <div class="w-100 mb-1">
            <div class="fs-6"><b>Connection Code:</b></div>
            <div class="fs-5 ps-4"><?php echo isset($code) ? $code : '' ?></div>
        </div>
        <div class="w-100 mb-1">
            <div class="fs-6"><b>Client:</b></div>
            <div class="fs-5 ps-4"><?php echo isset($fullname) ? $fullname : '' ?></div>
        </div>
        <div class="w-100 mb-1">
            <div class="fs-6"><b>Reading Range:</b></div>
            <div class="fs-5 ps-4"><?php echo isset($month_from) && isset($month_to) ? date("M, Y",strtotime($month_from)) . ' - ' .date("M, Y",strtotime($month_to)) : '' ?></div>
        </div>
        <div class="w-100 mb-1">
            <div class="fs-6"><b>Charges per Unit:</b></div>
            <div class="fs-5 ps-4"><?php echo isset($charge_per_unit) ? number_format($charge_per_unit) : '' ?></div>
        </div>
        <div class="w-100 mb-1">
            <div class="fs-6"><b>Previous Reading:</b></div>
            <div class="fs-5 ps-4"><?php echo isset($previous_reading) ? ($previous_reading) : '' ?></div>
        </div>
        <div class="w-100 mb-1">
            <div class="fs-6"><b>Current Reading:</b></div>
            <div class="fs-5 ps-4"><?php echo isset($current_reading) ? ($current_reading) : '' ?></div>
        </div>
        <div class="w-100 mb-1">
            <div class="fs-6"><b>Consumed Unit:</b></div>
            <div class="fs-5 ps-4"><?php echo isset($consumed) ? ($consumed) : '' ?></div>
        </div>
        <div class="w-100 mb-1">
            <div class="fs-6"><b>Payable Amount:</b></div>
            <div class="fs-5 ps-4"><?php echo isset($amount) ? number_format($amount,2) : '' ?></div>
        </div>
        <div class="w-100 mb-1">
            <div class="fs-6"><b>Payment Due:</b></div>
            <div class="fs-5 ps-4"><?php echo isset($due) ? date("M d, Y",strtotime($due)) : '' ?></div>
        </div>
        <div class="w-100 mb-1">
            <div class="fs-6"><b>Status:</b></div>
            <div class="fs-5 ps-4">
                <?php 
                    if($status == 1){
                        echo  '<span class="py-1 px-3 badge rounded-pill bg-success"><small>Paid</small></span>';
                    }else{
                        if(strtotime(date("Y-m-d")) > strtotime($due)){
                            echo  '<span class="py-1 px-3 badge rounded-pill bg-danger"><small>Over Due</small></span>';
                        }else{
                            echo  '<span class="py-1 px-3 badge rounded-pill bg-warning"><small>Pending</small></span>';
                        }
                    }
                ?>
            </div>
        </div>
        <div class="w-100 d-flex justify-content-end">
            <button class="btn btn-sm btn-dark rounded-0" type="button" data-bs-dismiss="modal">Close</button>
        </div>
    </div>
</div>