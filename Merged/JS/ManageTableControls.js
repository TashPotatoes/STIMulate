/**
 * Created by Hayden on 10/10/2014.
 */

checking = false;

$(document).ready(function(){
    LoadTableInteractions();
});

function LoadTableInteractions(){
    $(document).on('click', '.check-head', function(){
       //$('input[type="checkbox"]').prop('checked', !$(this).is(":checked"));

        CheckAll(checking);

        $("input:checked").each(function() {
            $(this).closest('tr').css({'background-color':'rgb(245,245,245)'});
        });
    });

    $(document).on('change', 'input[type="checkbox"]', function(){
        if($(this).prop('checked')){
            $(this).closest('tr').css({'background-color':'rgb(245,245,245)'});
        } else {
            $(this).closest('tr').css({'background-color':'inherit'});
        }
    });
}

function CheckAll(check){

        if(!check) {
            $("input[type =\"checkbox\"]").each(function () {
                $(this).prop('checked', "checked");
            });
            checking = true;
        } else {
            $("input[type =\"checkbox\"]").each(function () {
                $(this).removeAttr("checked");
            });
            checking = false;
        }
}