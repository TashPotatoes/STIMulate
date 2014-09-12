// STREAM FILTERS
$(document).ready(function(){
    Initialise();
})


function Initialise(){
    $("#filterStreamIt").on("click", function() {
        FilterByStream(this.id);
    });
    $("#filterStreamSc").on("click", function() {
        FilterByStream(this.id);
    });
    $("#filterStreamMa").on("click", function() {
        FilterByStream(this.id);
    });
    $("#filterStreamDH").on("click", function() {
        FilterByStream(this.id);
    });

}

function FilterByStream(stream) {
	StreamClass = ".f-" + stream.substr(-2).toUpperCase();
	$(".namecard").not(StreamClass).css("opacity", "0.4");
	$(StreamClass).css("opacity", "1")
}

function FilterBySpecialisations(specialisations) {
    //not working #oops.
    var output = '';
    for (var property in specialisations) {
        output += property + ': ' + specialisations[property]+'; ';
    }
    document.getElementById('testies').innerHTML(output);

}