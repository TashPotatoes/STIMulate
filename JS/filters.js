// STREAM FILTERS
$(document).ready(function(){
    Initialise();
    $(document).tooltip();
})

/* Function to listen for stream button clicks */
function Initialise(){
    $("#filterStreamIt, #filterStreamSc, #filterStreamMa, #filterStreamDh ").on("click", function() {
        UnHideTableElements();
        FilterByStream(this.id);
        ResetSpecialisationFilters();
    });

}

/* Reset specialisations on timetable on current stream */
function ResetSpecialisationFilters() {
    $(".namecard").css("opacity", "1");
    $(".namecard").parent().css("background-color", "white");
}

/* Display timetable & specialisation on selecting stream after first loading page*/
function UnHideTableElements() {
    if( $(".specfilter").css("display") ) {
        $(".specfilter").css("display", "block");
        $(".timetableWrapper").css("display", "block");
        $("#filterSelectMsg").remove();
        $("#defaultLogo").remove();
    }
}
/* Display namecards matching the chosen stream / hide the rest */
function FilterByStream(stream) {
	StreamClass = ".f-" + stream.substr(-2).toUpperCase();
	$(".namecard").not(StreamClass).css({"visibility": "hidden","display": "none"});
	$(StreamClass).css({"visibility": "visible", "display": "block"})
}

/* Retrieve specialisation data, then filter namecards to match the specialisations */
function FilterBySpecialisations() {
	var RawSpecialisations;
    var Spec = [];
    var fac_spec_matrix = [];
	$.ajax({ url: 'PHP/DatabaseAPI.php',
		data: {
			"action": 'specialisations',
		},
        	type: 'post',
        	datatype: 'JSON',

		success: function(output) {
            console.log(JSON.parse(output));
			RawSpecialisations = JSON.parse(output);
            console.log(Spec); 
            $.each(RawSpecialisations, function(id, matrix){
                var spec_name = matrix['spec_name'];
                console.log(matrix['user_id'], " has ", spec_name);
                if( $.inArray(spec_name, Spec ) !== -1 ) {
                    console.log(spec_name, " IN ARRAY");
                    var index = Spec.indexOf(spec_name);
                    fac_spec_matrix[index].push(matrix['user_id']);
                } else {
                    console.log("ADDING ",matrix['spec_name']);
                    Spec.push(spec_name);
                    fac_spec_matrix.push([spec_name]);
                    var index = Spec.indexOf(spec_name);
                    console.log(Spec.indexOf(spec_name));
                    fac_spec_matrix[index].push(matrix['user_id']);
                }
                console.log(Spec);
                console.log(fac_spec_matrix);
                console.log("----------");
            });
            console.log(Spec);


        },
        error: function(){
        }
    
    });
        $("#SpecFilter").autocomplete({
            source: Spec,
            search: function( event, ui ) {},
            close: function( event, ui ) {}
        });
        $("#SpecFilter").on("autocompleteclose", function() { 
            var specIndex = $.inArray(this.value, Spec);
            var test = $.inArray(this.value, fac_spec_matrix);
            //console.log(fac_spec_matrix);
            //console.log(test);
            if (specIndex != -1) {
                ResetSpecialisationFilters();
                console.log("++++++++++++++++++++++++");
                console.log(fac_spec_matrix);
                $(".namecard").css("opacity", ".4");
                $.each(fac_spec_matrix[specIndex], function( index, value ) {
                    if(index > 0) {
                        value = "." + value;
                        console.log("! " + value);
                        $(value).not(".absent").parent().css("background-color", "rgb(240,240,255)");
                        $(value).not(".absent").css("opacity", "1");
                    }

                });
            }
        });
}
