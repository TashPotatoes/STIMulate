<?php include "header.php"; ?>
        <div class="timetable-filter">
            <div class="filter-stream">
                <ul>
                    <li id="filterStreamIt"class="stream-select IT">IT</li>
                    <li id="filterStreamSc"class="stream-select SC">Science</li>
                    <li id="filterStreamMa"class="stream-select MA">Maths</li>
                    <li id="filterStreamDH"class="stream-select DH">Duty Hosts</li>
                </ul>
                <span class="clearfix"></span>
            </div>

            <div class="filter-spec">specialisation filters go here</div>
        </div>

        <?php include('include/timetable.php'); ?>
        
    </div>
    </body>
</html>
