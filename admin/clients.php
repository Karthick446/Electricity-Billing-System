
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title"><font color="red">Client List</font></h3>
        <div class="card-tools align-middle">
            <a class="btn btn-dark btn-sm py-1 rounded-0" href="./?page=manage_client" id="create_new">Add New</a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-hover table-striped table-bordered">
            <colgroup>
                <col width="5%">
                <col width="20%">
                <col width="40%">
                <col width="20%">
                <col width="15%">
            </colgroup>
            <thead>
                <tr bgcolor="orange">
                    <th class="text-center p-0">#</th>
                    <th class="text-center p-0">Date Created</th>
                    <th class="text-center p-0">Client</th>
                    <th class="text-center p-0">Status</th>
                    <th class="text-center p-0">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sql = "SELECT *,(lastname || ', ' || firstname || ' ' || middlename) as fullname FROM client_list order by fullname asc";
                $qry = $conn->query($sql);
                $i = 1;
                    while($row = $qry->fetchArray()):
                ?>
                <tr>
                    <td class="text-center p-0"><font color="red"><?php echo $i++; ?></font></td>
                    <td class="py-0 px-1 text-end"><font color="blue"><?php echo date("Y-m-d H:i",strtotime($row['date_created'])) ?></font></td>
                    <td class="py-0 px-1"><font color="green"><?php echo ucwords($row['fullname']) ?></font></td>
                    <td class="py-0 px-1 text-center">
                        <?php 
                        if($row['status'] == 1){
                            echo  '<span class="py-1 px-3 badge rounded-pill bg-success"><small>Active</small></span>';
                        }else{
                            echo  '<span class="py-1 px-3 badge rounded-pill bg-danger"><small>Inactive</small></span>';

                        }
                        ?>
                    </td>
                    <td class="text-center py-0 px-1">
                        <div class="btn-group" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle btn-sm rounded-0 py-0" data-bs-toggle="dropdown" aria-expanded="false">
                            Action
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <li><a class="dropdown-item view_data" href="javascript:void(0)" data-id = '<?php echo $row['client_id'] ?>'>View Details</a></li>
                            <li><a class="dropdown-item" data-id = '<?php echo $row['client_id'] ?>' href="./?page=manage_client&id=<?php echo $row['client_id'] ?>">Edit</a></li>
                            <li><a class="dropdown-item delete_data" data-id = '<?php echo $row['client_id'] ?>' data-name = '<?php echo $row['fullname'] ?>' href="javascript:void(0)">Delete</a></li>
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
        $('.edit_data').click(function(){
            uni_modal('Edit Client Details',"manage_client.php?id="+$(this).attr('data-id'),'large')
        })
        $('.view_data').click(function(){
            uni_modal('Client Details',"view_client.php?id="+$(this).attr('data-id'),'large')
        })
        $('.delete_data').click(function(){
            _conf("Are you sure to delete <b>"+$(this).attr('data-name')+"</b> from Client List?",'delete_data',[$(this).attr('data-id')])
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
            url:'./../Actions.php?a=delete_client',
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