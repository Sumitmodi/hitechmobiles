<div class="table-responsive">
    <?php if (!empty($status)) { ?>
        <div class="alert alert-success">
            <?= $status ?>
        </div>
    <?php } ?>
    <table class="table table-bordered table-hover table-stripped">
        <tr>
            <th>#</th>
            <th> Name</th>
            <th>Email</th>
            <th></th>
        </tr>
        <?php
        $i = 1;
        foreach ($resultArr as $result) {
            ?>
            <tr>
                <td><?= $i ?></td>
        
                <td><?= $result['name'] ?></td>
                <td><?= $result['email'] ?></td>
                <td><a href="<?= base_url() ?>admin/enquiries/edit/<?= $result['sn'] ?>" class="btn btn-success">View</a></td>
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
                location.href = '<?= base_url() ?>admin/flyers/delete/' + $sn;
                return false;
            } else {
                return false;
            }
        });
    });
</script>