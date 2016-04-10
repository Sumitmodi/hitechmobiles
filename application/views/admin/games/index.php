<div class="table-responsive">
    <?php if (!empty($status)) { ?>
        <div class="alert alert-success">
            <?= $status ?>
        </div>
    <?php } ?>
    <table class="table table-bordered table-hover table-stripped">
        <tr>
            <th>#</th>
            <th>Team 1</th>
            <th>Team 2</th>
            <th>Game Date</th>
            <th>Played</th>
            <th><a href="/admin/games/edit/0" class="btn btn-success">Add</a></th>
        </tr>
        <?php
        $i = 1;
        foreach ($resultArr as $result) {
            $gameDate = new DateTime($result['gameDateTime']);
            ?>
            <tr>
                <td><?= $i ?></td>
                <td><?= $result['Team1'] ?></td>
                <td><?= $result['Team2'] ?></td>
                <td><?= $gameDate->format('d M, Y H:i:s') ?></td>
                <td><?= ($result['played'] == "Y" ? "Yes" : "No") ?></td>
                <td>
                    <a href="/admin/games/edit/<?= $result['sn'] ?>" class="btn btn-success">Edit</a>
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
                location.href='/admin/games/delete/'+$sn;
                return false;
            }else{
                return false;
            }
        });
    });
</script>