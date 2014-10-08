// STREAM FILTERS
$(document).ready(function(){
    Initialise();
    $(document).tooltip();
})


function Initialise(){
    $("#filterStreamIt, #filterStreamSc, #filterStreamMa, #filterStreamDh ").on("click", function() {
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
    $(".namecard").parent().css("background-color", "white");


}
function UnHideTableElements() {
    if( $(".specfilter").css("display") ) {
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
	 	"BPM",
	 	"teensy",
        "c++",
        "c#"
	 ];


    var fac_spec_matrix = [
        ['python','n8571091', 'n1000001'],
        ['BPM','n8571091', 'n1000014', 'n1000009' ],
        ['teensy', 'n1000021','n1000022'],
        ['C++', 'n1000014', 'n1000009' ],
        ['C#', 'n1000014', 'n1000011' ]
    ];


    $("#SpecFilter").autocomplete({
        source: Specialisations,
        search: function( event, ui ) {},
        close: function( event, ui ) {}
    });
    $("#SpecFilter").on("autocompleteclose", function() { 
        var specIndex = $.inArray(this.value, Specialisations);
        var test = $.inArray(this.value, fac_spec_matrix);
        console.log(fac_spec_matrix);
        console.log(test);
        if (specIndex != -1) {
            ResetSpecialisationFilters();
            $.each(fac_spec_matrix[specIndex], function( index, value ) {
                $(".namecard").css("opacity", ".4");
                if(index > 0) {
                    value = "." + value;
                    $(value).not(".absent").parent().css("background-color", "rgb(240,240,255)");
                    $(value).not(".absent").css("opacity", "1");
               }
            });
        }
    });

}
