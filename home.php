 <div class="w-100 h-100">
    <header id="cover">
        <div class="container-fluid h-100 d-flex flex-column justify-content-center align-items-end">
            <div class="flex-grow-1 d-flex flex-column justify-content-center align-items-center w-100">
                <div id="banner-sub-title" class="w-100 text-center wow fadeIn" data-wow-duration="1.2s"><span><font color="magenta">Electricity Billing System</font></span></div>
            </div>
            <div class="w-100 d-flex justify-content-center">
                <a href="./?page=tracks" class="btn btn-primary rounded-pill track bills">Track your Connection's Bills</a>
            </div>
            
        </div>
    </header>
    <div class="flex-grow-1 bg-light mb-0">
        <section class="wow slideInRight"  data-wow-delay=".5s" data-wow-duration="1.5s">
            <div class="container">
               <marquee direction="left"><font color="green">WELCOME TO ELECTRICITY BILLING SYSTEM</font></marquee>
               <marquee direction="right"><font color="blue">SAVE ELECTRICITY</font></marquee>
            </div>
        </section>
      <center>  <img src="images.png"width=10%></center>
    </div>
</div>
<script>
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
</script>