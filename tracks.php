<style>
    header#cover {
        height: 70vh;
        position: relative;
    }
</style>
<div class="w-100 h-100">
    <header id="cover">
        <div class="container-fluid h-100 d-flex flex-column justify-content-center align-items-end">
            <div class="flex-grow-1 d-flex flex-column justify-content-center align-items-center w-100">
                <div id="banner-site-title" class="w-100 text-center wow fadeIn" data-wow-duration="1.2s"><font color="yellow">Client Detail</font></div>
                <div id="banner-sub-title" class="w-100 text-center wow fadeIn" data-wow-duration="1.2s"><span><font color="orange">Electricity Billing System</font></span></div>
            </div>
            <div class="w-100 d-flex justify-content-center">
                <div class="card col-md-6 wow bounceInDown">
                    <div class="card-body">
                        <form action="" method="GET" id="track-form">
                            <div class="form-group">
                                <label for="code" class="control-label"><font color="blue">Enter your Connection Code</font></label>
                                <input type="text" autofocus name="code" id="code" class="form-control" required value="<?php echo isset($_GET['code']) ? $_GET['code'] :'' ?>" placeholder="XXXXXXXXXXX">
                            </div>
                            <div class="form-group mt-2">
                                <center>
                                    <button class="btn btn-primary btn-sm rounded-pill">Show Bills</button>
                                </center>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
        </div>
    </header>
    <div class="flex-grow-1 bg-light mb-0 mt-3">
        <?php if(!isset($_GET['code'])): ?>
        <section class="wow bounceInUp"  data-wow-delay=".5s" data-wow-duration="1.5s">
            <div class="container">
                <h3 class="text-center text-muted"><b><font color="bleachedalmond">Enter you connection's code to view the list of your bills.</font></b></h3>
            </div>
        </section>
        <?php else: ?>
        <section class="wow bounceInUp"  data-wow-delay=".5s" data-wow-duration="1.5s">
            <?php 
            if(isset($_GET['code'])){
                $qry = $conn->query("SELECT c.*,cl.connection_id,cl.code,cl.type,(c.lastname || ', ' || c.firstname || ' ' || c.middlename) as fullname FROM `connection_list` cl inner join client_list c on cl.client_id = c.client_id where cl.code = '{$_GET['code']}'")->fetchArray();
                    if($qry){
                        foreach($qry as $k => $v){
                            $$k = $v;
                        }   
                    }
                }
            ?>
            <?php if(!isset($connection_id)): ?>
            <div class="container">
                <h3 class="text-center text-danger"><b>Connection Code is Invalid</b></h3>
            </div>
            <?php else: ?>
            <div class="container">
                <h3><center><font color="green">Connection's Billing History</font></center></h3>
                <hr>
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="w-100 mb-1">
                                <div class="fs-6"><b><font color="blue">Client Name:</font></b></div>
                                <div class="fs-5 ps-4"><?php echo isset($fullname) ? $fullname : '' ?></div>
                            </div>
                            <div class="w-100 mb-1">
                                <div class="fs-6"><b><font color="blue">Gender:</font></b></div>
                                <div class="fs-5 ps-4"><?php echo isset($gender) ? $gender : '' ?></div>
                            </div>
                            <div class="w-100 mb-1">
                                <div class="fs-6"><b><font color="blue">Date of Birth:</font></b></div>
                                <div class="fs-5 ps-4"><?php echo isset($dob) ? date("F d, Y",strtotime($dob)) : '' ?></div>
                            </div>
                            <div class="w-100 mb-1">
                                <div class="fs-6"><b><font color="blue">Contact:</font></b></div>
                                <div class="fs-5 ps-4"><?php echo isset($contact) ? $contact : '' ?></div>
                            </div>
                            <div class="w-100 mb-1">
                                <div class="fs-6"><b><font color="blue">Email:</font></b></div>
                                <div class="fs-5 ps-4"><?php echo isset($email) ? $email : '' ?></div>
                            </div>
                            <div class="w-100 mb-1">
                                <div class="fs-6"><b><font color="blue">Address:</font></b></div>
                                <div class="fs-6 ps-4 fw-light lh-1"><?php echo isset($address) ? $address : '' ?></div>
                            </div>
                            <div class="w-100 mb-1">
                                <div class="fs-6"><b><font color="blue">Status:</font></b></div>
                                <div class="fs-6 ps-4 fw-light lh-1">
                                    <?php if($status == 1): ?> 
                                        <span class="badge bg-success rounded-pill px-2">Active</span>   
                                    <?php else: ?>    
                                        <span class="badge bg-danger rounded-pill px-2">Inactive</span>   
                                    <?php endif; ?>    
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="w-100 mb-1">
                                        <div class="fs-6"><b><font color="blue">Connection Code:</font></b></div>
                                        <div class="fs-5 ps-4"><?php echo isset($code) ? $code : '' ?></div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="w-100 mb-1">
                                        <div class="fs-6"><b><font color="blue">Type:</font></b></div>
                                        <div class="fs-5 ps-4"><?php echo isset($type) ? $type : '' ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="fs-6"><b><font color="blue">Billing List:</font></b></div>
                            <table class="table table-hover table-striped table-bordered"border="5">
                                <colgroup>
                                    <col width="5%">
                                    <col width="15%">
                                    <col width="20%">
                                    <col width="25%">
                                    <col width="35%">
                                    <col width="10%">
                                </colgroup>
                                <thead>
                                    <tr bgcolor="orange">
                                        <th class="text-center p-0">#</th>
                                        <th class="text-center p-0">Date Added</th>
                                        <th class="text-center p-0">Reading Range</th>
                                        <th class="text-center p-0">Connection</th>
                                        <th class="text-center p-0">Amount</th>
                                        <th class="text-center p-0">Status</th>
                                        <th class="text-center p-0">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $sql = "SELECT b.*, cl.code FROM bill_list b inner join connection_list cl on b.connection_id = cl.connection_id where cl.connection_id = '{$connection_id}' order by strftime('%s',b.date_created) desc";
                                    $qry = $conn->query($sql);
                                    $i = 1;
                                        while($row = $qry->fetchArray()):
                                    ?>
                                    <tr >
                                        <td class="text-center p-0"><font color="red"><?php echo $i++; ?></font></td>
                                        <td class="py-0 px-1 text-end"><font color="megenta"><?php echo date("Y-m-d H:i",strtotime($row['date_created'])) ?></font></td>
                                        <td class="py-0 px-1"><font color="green"><?php echo date("M Y",strtotime($row['month_from'])).' - '.date("M Y",strtotime($row['month_to'])) ?></font></td>
                                        <td class="py-0 px-1 lh-1">
                                            <span class="fw-bold"><font color="bleachedalmond"><?php echo $row['code'] ?></font></span><br>
                                        </td>
                                        <td class="py-0 px-1 text-end"><center><font color="olive"><?php echo number_format($row['amount'],2) ?></font></center>
                                        <div class="form-group">
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
                                        <td class="text-center py-0 px-1">
                        <div class="btn-group" role="group">
                            
                            <button class="btn btn-success form-control" data-toggle="modal"  data-target="#PAY">
                            <a class="dropdown-item edit_data" data-id = '<?php echo $row['bill_id'] ?>' href="javascript:void(0)">PAY</a>
                            </button>
                        </div>
                    </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                                        </table>
                                        <center><img src="download.png"></center>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </section>
        <?php endif; ?>
    </div>
</div>
<script>
$(function(){
    $('#track-form').submit(function(e){
        e.preventDefault()
        location.href = "./?page=tracks&"+$(this).serialize()
    })
    $('table').dataTable()
})
$(document).scroll(function() { 
    $('#topNavBar').removeClass('bg-transaparent bg-dark')
    if($(window).scrollTop() === 0) {
        $('#topNavBar').addClass('bg-transaparent')
    }else{
        $('#topNavBar').addClass('bg-dark')
    }
});
$(function(){
    $(document).trigger('scroll')
})

$(function(){
        $('#create_new').click(function(){
            uni_modal('Create New Billing',"manage_bill.php?",'mid-large')
        })
        $('.edit_data').click(function(){
            uni_modal('PAYMENT GATEWAY',"manage_bill.php?id="+$(this).attr('data-id'),'mid-large')
        })
        $('.view_data').click(function(){
            uni_modal('Bill Details',"view_bill.php?id="+$(this).attr('data-id'),'mid-large')
        })
        $('.delete_data').click(function(){
            _conf("Are you sure to delete this bill from List?",'delete_data',[$(this).attr('data-id')])
        })
        $('table td,table th').addClass('align-middle py-1')
       
    })
</script>