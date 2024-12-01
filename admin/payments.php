<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">List of Connection Payments</h3>
        <div class="card-tools align-middle">
            <a class="btn btn-dark btn-sm py-1 rounded-0" href="javascript:void(0)" id="create_new">Add New</a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-hover table-striped table-bordered">
            <colgroup>
                <col width="5%">
                <col width="15%">
                <col width="20%">
                <col width="25%">
                <col width="15%">
                <col width="10%">
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
                $sql = "SELECT b.*,(c.lastname || ', ' || c.firstname || ' ' || c.middlename) as fullname, cl.code FROM bill_list b inner join connection_list cl on b.connection_id = cl.connection_id inner join client_list c on cl.client_id = c.client_id order by strftime('%s',b.date_created) desc";
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
                        <span class="fw-light"><?php echo $row['fullname'] ?></span>
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
                    <td class="text-center py-0 px-1">
                        <div class="btn-group" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle btn-sm rounded-0 py-0" data-bs-toggle="dropdown" aria-expanded="false">
                            Action
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <li><a class="dropdown-item view_data" href="javascript:void(0)" data-id = '<?php echo $row['bill_id'] ?>'>View Details</a></li>
                            <li><a class="dropdown-item edit_data" data-id = '<?php echo $row['bill_id'] ?>' href="javascript:void(0)">Edit</a></li>
                            <li><a class="dropdown-item delete_data" data-id = '<?php echo $row['bill_id'] ?>' data-name = '<?php echo $row['title'] ?>' href="javascript:void(0)">Delete</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    $(function(){
        $('#create_new').click(function(){
            uni_modal('Create New Billing',"manage_bill.php?",'mid-large')
        })
        $('.edit_data').click(function(){
            uni_modal('Edit Billing Details',"manage_bill.php?id="+$(this).attr('data-id'),'mid-large')
        })
        $('.view_data').click(function(){
            uni_modal('Bill Details',"view_bill.php?id="+$(this).attr('data-id'),'mid-large')
        })
        $('.delete_data').click(function(){
            _conf("Are you sure to delete <b>"+$(this).attr('data-name')+"</b> from bill List?",'delete_data',[$(this).attr('data-id')])
        })
        $('table td,table th').addClass('align-middle py-1')
        $('table').dataTable({
            columnDefs: [
                { orderable: false, targets:3 }
            ]
        })
    })
    function delete_data($id){
        $('#confirm_modal button').attr('disabled',true)
        $.ajax({
            url:'./../Actions.php?a=delete_bill',
            method:'POST',
            data:{id:$id},
            dataType:'JSON',
            error:err=>{
                console.log(err)
                alert("An error occurred.")
                $('#confirm_modal button').attr('disabled',false)
            },
            success:function(resp){
                if(resp.status == 'success'){
                    location.reload()
                }else{
                    alert("An error occurred.")
                    $('#confirm_modal button').attr('disabled',false)
                }
            }
        })
    }
</script>