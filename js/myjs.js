/**
 * This is execute on document ready.
 */
$(document).ready
(
    function()
    {
        /**
         * This var contains if the current page is the web page to add user reserve.
         * @type {boolean}
         */
         var isFormUserReserve = ($('#form-user-reserve').length > 0);



        /**
         * Open and close the toggle drop (forms buttons).
         */
        $('.sub-menu').hover
         (
             function()
             {
                 $(this).addClass('open');
             },
             function()
             {
                 $(this).removeClass('open');
             }
         );



        /**
         * Administrate behavior to button add personalized reserve.
         */
        $("#btn-personalized-reserve").click
        (
            function()
            {
                var thisBtn = $(this);
                var btn20days = $("#btn-reserve-20-days");
                var personalizedReserve = $("#personalized-reserve");

                if (!thisBtn.hasClass('show-personalized'))
                {
                    thisBtn.addClass("show-personalized");
                    personalizedReserve.removeClass("hidden");
                    btn20days.attr("disabled",true).addClass("not-active");

                    if(isFormUserReserve)
                        $('#totalDays').attr("disabled",true).val("");

                }
                else
                {
                    thisBtn.removeClass("show-personalized");
                    personalizedReserve.addClass("hidden");
                    btn20days.attr("disabled",false).removeClass("not-active");

                    if(isFormUserReserve){
                        $('#totalDays').attr("disabled",false);
                    }
                }

            }
        );



        /**
         * Insert the href in to the default button reserve. (If is the web page to add user reserve).
         */
        if(isFormUserReserve)
        {
            $('#btn-reserve-20-days').click
            (
                function()
                {
                    $(this).attr("href",linkDefaultReserve());
                }
            );
        }



        /**
         * Submit the value of search of the filter data.
         */
        $("#search").keypress
        (
            function(e)
            {
                if(e.which == 13)
                {
                    $("#form-filter-menu").submit();
                }
            }
        );



        /**
         * Insert the actually data in the input date to contains class current-date.
         */
        $('.current-date').attr
        (
            "value",
            function()
            {
                var currentDate = new Date();
                return currentDate.getFullYear() + "-" + (currentDate.getMonth() +1) + "-" + currentDate.getDate();
            }
        );



        /**
         * This var contains the value to the input email before to modified.
         * @type {*|jQuery}
         */
        var email = $('#email').val();

        /**
         * Verify if the inserted email exists or not.
         */
        $('#email').blur
        (
            function()
            {
                if($(this).val() != email && !disponibilityAjax("ajax=emailDisponibility&email="+$(this).val()))
                    $(this).attr("placeholder","This email is not available").val("").parent().addClass("has-error");

                else
                    $(this).parent().removeClass("has-error");

            }
        );



        /**
         * This var contains the first value to the input isbn.
         * @type {*|jQuery}
         */
        var isbn = $('#isbn-form').val();

        /**
         * Verify if the inserted isbn exists or not.
         */
        $('#isbn-form').blur
        (
            function()
            {
                if($(this).val() != isbn && $(this).val() != "" && !disponibilityAjax("ajax=book&isbn="+$(this).val()))
                    $(this).attr("placeholder","This ISBN is not available").val("").parent().addClass("has-error");

                else
                    $(this).parent().removeClass("has-error");

            }
        );
    }
);



/**
 * Check if the register form is correct or not.
 * @returns {boolean}
 */
function checkRegisterContent()
{
    var pwd = $('#pwd');
    var pwd1 = $('#pwd1');

    if(pwd.val() !== pwd1.val() || pwd.val() == "")
    {
        $('#error').html('<h5><span class="label label-danger">Incorrect fields</span></h5>');
        pwd.val("").parent().addClass("has-error");
        pwd1.val("").parent().addClass("has-error");

        return false;
    }
    return true;
}



/**
 * This method generate the link to send the default reserve in add user reserve.
 * @returns {string}
 */
function linkDefaultReserve()
{
    var user = "&user="+$('#reserve-user').attr("value");
    var isbn = "&isbn="+$('#select-book').val();
    var daysReserve = "&days_reserve="+$('#totalDays').val();
    var sent = "";


    if($("#status-sent").is(':checked'))
    {
        var currentDate = new Date();
        sent = "&sent="+currentDate.getFullYear() + "-" + (currentDate.getMonth() +1) + "-" + currentDate.getDate();
    }

    return "controller.php?insert=setInsertUserDefaultReserve"+user+isbn+daysReserve+sent;
}



/**
 * This method check if the reserve is available or not. (In web page add reserve and web page add user reserve).
 * @returns {boolean}
 */
function checkReserveDisponibility()
{
    var valDateStart  = $("#date-start").val();
    var valDateFinish = $("#date-finish").val();
    var form          = $('form[isbn]');
    var user          = "";
    var isbn          = form.attr('isbn');

    if(isbn == "")
    {
        isbn = $('#select-book').val();
        user = "&user="+$('#reserve-user').attr("value");
        var sent = "";


        if($("#status-sent").is(':checked'))
        {
            var currentDate = new Date();
            sent = "&sent="+currentDate.getFullYear() + "-" + (currentDate.getMonth() +1) + "-" + currentDate.getDate();
        }

        form.attr
        (
            'action',
            function()
            {
                return "controller.php?insert=setInsertUserPersonalizedReserve&isbn="+isbn+user+sent;
            }
        );
    }

    var dataCheck = "ajax=reserveDisponibility&isbn="+isbn+"&dateStart="+valDateStart+"&dateFinish="+valDateFinish+user;

    if(disponibilityAjax(dataCheck))
        return true;

    else
    {
        $("#label-error-personalized-reserve").removeClass("hidden");
        return false;
    }
}



/**
 * This method execute ajax synchronous request.
 * @param dataString *Description*: this var contains the url to request.
 * @returns {boolean}
 */
function disponibilityAjax(dataString)
{
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