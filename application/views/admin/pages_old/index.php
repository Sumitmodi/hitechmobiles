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
            <th>Slug Name</th>
            <th>Page Name</th>
            <th>Status</th>
            <th><a href="<?=  base_url()?>admin/pages/edit/0" class="btn btn-success">Add</a></th>
        </tr>
        <?php
        $i = 1;
        foreach ($resultArr as $result) {
            ?>
            <tr>
                <td><?= $i ?></td>
                <td><img src="<?= $this->config->item('pages_url') . 't_'.$result['image'] ?>" /></td>
                <td><?= $result['slugName'] ?></td>
                <td><?= $result['name'] ?></td>
                <td><?= ($result['status'] == "Y" ? "Enable" : "Disable") ?></td>
                <td><a href="<?=  base_url()?>admin/pages/edit/<?= $result['sn'] ?>" class="btn btn-success">Edit</a>
                    <a data-sn="<?=$result['sn']?>" class="btn btn-danger btnDelete">Delete</a></td>
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
                location.href='<?=base_url()?>admin/pages/delete/'+$sn;
                return false;
            }else{
                return false;
            }
        });
    });
</script>