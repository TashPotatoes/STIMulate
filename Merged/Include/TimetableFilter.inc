
<div class = "filter">
    <div class = "headElement">
        <img src="IMG/calander.png" alt="Calander" class = "inline-image">
        <h2 class = "inline-text">Stream</h2>
    </div>
    <span class = "filter-stream">
        <ul>
            <?php generateStreamFilters($showSearch); ?>
        </ul>
    </span>

</div>
<?php if(isset($showSearch)){
    if($showSearch) {?>
<div class = "filter">
        <div class = "headElement">
        <img src="IMG/calander.png" alt="Calander" class = "inline-image">
        <h2 class = "inline-text">Specialisation</h2>
    </div>
    <span class = "searchbar">
        <img src="IMG/search.png" class = "inline-image searchIcon">
        <input type="text" placeholder="Search Topics, Streams, or Units..." class = "searchSpan">
    </span>
</div>
<?php }}?>

<?php

function generateStreamFilters($showSearch = false) {
    $sqlObject = new \PHP\SqlObject("SELECT str_shortname, str_name FROM streams");
    $data = $sqlObject->Execute();

    foreach ($data as $stream) {
        $shortcode = ucfirst(strtolower($stream['str_shortname']));
        echo "<li id='filterStream".$shortcode."' class='stream-filters'>".$stream['str_name']."</li>";
    }

    if(!$showSearch) {
    ?>
    <span class = "searchbar specialSearchSpan">
        <img src="IMG/search.png" class = "inline-image searchIcon">
        <input type="text" placeholder="Search Topics, Streams, or Units..." class = "searchSpan">
    </span>
    <?php }
}
?>