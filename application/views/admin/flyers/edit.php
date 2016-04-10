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
                        <?= (!empty($resultArr) ? "Edit" : "Add") ?> Flyers
                    </th>
                </tr>
                <tr>
                    <td>Image</td>
                    <td><?php if (!empty($resultArr)) { ?>
                            <img src="<?= $this->config->item('flyers_url') . 't_' . $resultArr['image'] ?>" />
                        <?php } ?>
                        <input type="file" name="image" /></td>
                </tr>
                <tr>
                    <td>Name</td>
                    <td><input type="type" class="form-control" value="<?= (!empty($resultArr) ? $resultArr['name'] : "") ?>" name="name" /></td>
                </tr>
                <tr>
                    <td>Upload PDF</td>
                    <td><?php if (!empty($resultArr)) { ?>
                            <a href="<?= $this->config->item('flyers_url') . $resultArr['pdf'] ?>" target="_blank">LINK</a>
                        <?php } ?>
                        <input type="file" name="pdf" /></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><?= $this->ckeditor->editor("description", (!empty($resultArr) ? $resultArr['description'] : "")); ?></td>
                </tr>

                <tr>
                    <td>Meta Title</td>
                    <td><input type="type" class="form-control" value="<?= (!empty($resultArr) ? $resultArr['metaTitle'] : "") ?>" name="metaTitle" /></td>
                </tr>
                <tr>
                    <td>Meta Description</td>
                    <td><textarea rows="5" class="form-control" name="metaDesc"><?= (!empty($resultArr) ? $resultArr['metaDesc'] : "") ?> </textarea></td>
                </tr>
                <tr>
                    <td>Meta Keywords</td>
                    <td><input type="type" class="form-control" value="<?= (!empty($resultArr) ? $resultArr['metaKeyword'] : "") ?>" name="metaKeyword" /></td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>
                        <input type="radio"  value="Y" <?= (!empty($resultArr) ? ($resultArr['status'] == "Y" ? "checked" : "") : "checked") ?> name="status"  /> Enable
                        <input type="radio"  value="N" <?= (!empty($resultArr) ? ($resultArr['status'] == "N" ? "checked" : "") : "") ?> name="status"  /> Disable
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" class="btn btn-success" value="Submit!" />
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>