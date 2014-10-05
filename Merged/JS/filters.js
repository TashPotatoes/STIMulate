// STREAM FILTERS
$(document).ready(function(){
    Initialise();
})


function Initialise(){
    $("#filterStreamIt").on("click", function() {
        FilterByStream(this.id);
        UnHideTableElements();
    });
    $("#filterStreamSc").on("click", function() {
        FilterByStream(this.id);
        UnHideTableElements();
    });
    $("#filterStreamMa").on("click", function() {
        FilterByStream(this.id);
        UnHideTableElements();
    });
    $("#filterStreamDh").on("click", function() {
        FilterByStream(this.id);
        UnHideTableElements();
    });

}

function UnHideTableElements() {
    if( $(".specfilter").css("display") ) {
        console.log('UNHIDE IT');
        $(".specfilter").css("display", "block");
        $(".timetableWrapper").css("display", "block");
        $("#filterSelectMsg").remove();
        $("#defaultLogo").remove();
    }
}
function FilterByStream(stream) {
	StreamClass = ".f-" + stream.substr(-2).toUpperCase();
	$(".namecard").not(StreamClass).css({"visibility": "hidden","display": "none"});
	$(StreamClass).css({"visibility": "visible", "display": "block"})
}

function FilterBySpecialisations(specialisations) {
    //not working #oops.
    var output = '';
    for (var property in specialisations) {
        output += property + ': ' + specialisations[property]+'; ';
    }
    document.getElementById('testies').innerHTML(output);

}