jQuery(document).ready(function($){
    $('#custom-section h2').click(function(){
        $(this).next().slideToggle(500);
    });
});