<?php
require_once("../DBConnection.php");
if(isset($_GET['id'])){
$qry = $conn->query("SELECT * FROM `bill_list` where bill_id = '{$_GET['id']}'");
    foreach($qry->fetchArray() as $k => $v){
        $$k = $v;
    }
}
?>
<div class="container-fluid">
    <form action="" id="bill-form">
        <input type="hidden" name="id" value="<?php echo isset($bill_id) ? $bill_id : '' ?>">
        <div class="form-group">
            <label for="connection_id" class="control-label">Connection</label>
            <select name="connection_id" id="connection_id" class="form-select form-select-sm rounded-0" required>
                <option disabled <?php echo !isset($connection_id) ? "selected" : '' ?>>Please select client here.</option>
                <?php 
                $cwhere="";
                if(isset($connection_id))
                $cwhere=" or cl.connection_id = '{$connection_id}'";
                $client = $conn->query("SELECT cl.*,(c.lastname || ', ' || c.firstname || ' ' || c.middlename) as fullname FROM `connection_list` cl inner join client_list c on cl.client_id = c.client_id where cl.status = 1 {$cwhere}  order by `fullname` asc");
                while($row = $client->fetchArray()):
                ?>
                <option value="<?php echo $row['connection_id'] ?>" <?php echo isset($connection_id) && $connection_id == $row['connection_id'] ? "selected" : '' ?>><?php echo $row['code'].' - '.$row['fullname'] ?></option>
                <?php endwhile; ?>
            </select>
            <?php ?>
        </div>
        <div class="form-group">
            <label for="meter_default" class="control-label">Reading Range</label>
            <div class="row mx-0 w-100">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="month_from" class="control-label">From</label>
                        <input type="month" class="form-control form-control-sm rounded-0" name="month_from" id="month_from" required value="<?php echo isset($month_from) ? $month_from : "" ?>">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="month_to" class="control-label">To</label>
                        <input type="month" class="form-control form-control-sm rounded-0" name="month_to" id="month_to" required value="<?php echo isset($month_to) ? $month_to : "" ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="charge_per_unit" class="control-label">Charges Per Unit</label>
            <input type="number" step="any" name="charge_per_unit" id="charge_per_unit" required class="form-control form-control-sm rounded-0" value="<?php echo isset($charge_per_unit) ? $charge_per_unit : $_SESSION['settings']['charge_per_unit'] ?>" readonly>
        </div>
        <div class="form-group">
            <label for="meter_default" class="control-label">Reading Meter</label>
            <div class="row mx-0 w-100">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="previous_reading" class="control-label">Previous</label>
                        <input type="number" class="form-control form-control-sm rounded-0" name="previous_reading" id="previous_reading" required value="<?php echo isset($previous_reading) ? $previous_reading : '' ?>" readonly>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="current_reading" class="control-label">To</label>
                        <input type="number" class="form-control form-control-sm rounded-0" name="current_reading" id="current_reading" required value="<?php echo isset($current_reading) ? $current_reading : "" ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row mx-0 w-100">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="consumed" class="control-label">Consumed Unit</label>
                        <input type="number" step="any" name="consumed" id="consumed" required class="form-control form-control-sm rounded-0" value="<?php echo isset($consumed) ? $consumed : "" ?>" readonly>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="amount" class="control-label">Amount to Payable</label>
                        <input type="number" step="any" name="amount" id="amount" required class="form-control form-control-sm rounded-0 text-end" value="<?php echo isset($amount) ? $amount : "" ?>" readonly>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row mx-0 w-100">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="due" class="control-label">Due Date</label>
                        <input type="date" class="form-control form-control-sm rounded-0" name="due" id="due" required value="<?php echo isset($due) ? $due : "" ?>">
                    </div>
                </div>
             
    </form>
</div>

<script>
    function get_previous(){
        var connection_id = $('#connection_id').val()

        $.ajax({
            url:'./../Actions.php?a=get_previous',
            method:'post',
            data:{connection_id:connection_id,bill_id:"<?php echo isset($bill_id) ? $bill_id : '' ?>"},
            dataType:'json',
            error:err=>{
                console.log(err)
                alert('Cannot get the previous reading for the selected date.')
            },
            success:function(resp){
                if(resp.status == 'success'){
                    $('#previous_reading').val(resp.reading);
                    if($('#current_reading').val() > 0){
                        calculate_consumed()
                    }
                }else{
                    alert('Cannot get the previous reading for the selected date.')
                }
            }
        })
    }
    function calculate_consumed(){
        var prev = $('#previous_reading').val()
        var current = $('#current_reading').val()
        var charge = $('#charge_per_unit').val()

        var consumed = parseFloat(current) - parseFloat(prev);
        var amount = parseFloat(consumed) * parseFloat(charge);

        $('#consumed').val(consumed)
        $('#amount').val(amount)
    }
    $(function(){
        $('#bill-form').submit(function(e){
            e.preventDefault();
            if($('#amount').val() < 0){
                alert("Unable to save the billing due invalid payable amount.");
                return false;
            }
            $('.pop_msg').remove()
            var _this = $(this)
            var _el = $('<div>')
                _el.addClass('pop_msg')
            $('#uni_modal button').attr('disabled',true)
            $('#uni_modal button[type="submit"]').text('submitting form...')
            $.ajax({
                url:'./../Actions.php?a=save_bill',
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
                        if("<?php echo ISSET($bill_id) ?>" != 1)
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
        $('#connection_id').change(function(){
            get_previous()
        })
        $('#current_reading').on('input',function(){
            calculate_consumed()
        })
        
    })
</script>