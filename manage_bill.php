<?php
require_once("./DBConnection.php");
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
                <?php if(isset($status)): ?>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="status" class="control-label">Change Status</label>
                        <select name="status" id="status" class="form-select form-select-sm rounded-0" required>
                        <option value="0" <?php echo isset($status) && $status == 0 ? 'selected' : '' ?>>Pending</option>
                        <option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>Paid</option>
                        </select>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>

<head>
    <style>
     .form-Group {
width: 650px;
height: 500px;
background-color:white;
display: flex;
flex-direction:column;
padding: 40px;
justify-content:space-around;
align:center;
}

.container h1{
text-align: center;
font-size:14px;
}

.first-row{
display: flex;
}

.owner{
width: 100%;
margin-right: 40px;
}

.input-field{
border: 2px solid green;

}

.input-field input{
width: 100%;
border:none;
outline: none;
padding: 10px;
display: flex;
}

.selection{
display: flex;
justify-content: space-between;
align-items: center;
}

.selection select{
padding: 10px 20px;
}
a{
background-color: blueviolet;
color: white;
text-align: center;
text-transform: uppercase;
text-decoration: none;
padding: 10px;
font-size: 18px;
transition: 0.5s;
}
.button{
    width:100%;
    cursor:pointer;
    margin-bottom:20px;
}
.button button{
    width:100%;
    padding: 10px;
    font-size:20px;
    color:#fff;
    background-color:#806bcd;
    border:none;
    outline:none;
    border-radius: 40px;
    cursor:pointer;
}
.message{
    width:100%;
    position:relative;
    margin-bottom:60px;
    display:flex;
    justify-content:center;
}
.message .success{
    font-size:20px;
    color:green;
    position:absolute;
    animation:buttons .4s linear;
    display:none;
}
.message .danger{
    font-size:20px;
    color:red;
    position:absolute;
    transition: .3s;
    animation:buttons .3s linear;
    display:none;
}
@keyframes buttons{
    0%{
        transform: scale(0.1);
    }
    50%{
        transform: scale(0.5);
    }
    100%{
        transform: scale(1);
    }
}

a:hover{
background-color: dodgerblue;
}

.cards img{
width: 100px;
}
        </style>
        </head>
    <body>
<div class="form-Group">
<h1><font color="blue"><center><b>Confirm Your Payment</center></b></font></h1>
<div class="first-row">
    <div class="owner">
        <h3>Owner:</h3>
        <div class="input-field">
            <input type="text"id="username" autofocus name="username" class="form-control form-control-sm rounded-0" required>
        </div>
    </div>
    <div class="cvv">
        <h3>CVV:</h3>
        <div class="input-field">
            <input type="password"id="password" name="password" class="form-control form-control-sm rounded-0" required>
        </div>
    </div>
</div>
<div class="second-row">
    <div class="card-number">
        <h3>Card Number:</h3>
        <div class="input-field">
            <input type="text" name="cardnumber"id="cardnumber"required>
        </div>
    </div>
</div>
<div class="third-row">
    <h3>Expiry:</h3>
    <div class="selection">
        <div class="date">
            <select name="months" id="months">
                <option value="Jan">Jan</option>
                <option value="Feb">Feb</option>
                <option value="Mar">Mar</option>
                <option value="Apr">Apr</option>
                <option value="May">May</option>
                <option value="Jun">Jun</option>
                <option value="Jul">Jul</option>
                <option value="Aug">Aug</option>
                <option value="Sep">Sep</option>
                <option value="Oct">Oct</option>
                <option value="Nov">Nov</option>
                <option value="Dec">Dec</option>
              </select>
              <select name="years" id="years">
                <option value="2020">2020</option>
                <option value="2019">2019</option>
                <option value="2018">2018</option>
                <option value="2017">2017</option>
                <option value="2016">2016</option>
                <option value="2015">2015</option>
              </select>
        </div>
        <div class="cards">
            <img src="mc.png" alt="">
            <img src="vi.png" alt="">
            <img src="pp.png" alt="">
        </div>
    </div> 
</div> 
    <br>
    <div class="button">
    <button id="send" onclick="message()">PAY</button>
   </div>  
<div class="message">
    <div class="success"id="success">Your Payment was Successfull!!!</div>
    <div class="danger"id="danger">Fields cannot be Empty</div>
</div>
<script src="manage_bill.js"></script>
<script>
    function get_previous(){
        var connection_id = $('#connection_id').val()

        $.ajax({
            url:'././Actions.php?a=get_previous',
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
                url:'././Actions.php?a=save_bill',
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
                     $('#uni_modal button[type="submit"]').text('PAY')
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
