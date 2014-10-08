// STREAM FILTERS
$(document).ready(function(){
    Initialise();
})


function Initialise(){
    $("#filterStreamIt").on("click", function() {
        UnHideTableElements();
        FilterByStream(this.id);
        ResetSpecialisationFilters();
    });
    $("#filterStreamSc").on("click", function() {
        UnHideTableElements();
        FilterByStream(this.id);
        ResetSpecialisationFilters();
    });
    $("#filterStreamMa").on("click", function() {
        UnHideTableElements();
        FilterByStream(this.id);
        ResetSpecialisationFilters();
    });
    $("#filterStreamDh").on("click", function() {
        UnHideTableElements();
        FilterByStream(this.id);
        ResetSpecialisationFilters();
    });
    $("#SpecFilter").on("change", function() {
       // console.log($(this).value.text);
        //get the inputs value
        //find elements with ^ value in class
        //do something with them.
    });
}

function ResetSpecialisationFilters() {console.log("BLSADS");
    $(".namecard").css("opacity", "1");

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
	/*var Specialisations; 
	$.ajax({ url: 'PHP/DatabaseAPI.php',
		data: {
			"action": 'specialisation',
			"stream": clickedStream
		},
        	type: 'post',
        	datatype: 'JSON',

		success:  function(output){
			Specialisations = (JSON.Parse(output));
		}
        	error: UnsuccessfulCall
	});*/
		
	 var Specialisations = [
		"python",
		"c++",
		"c#",
		"BPM",
		"teensy"
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
    if (specialisation == 'python') {
        $(".n8571091").css("opacity", "1");
        $(".namecard").not(".n8571091").css("opacity", ".4");
    };
}
