
<div class="row">
    <div class="col-xs-12">
        <div class="padding">
            <?php if (!empty($destination)) { ?>
                <h2>Packages - <span><?= $destination['name'] ?></span></h2>
            <?php } else { ?>
                <p>Sorry, Wrong Destination</p>
            <?php } ?>

            <div class="row">
                <?php foreach ($packagesArr as $package) { ?>
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <?php
                        $packageName = str_replace(' ', '-', $package['name']); // Replaces all spaces with hyphens.
                        $packageName = preg_replace('/[^A-Za-z0-9\-]/', '', $packageName); // Removes special chars.
                        $packageName = preg_replace('/-+/', '-', $packageName);
                        ?>
                        <a href="<?= base_url() ?>destination/packages_detail/<?= $packageName ?>/<?= $package['sn'] ?>">
                            <div class="packageBox">
                                <div class="image" style="background-image:url('<?= (!empty($package['image']) ? $this->config->item('packages_url') . 'm_' . $package['image'] : $this->config->item('theme_exotic_url') . "images/noimage.jpg") ?>');">
                                    <?php if ($package['featured'] == "Y") { ?>
                                        <div class="hotdeal"></div>
                                    <?php } ?>
                                </div>
                                <div class="">
                                    <div class="col-xs-9 name">
                                        <?= $package['name'] ?>
                                    </div>
                                    <div class="col-xs-3 days">
                                        <?= $package['days'] ?> <div><?= ($package['days'] > 1 ? "DAYS" : "DAY") ?></div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>