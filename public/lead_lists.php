<div class="panel fb-panel fb_sc_maindiv lm_ecolabs_cg7_maindiv">
    <div class="shifts-overlay adminoverlays">
    </div>
    <h3 class="card-title">Lead list</h3>
    <input type="hidden" id="exporturl" value="<?= $exporturl ?>">
    <div class="row card-body">

        <form id="filterform" >
            <div class="row">
                    <select class="fb_input" name="lead_type">
                        <option value="0">All Leads</option>
                        <?php
                        foreach ( $formtypes as $formt ) {
                            echo '<option value="'.$formt->ID.'">'.$formt->post_title.'</option>';
                        }
                        ?>
                    </select>
                    <input type="date" class=" fb_input" name="from_date">
                    <input type="date" class=" fb_input" name="to_date">
                    <select class="fb_input" name="region">
                        <option value="0">Region</option>
                        <option value="AU">AU</option>
                        <option value="NZ">NZ</option>
                        <option value="ANZ">ANZ</option>
                    </select>

                    <input name="fb_filter_submit_btn" class="button button-primary fb_sc_submitbtn fb_filter_submit_btn" id="fb_filter_submit_btn" type="submit" value="Filter">

                    <input class="button button-primary fb_sc_submitbtn fb_filter_export_btn" id="fb_filter_export_btn" type="submit" value="Export">


            </div>
        </form>
        <div class="row leadlist-table">

        </div>

    </div> <!-- row -->
</div>
