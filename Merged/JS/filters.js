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
    $("#SpecFilter").on("change", function() {
       // console.log($(this).value.text);
        //get the inputs value
        //find elements with ^ value in class
        //do something with them.
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

function FilterBySpecialisations() {
    var Specialisations = [
    "one",
    "two",
    "three",
    "four",
    "five"
    ];
    $("#SpecFilter").autocomplete({
        source: Specialisations,
        select: function(event, ui) {
            FilterSpecialisations();
        },
        change: function(event, ui) {
            FilterSpecialisations();
        }
    })

}

function FilterSpecialisations() {
    var specialisation = $("#SpecFilter").val();
    console.log(specialisation);
    if (specialisation == 'one') {
        $(".n8571091").css("opacity", "1");
        $(".namecard").not(".n8571091").css("opacity", ".4");
    };
}