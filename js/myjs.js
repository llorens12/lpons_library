$(document).ready(function()
    {
         $('.sub-menu').hover(function()
         {
             $(this).addClass('open');

         },function()
         {
             $(this).removeClass('open');
         });


        $("#btn-personalized-reserve").click(function(){

            var thisBtn = $(this);
            var btn20days = $("#btn-reserve-20-days");
            var personalizedReserve = $("#personalized-reserve");

            if (!thisBtn.hasClass('show-personalized'))
            {
                thisBtn.addClass("show-personalized");
                personalizedReserve.removeClass("hidden");
                btn20days.attr("disabled",true);

            }
            else
            {
                thisBtn.removeClass("show-personalized");
                personalizedReserve.addClass("hidden");
                btn20days.attr("disabled",false);
            }

        });

        $("#search").keypress(function(e)
        {
            if(e.which == 13)
            {
                $("#form-filter-menu").submit();
            }
        });


        $('#date-start, #date-finish').attr("value", function(){

            var currentDate = new Date();
            return currentDate.getFullYear() + "-" + (currentDate.getMonth() +1) + "-" + currentDate.getDate();
        });

        var email = $('#email').val();

        $('#email').blur(function(){

            if($(this).val() != email && !disponibilityAjax("ajax=emailDisponibility&email="+$(this).val()))
            {
                $(this).attr("placeholder","This email is not available").val("").parent().addClass("has-error");
            }
            else
            {
                $(this).parent().removeClass("has-error");
            }
        });


        var isbn = $('#isbn-form').val();

        $('#isbn-form').blur(function(){


            if($(this).val() != isbn && !disponibilityAjax("ajax=book&isbn="+$(this).val()))
            {
                $(this).attr("placeholder","This ISBN is not available").val("").parent().addClass("has-error");
            }
            else
            {
                $(this).parent().removeClass("has-error");
            }
        });

    }

);

function checkRegisterContent(){

    var pwd = $('#pwd');
    var pwd1 = $('#pwd1');

    if(pwd.val() !== pwd1.val() || pwd.val() == ""){
        $('#error').html('<h5><span class="label label-danger">Incorrect fields</span></h5>');
        pwd.val("").parent().addClass("has-error");
        pwd1.val("").parent().addClass("has-error");

        return false;
    }
    return true;
}


function checkReserveDisponibility(){

    var valDateStart  = $("#date-start").val();
    var valDateFinish = $("#date-finish").val();

    var dataCheck = "ajax=reserveDisponibility&isbn="+$('form[isbn]').attr('isbn')+"&dateStart="+valDateStart+"&dateFinish="+valDateFinish;
console.log(dataCheck);
    if(disponibilityAjax(dataCheck)){
        return true;
    }

    $("#label-error-personalized-reserve").removeClass("hidden");
    return false;


}




function disponibilityAjax(dataString){


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
    return disponibility;
}