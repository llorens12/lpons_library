$(document).ready(function()
    {
         $('.sub-menu').hover(function()
         {
             $(this).addClass('open');

         },function()
         {
             $(this).removeClass('open');
             console.log("sale");
         });
    }
);


function registerContent(event){

    var pwd = $('#pwd');
    var pwd1 = $('#pwd1');
    var email = $('#email');


    if(!emailDisponibility() || pwd.val() !== pwd1.val()){
        $('#error').html('<h5><span class="label label-danger">Incorrect fields</span></h5>');
        pwd.val("").parent().addClass("has-error");
        pwd1.val("").parent().addClass("has-error");
        email.val("").parent().addClass("has-error");

        return false;
    }
    return true;
}

function emailDisponibility(){

    var email = $('#email').val();
    var dataString = "ajax=emailDisponibility&email="+email;
    var disponibility;

    $.ajax
    ({
        type: "POST",
        url: "controller.php",
        async: false,
        cache: false,
        timeout: 300000,
        data: dataString,
        success: function(data)
        {
            disponibility = (data == "true");
        }
    });
    console.log(disponibility);
    return disponibility;
}