<?php if (!empty($destination)) { ?>
    <?php if (!empty($destination['image'])) { ?>
        <ul class="bxslider1">
            <li><img src="<?= $this->config->item('destinations_url') . $destination['image'] ?>" /></li>
        </ul>
    <?php } ?>
    <div class="row">
        <div class="col-xs-12">
            <div class="padding">
                <h2><?= $destination['name'] ?> <div class="floatright"><a href="<?= $this->config->item('base_url') ?>destination/packages/<?= str_replace(' ', '-', $destination['name']) . "/" . $destination['sn'] ?>" class="btn btn-warning">View Packages</a></h2>
                <?= $destination['description'] ?>

            </div>
        </div>
    </div>
<?php } else { ?>
    <div class="row">
        <div class="col-xs-12">
            <div class="padding">
                Wrong Destination

            </div>
        </div>
    </div>
<?php } ?>
