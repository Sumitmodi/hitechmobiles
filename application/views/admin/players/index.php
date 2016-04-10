<div class="table-responsive">
    <?php if (!empty($status)) { ?>
        <div class="alert alert-success">
            <?= $status ?>
        </div>
    <?php } ?>
    <table class="table table-bordered table-hover table-stripped">
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Gender</th>
            <th>Status</th>
            <th></th>
        </tr>
        <?php
        $i = 1;
        foreach ($resultArr as $result) {
            ?>
            <tr>
                <td><?= $i ?></td>
                <td><?= $result['firstname']." ".$result['lastname'] ?></td>
                <td><?= $result['email'] ?></td>
                <td><?= $result['gender'] ?></td>
                <td><?= ($result['status'] == "Y" ? "Enable" : "Disable") ?></td>
                <td>
                    <a href="/admin/players/edit/<?= $result['sn'] ?>" class="btn btn-success">View</a>
                </td>
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
            if (conf){
                location.href='/admin/teams/delete/'+$sn;
                return false;
            }else{
                return false;
            }
        });
    });
</script>