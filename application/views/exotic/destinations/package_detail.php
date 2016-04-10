<?php if (!empty($package)) { ?>
    <?php if (!empty($package['image'])) { ?>
        <ul class="bxslider1">
            <li><img src="<?= $this->config->item('packages_url') . $package['image'] ?>" /></li>
        </ul>
    <?php } ?>
    <div class="row">
        <div class="col-xs-12">
            <div class="padding">
                <h2><?= $package['name'] ?> <div class="floatright"><a href="<?= $this->config->item('base_url') ?>pages/enquiry/<?= str_replace(' ', '-', $package['name']) . "/" . $package['sn'] ?>" class="btn btn-warning"><span class="price"><?= $package['price'] ?></span><br/>Enquire Now</a></h2>
                <b><?= $package['days'] ?> <?= ($package['days'] > 1 ? "DAYS" : "DAY") ?></b>
                <?= $package['description'] ?>
                <a href="<?= $this->config->item('base_url') ?>pages/enquiry/<?= str_replace(' ', '-', $package['name']) . "/" . $package['sn'] ?>" class="btn btn-warning"><span class="price"><?= $package['price'] ?></span><br/>Enquire Now</a>
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
