<?php
require_once("../DBConnection.php");
if(isset($_GET['id'])){
$qry = $conn->query("SELECT * FROM `client_list` where client_id = '{$_GET['id']}'");
    foreach($qry->fetchArray() as $k => $v){
        $$k = $v;
    }
}
?>
<div class="card">
    <div class="card-header">
        <h5 class="card-title"><?php echo isset($_GET['id'])? "Update" :"Create New" ?> <i><font color="red">Client Content</i></font></h5>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <form action="" id="Client-form">
                <input type="hidden" name="id" value="<?php echo isset($client_id) ? $client_id : '' ?>">
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="firstname" class="control-label"><font color="blue">First Name</font></label>
                                <input type="text" name="firstname" autofocus id="firstname" required class="form-control form-control-sm rounded-0" value="<?php echo isset($firstname) ? $firstname : '' ?>">
                            </div>
                            <div class="form-group">
                                <label for="lastname" class="control-label"><font color="blue">Last Name</font></label>
                                <input type="text" name="lastname"  id="lastname" required class="form-control form-control-sm rounded-0" value="<?php echo isset($lastname) ? $lastname : '' ?>">
                            </div>
                            <div class="form-group">
                                <label for="middlename" class="control-label"><font color="blue">Middle Name</font></label>
                                <input type="text" name="middlename"  id="middlename" class="form-control form-control-sm rounded-0" value="<?php echo isset($middlename) ? $middlename : '' ?>">
                            </div>
                            <div class="form-group">
                                <label for="gender" class="control-label"><font color="blue">Gender</font></label>
                                <select name="gender" id="gender" class="form-select form-select-sm rounded-0" required>
                                    <option <?php echo (isset($gender) && $gender = "Male") ? 'selected' : '' ?>>Male</option>
                                    <option <?php echo (isset($gender) && $gender = "Female") ? 'selected' : '' ?>>Female</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="dob" class="control-label"><font color="blue">Date of Birth</font></label>
                                <input type="date" name="dob"  id="dob" class="form-control form-control-sm rounded-0" value="<?php echo isset($dob) ? $dob : '' ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="contact" class="control-label"><font color="blue">Contact</font></label>
                                <input type="text" name="contact"  id="contact" class="form-control form-control-sm rounded-0" value="<?php echo isset($contact) ? $contact : '' ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="control-label"><font color="blue">Email</font></label>
                                <input type="email" name="email"  id="email" class="form-control form-control-sm rounded-0" value="<?php echo isset($email) ? $email : '' ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="address" class="control-label"><font color="blue">Address</font></label>
                                <textarea name="address" id="address" cols="30" rows="3" class="form-control form-control-sm rounded-0 rounded-0" required><?php echo isset($address) ? $address : '' ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="status" class="control-label"><font color="blue">Status</font></label>
                                <select name="status" id="status" class="form-select form-select-sm rounded-0" required>
                                    <option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>Active</option>
                                    <option value="0" <?php echo isset($status) && $status == 0 ? 'selected' : '' ?>>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card-footer">
        <div class="col-12 d-flex justify-content-end">
            <div class="col-auto">
                <button class="btn btn-primary rounded-0 me-2" form="Client-form">Save</button>
                <a class="btn btn-dark rounded-0" href="./?page=clients">Back</a>
            </div>
        </div>
    </div>
</div>

<script>
    $(function(){
        $('#Client-form').submit(function(e){
            e.preventDefault();
            $('.pop_msg').remove()
            var _this = $(this)
            var _el = $('<div>')
                _el.addClass('pop_msg')
            $('.card-footer button').attr('disabled',true)
            $('.card-footer button[type="submit"]').text('submitting form...')
            $.ajax({
                url:'./../Actions.php?a=save_client',
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
                error:err=>{
                    console.log(err)
                    _el.addClass('alert alert-danger')
                    _el.text("An error occurred.")
                    _this.prepend(_el)
                    _el.show('slow')
                     $('.card-footer button').attr('disabled',false)
                     $('.card-footer button[type="submit"]').text('Save')
                },
                success:function(resp){
                    if(resp.status == 'success'){
                        _el.addClass('alert alert-success')
                        $('.card-footer').on('hide.bs.modal',function(){
                            location.reload()
                        })
                        if("<?php echo isset($client_id) ?>" != 1)
                        _this.get(0).reset();
                    }else{
                        _el.addClass('alert alert-danger')
                    }
                    _el.text(resp.msg)

                    _el.hide()
                    _this.prepend(_el)
                    _el.show('slow')
                    $('#page-container').animate({scrollTop:0},'fast')
                     $('.card-footer button').attr('disabled',false)
                     $('.card-footer button[type="submit"]').text('Save')
                }
            })
        })
    })
</script>