/**
 * Created by Hayden on 20/09/14.
 */
$(document).ready(function(){
    SideBarControls();
});

function SideBarControls(){
    $("aside li").click(function(){
        window.location=$(this).find("a").attr("href");
        return false;
    });
}