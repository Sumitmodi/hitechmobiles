<div class="row text-center">
    <div class="col-xs-12">
        <?php foreach ($resultArr as $result) { ?>
            <?php if (empty($result['parameters'])) { ?>
                <div class="boxStep2 boxBlack"  onclick="steps(<?= $steps ?>, '<?= $result['sn'] ?>');">
                    <?php if (!empty($result['image'])) { ?>
                        <img src="<?= $this->config->item('pick_image_url') . $result['image'] ?>" />
                    <?php } ?>
                    <span class="padding"><?= $result['name'] ?></span>
                </div><br/>
            <?php } else { ?>
                <div class="boxStep2 boxBlack">
                    <span class="padding"><?= $result['name'] ?></span><br/>
                    <div class="dd dd<?=$steps?>" data-stepSN="<?= $result['sn'] ?>">
                        <select name="stepdd" onchange="checkDD(<?= $steps ?>);">
                            <option value="">Select</option>
                            <?php $parameters = explode(",", $result['parameters']); ?>
                            <?php foreach ($parameters as $para) { ?>
                                <option value="<?= $para ?>"><?= $para ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div><br/>
            <?php } ?>
        <?php } ?>
    </div>
</div>