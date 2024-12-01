<?php
require_once("../DBConnection.php");
if(isset($_GET['id'])){
$qry = $conn->query("SELECT * FROM `connection_list` where connection_id = '{$_GET['id']}'");
    foreach($qry->fetchArray() as $k => $v){
        $$k = $v;
    }
}
?>
<div class="container-fluid">
    <form action="" id="connection-form">
        <input type="hidden" name="id" value=""><?php echo isset($connection_id) ? $connection_id : ''?>
        <div class="form-group">
            <label for="client_id" class="control-label"><font color="blue">Client</font></label>
            <select name="client_id" id="client_id" class="form-select form-select-sm rounded-0" required>
                <option disabled <?php echo !isset($client_id) ? "selected" : '' ?>>Please select client here.</option>
                <?php 
                $cwhere="";
                if(isset($client_id))
                $cwhere=" or client_id = '{$client_id}'";
                $client = $conn->query("SELECT *,(lastname || ', ' || firstname || ' ' || middlename) as fullname FROM client_list where status = 1 {$cwhere} order by fullname asc");
                while($row = $client->fetchArray()):
                ?>
                <option value="<?php echo $row['client_id'] ?>" <?php echo isset($client_id) && $client_id == $row['client_id'] ? "selected" : '' ?>><?php echo $row['fullname'] ?></option>
                <?php endwhile; ?>
            </select>
            <?php ?>
        </div>
        <div class="form-group">
            <label for="type" class="control-label"><font color="blue">Connection Type</font></label>
            <select name="type" id="type" class="form-select form-select-sm rounded-0" required>
                <option <?php echo isset($type) && $type == "Resedential" ? 'selected' : '' ?>>Resedential</option>
                <option <?php echo isset($type) && $type == "Commercial" ? 'selected' : '' ?>>Commercial</option>
            </select>
        </div>
        <div class="form-group">
            <label for="meter_default" class="control-label"><font color="blue">Starting Meter Reading</font></label>
            <input type="number" step="any" name="meter_default" id="meter_default" required class="form-control form-control-sm rounded-0" value="<?php echo isset($meter_default) ? $meter_default : '' ?>">
        </div>
        <div class="form-group">
            <label for="status" class="control-label"><font color="blue">Status</font></label>
            <select name="status" id="status" class="form-select form-select-sm rounded-0" required>
                <option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>Active</option>
                <option value="0" <?php echo isset($status) && $status == 0 ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
    </form>
</div>

<script>
    $(function(){
        $('#connection-form').submit(function(e){
            e.preventDefault();
            $('.pop_msg').remove()
            var _this = $(this)
            var _el = $('<div>')
                _el.addClass('pop_msg')
            $('#uni_modal button').attr('disabled',true)
            $('#uni_modal button[type="submit"]').text('submitting form...')
            $.ajax({
                url:'./../Actions.php?a=save_connection',
                method:'POST',
                data:$(this).serialize(),
                dataType:'JSON',
                error:err=>{
                    console.log(err)
                    _el.addClass('alert alert-danger')
                    _el.text("An error occurred.")
                    _this.prepend(_el)
                    _el.show('slow')
                     $('#uni_modal button').attr('disabled',false)
                     $('#uni_modal button[type="submit"]').text('Save')
                },
                success:function(resp){
                    if(resp.status == 'success'){
                        _el.addClass('alert alert-success')
                        $('#uni_modal').on('hide.bs.modal',function(){
                            location.reload()
                        })
                        if("<?php echo ISSET($connection_id) ?>" != 1)
                        _this.get(0).reset();
                    }else{
                        _el.addClass('alert alert-danger')
                    }
                    _el.text(resp.msg)

                    _el.hide()
                    _this.prepend(_el)
                    _el.show('slow')
                     $('#uni_modal button').attr('disabled',false)
                     $('#uni_modal button[type="submit"]').text('Save')
                }
            })
        })
    })
</script>