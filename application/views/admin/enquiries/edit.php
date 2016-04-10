<div class="container">
    <div class="table-responsive">
        <?php if (!empty($status)) { ?>
            <div class="alert alert-success">
                <?= $status ?>
            </div>
        <?php } ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="sn" value="<?= (!empty($resultArr) ? $resultArr['sn'] : 0) ?>" />
            <table class="table table-bordered" cellpadding="5" cellspacing="0">
                <tr>
                    <th colspan="2">
                        <?= (!empty($resultArr) ? "View" : "Add") ?> Message
                    </th>
                </tr>
              
                <tr>
                    <td>Name</td>
                    <td><?= (!empty($resultArr) ? $resultArr['name'] : "") ?></td>
                </tr>
                
                <tr>
                    <td>Email</td>
                    <td><?= (!empty($resultArr) ? $resultArr['email'] : "") ?></td>
                </tr>
                
                <tr>
                    <td>Message</td>
                    <td><?= (!empty($resultArr) ? $resultArr['comments'] : "") ?></td>
                </tr>

              
            </table>
        </form>
    </div>
</div>