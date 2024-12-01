function message(){
    var username = document.getElementById('username');
    var password = document.getElementById('password');
    var cardnumber = document.getElementById('cardnumber');
    const success=document.getElementById('success');
    const danger=document.getElementById('danger'); 

    if (username.value === '' || username.value === '' || cardnumber.value === '' || '')
    {
        danger.style.display = 'block';
    }
    else{
        setTimeout(() => {
          username.value = ''; 
         password.value = '';
          cardnumber.value = '';
        }, 2000);

        success.style.display ='block';
    }

    setTimeout(() => {
        danger.style.display='none';
        success.style.display='none';
    }, 4000);  
}   