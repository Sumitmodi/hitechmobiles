<div class="table-responsive">
    <?php if (!empty($status)) { ?>
        <div class="alert alert-success">
            <?= $status ?>
        </div>
    <?php } ?>
    <table class="table table-bordered table-hover table-stripped">
        <tr>
            <th>#</th>
            <th>Image</th>
            <th>Brochure Name</th>
            <th>PDF Attachment</th>
            <th>Status</th>
            <th><a href="<?= base_url() ?>admin/brochures/edit/0" class="btn btn-success">Add</a></th>
        </tr>
        <?php
        $i = 1;
        foreach ($resultArr as $result) {
            ?>
            <tr>
                <td><?= $i ?></td>
                <td><img src="<?= $this->config->item('brochures_url') . 't_' . $result['image'] ?>" /></td>
                <td><?= $result['name'] ?></td>
                <td><?php if (!empty($result['pdf'])) { ?>
                        <a href="<?= $this->config->item('brochures_url') . $result['pdf'] ?>" target="_blank">LINK</a>
                    <?php } ?></td>
                <td><?= ($result['status'] == "Y" ? "Enable" : "Disable") ?></td>
                <td><a href="<?= base_url() ?>admin/brochures/edit/<?= $result['sn'] ?>" class="btn btn-success">Edit</a>
                    <a data-sn="<?= $result['sn'] ?>" class="btn btn-danger btnDelete">Delete</a></td>
            </tr>
            <?php
            $i++;
        }
        ?>
    </table>
</div>
<script>
    $(document).ready(function () {
        $(".btnDelete").click(function () {
            $sn = $(this).attr('data-sn');
            var conf = confirm('Are you sure you want to delete?');
            if (conf) {
                location.href = '<?= base_url() ?>admin/brochures/delete/' + $sn;
                return false;
            } else {
                return false;
            }
        });
    });
</script>