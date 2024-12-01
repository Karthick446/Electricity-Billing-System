<?php
require_once("../DBConnection.php");
if(isset($_GET['id'])){
$qry = $conn->query("SELECT *,(lastname || ', ' || firstname || ' ' || middlename) as fullname FROM `client_list` where client_id = '{$_GET['id']}'");
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
        <div class="row">
            <div class="col-sm-4">
                <div class="w-100 mb-1">
                    <div class="fs-6"><b>Client Name:</b></div>
                    <div class="fs-5 ps-4"><?php echo isset($fullname) ? $fullname : '' ?></div>
                </div>
                <div class="w-100 mb-1">
                    <div class="fs-6"><b>Gender:</b></div>
                    <div class="fs-5 ps-4"><?php echo isset($gender) ? $gender : '' ?></div>
                </div>
                <div class="w-100 mb-1">
                    <div class="fs-6"><b>Date of Birth:</b></div>
                    <div class="fs-5 ps-4"><?php echo isset($dob) ? date("F d, Y",strtotime($dob)) : '' ?></div>
                </div>
                <div class="w-100 mb-1">
                    <div class="fs-6"><b>Contact:</b></div>
                    <div class="fs-5 ps-4"><?php echo isset($contact) ? $contact : '' ?></div>
                </div>
                <div class="w-100 mb-1">
                    <div class="fs-6"><b>Email:</b></div>
                    <div class="fs-5 ps-4"><?php echo isset($email) ? $email : '' ?></div>
                </div>
                <div class="w-100 mb-1">
                    <div class="fs-6"><b>Address:</b></div>
                    <div class="fs-6 ps-4 fw-light lh-1"><?php echo isset($address) ? $address : '' ?></div>
                </div>
                <div class="w-100 mb-1">
                    <div class="fs-6"><b>Status:</b></div>
                    <div class="fs-6 ps-4 fw-light lh-1">
                        <?php if($status == 1): ?> 
                            <span class="badge bg-success rounded-pill px-2">Active</span>   
                        <?php else: ?>    
                            <span class="badge bg-danger rounded-pill px-2">Inactive</span>   
                        <?php endif; ?>    
                    </div>
                </div>
                <div class="w-100 d-flex justify-content-end">
                    <button class="btn btn-sm btn-dark rounded-0" type="button" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
            <div class="col-sm-8">
                <h4><b>Connections</b></h4>
                <table class="table table-hover table-striped table-bordered">
                    <colgroup>
                        <col width="5%">
                        <col width="25%">
                        <col width="20%">
                        <col width="25%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th class="text-center p-0">#</th>
                            <th class="text-center p-0">Connection Code</th>
                            <th class="text-center p-0">Type</th>
                            <th class="text-center p-0">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $sql = "SELECT cl.* FROM `connection_list` cl where client_id = '{$client_id}'";
                        $qry = $conn->query($sql);
                        $i = 1;
                            while($row = $qry->fetchArray()):
                        ?>
                        <tr>
                            <td class="text-center p-0"><?php echo $i++; ?></td>
                            <td class="py-0 px-1"><?php echo $row['code'] ?></td>
                            <td class="py-0 px-1"><?php echo $row['type'] ?></td>
                            <td class="py-0 px-1 text-center">
                                <?php 
                                if($row['status'] == 1){
                                    echo  '<span class="py-1 px-3 badge rounded-pill bg-success"><small>Active</small></span>';
                                }else{
                                    echo  '<span class="py-1 px-3 badge rounded-pill bg-danger"><small>Inactive</small></span>';

                                }
                                ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <div class="clearfix"></div>
                <h4><b>Bills</b></h4>
                <table class="table table-hover table-striped table-bordered">
                    <colgroup>
                        <col width="5%">
                        <col width="15%">
                        <col width="20%">
                        <col width="25%">
                        <col width="15%">
                        <col width="10%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th class="text-center p-0">#</th>
                            <th class="text-center p-0">Date Added</th>
                            <th class="text-center p-0">Reading Range</th>
                            <th class="text-center p-0">Connection</th>
                            <th class="text-center p-0">Amount</th>
                            <th class="text-center p-0">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $sql = "SELECT b.*, cl.code FROM bill_list b inner join connection_list cl on b.connection_id = cl.connection_id where cl.client_id = '{$client_id}' order by strftime('%s',b.date_created) desc";
                        $qry = $conn->query($sql);
                        $i = 1;
                            while($row = $qry->fetchArray()):
                        ?>
                        <tr>
                            <td class="text-center p-0"><?php echo $i++; ?></td>
                            <td class="py-0 px-1 text-end"><?php echo date("Y-m-d H:i",strtotime($row['date_created'])) ?></td>
                            <td class="py-0 px-1"><?php echo date("M Y",strtotime($row['month_from'])).' - '.date("M Y",strtotime($row['month_to'])) ?></td>
                            <td class="py-0 px-1 lh-1">
                                <span class="fw-bold"><?php echo $row['code'] ?></span><br>
                            </td>
                            <td class="py-0 px-1 text-end"><?php echo number_format($row['amount'],2) ?></td>
                            <td class="py-0 px-1 text-center">
                                <?php 
                                if($row['status'] == 1){
                                    echo  '<span class="py-1 px-3 badge rounded-pill bg-success"><small>Paid</small></span>';
                                }else{
                                    if(strtotime(date("Y-m-d")) > strtotime($row['due'])){
                                        echo  '<span class="py-1 px-3 badge rounded-pill bg-danger"><small>Over Due</small></span>';
                                    }else{
                                        echo  '<span class="py-1 px-3 badge rounded-pill bg-warning"><small>Pending</small></span>';
                                    }
                                }
                                ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>