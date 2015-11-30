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
