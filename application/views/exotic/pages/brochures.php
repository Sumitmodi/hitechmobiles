<?php if (!empty($brochuresArr)) { ?>
    <div class="row">
        <div class="col-xs-12">
            <div class="padding">
                <h2>Brochures</h2>
                <div class="row">
                    <?php foreach ($brochuresArr as $brochure) { ?>
                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <a href="<?= $this->config->item('brochures_url') ?><?= $brochure['pdf'] ?>">
                                <div class="packageBox">
                                    <div class="image" style="background-image:url('<?= (!empty($brochure['image']) ? $this->config->item('brochures_url') . 'm_' . $brochure['image'] : $this->config->item('theme_exotic_url') . "images/noimage.jpg") ?>');">
                                    </div>
                                    <div class="">
                                        <div class="col-xs-12 name">
                                            <?= $brochure['name'] ?>
                                        </div>
                                        <?=$brochure['description']?>
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
<?php } else { ?>
    <div class="row">
        <div class="col-xs-12">
            <div class="padding">
                  <h2>Brochures</h2>
                Coming Soon...

            </div>
        </div>
    </div>
<?php } ?>
