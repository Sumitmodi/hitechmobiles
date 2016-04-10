<?php if (!empty($pages['image']) || $slug == "index") { ?>
    <ul class="bxslider1">
        <?php if (!empty($pages['image'])) { ?>
            <li><img src="<?= $this->config->item('pages_url') . $pages['image'] ?>" /></li>
        <?php } else { ?>
            <li><img src="<?= $this->config->item('base_url') ?>uploads/destinations/singapore.jpg" /></li>
            <li><img src="<?= $this->config->item('base_url') ?>uploads/destinations/maldives.jpg" /></li>
            <li><img src="<?= $this->config->item('base_url') ?>uploads/destinations/Athens-greece.jpg" /></li>
            <li><img src="<?= $this->config->item('base_url') ?>uploads/destinations/sri-lanka.jpg" /></li>
        <?php } ?>
    </ul>
<?php } ?>
<div class="row">
    <div class="col-xs-12">
        <div class="padding">
            <?= $pagesArr['description'] ?>
            <?php if ($slug == "index" && !empty($featuredArr)) { ?>
                <h2>Hot <span>Deals!</span></h2>
                <div class="row">
                    <?php foreach ($featuredArr as $featured) { ?>
                        <?php
                        $packageName = str_replace(' ', '-', $featured['name']); // Replaces all spaces with hyphens.
                        $packageName = preg_replace('/[^A-Za-z0-9\-]/', '', $packageName); // Removes special chars.
                        $packageName = preg_replace('/-+/', '-', $packageName);
                        ?>
                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <a href="<?= base_url() ?>destination/packages_detail/<?= $packageName ?>/<?= $featured['sn'] ?>">
                                <div class="packageBox">
                                    <div class="image" style="background-image:url('<?= (!empty($featured['image']) ? $this->config->item('packages_url') . 'm_' . $featured['image'] : $this->config->item('theme_exotic_url') . "images/noimage.jpg") ?>');">
                                        <div class="hotdeal"></div>
                                    </div>
                                    <div class="">
                                        <div class="col-xs-9 name">
                                            <?= $featured['name'] ?>
                                        </div>
                                        <div class="col-xs-3 days">
                                            <?= $featured['days'] ?> <div><?= ($featured['days'] > 1 ? "DAYS" : "DAY") ?></div>
                                        </div>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>