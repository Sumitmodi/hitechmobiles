<div class="table-responsive">
    <?php if (!empty($status)) { ?>
        <div class="alert alert-success">
            <?= $status ?>
        </div>
    <?php } ?>
    <h2>All Users</h2>
    <table id="tableSorter" class="table tablesorter table-bordered table-hover table-stripped">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Reset Password</th>
                <th>Status</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <form method="POST">
            <tr>
                <td>1
                    <input type="hidden" name="sn" value="0" />
                </td>
                <td><input type="type" class="form-control" value="" name="name" /></td>
                <td><input type="email" class="form-control" value="" name="email" /></td>
                <td><input type="password" class="form-control" value="" name="password" /></td>
                <td>
                    <input type="radio"  value="Y" checked name="status"  /> Enable
                    <input type="radio"  value="N"  name="status"  /> Disable
                </td>
                <td><input type="submit" class="btn btn-success" value="Add" /></td>
            </tr>
        </form>
        <?php
        $i = 2;
        foreach ($resultArr as $result) {
            ?>
            <form method="POST">
                <tr>
                    <td><?= $i ?><input type="hidden" name="sn" value="<?= $result['sn'] ?>" /></td>
                    <td><input type="type" class="form-control" value="<?= $result['name'] ?>" name="name" /></td>
                    <td><input type="email" class="form-control" value="<?= $result['email'] ?>" name="email" /></td>
                    <td><input type="password" class="form-control" value="" name="password" /></td>
                    <td>
                        <input type="radio"  value="Y" <?= ($result['status'] == "Y" ? "checked" : "") ?> name="status"  /> Enable
                        <input type="radio"  value="N" <?= ($result['status'] == "N" ? "checked" : "") ?> name="status"  /> Disable
                    </td>
                    <td><input type="submit" class="btn btn-success" value="Save" />
                        <a data-sn="<?= $result['sn'] ?>" class="btn btn-danger btnDelete">Delete</a></td>
                </tr>
            </form>
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
                location.href = '<?= base_url() ?>admin/destinations/delete/' + $sn;
                return false;
            } else {
                return false;
            }
        });
    });
</script>