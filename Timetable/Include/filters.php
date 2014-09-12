<script type="text/javascript">
var specialisation_states = {"one": 0,"two": 0,"three": 0};
function UpdateSpecFilters(filter) {
	var checkBox = document.getElementById(filter.id);
	var checkBoxName = checkBox.name;
	var isChecked = checkBox.checked;
	if(isChecked == true)
	{
		specialisation_states[checkBoxName] = 1;
	} else {
		specialisation_states[checkBoxName] = 0;
	}
	console.log(specialisation_states);
	FilterBySpecialisations(specialisation_states);
}
</script>

<?php
$specs = array("one","two","three");
	echo "<form id='' name=''>";

foreach ($specs as $spec) {
	$fuckyou = $spec;
	$checkboxCode = "<span><input type='checkbox' id='$spec' name='$spec' onchange='UpdateSpecFilters($fuckyou)'/><label for='$spec' onclick>$spec</label></span>";
	echo $checkboxCode;
}
echo "</form>";
?>

</div>

<div id='testies' class="filter-spec">