<div class="tablediv md12" data-table="leadlist">

    <table class="table table-sm table-bordered table-hover table-condensed fb_sc_table leadlisttable">
        <thead>
            <tr>
                <th>Lead Type</th>
                <th>Lead Details</th>
                <th>Date</th>
                <th>Date</th>
                <th>Region</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ( $rows as $val ) :
                $dets = json_decode($val->details);
                $fullDetails = "";
                $partialDetails = "";
                $ctr = 0;
                foreach ($dets as $detK => $detV){
                    $fullDetails .= $detK . ": " . $detV . "<br>";
                    if ( $ctr <= 1 ) {
                        $partialDetails .= $detK . ": " . $detV . "<br>";
                    }
                    $ctr++;
                }

                ?>
            <tr data-id="<?= $val->form_id ?>">
                <td><?= $val->post_title ?></td>
                <td class="details-col">
                    <span class="fullDetails">
                        <?= $fullDetails ?>
                    </span>
                    <span class="partialDetails">
                        <?= $partialDetails ?>
                    </span>
                    <a class="seemore" data-val="0">See more...</a>
                    </td>
                <td><?= $val->form_date_created ?></td>
                <td></td>
                <td></td>
                <!-- <td>
                    <div class="btn-group btn-group-toggle">
                        <button class="btn btn-sm btn-info btn-edit-loc"><span class="dashicons dashicons-edit"></span></button>
                        <button class="btn btn-sm btn-danger btn-delete-loc"><span class="dashicons dashicons-trash"></span></button>
                    </div>
                </td> -->
            </tr>
            <?php endforeach;?>
        </tbody>

    </table>


    <nav >
      <ul class="pagination">
        <?php
        for ($a=1; $a <= ceil($count/$limit); $a++) :

        ?><li class="page-item <?php echo ($page+1==$a) ? 'active' : '' ?>">
    <a class="page-link" href="#" data-page="<?= $a ?>"><?= $a ?></a>
    </li><?php
        endfor;
        ?>
      </ul>
    </nav>
</div>
