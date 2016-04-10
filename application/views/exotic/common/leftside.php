
<div class="col-xs-12 col-md-3 desktopVersion destinationPaddingRight0" >
    <div class="bluebg destination ">
        <b>Destinations</b>
        <hr class="line" />
        <ul>
            <?php foreach ($destinationMenuArr as $destination) { ?>
                <li><a href="<?= $this->config->item('base_url') ?>destination/packages/<?= str_replace(' ', '-', $destination['name']) . "/" . $destination['sn'] ?>"><?= $destination['name'] ?> </a></li>
            <?php } ?>
        </ul>


    </div>
</div>