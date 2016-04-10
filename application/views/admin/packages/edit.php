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
                        <?= (!empty($resultArr)?"Edit":"Add") ?> Packages
                    </th>
                </tr>
                <tr>
                    <td>Image</td>
                    <td><?php if (!empty($resultArr)) { ?>
                            <img src="<?= $this->config->item('packages_url') . 't_' . $resultArr['image'] ?>" />
                        <?php } ?>
                        <input type="file" name="image" /></td>
                </tr>
                <tr>
                    <td>Name</td>
                    <td><input type="type" class="form-control" value="<?= (!empty($resultArr) ? $resultArr['name'] : "") ?>" name="name" /></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><?= $this->ckeditor->editor("description", (!empty($resultArr) ? $resultArr['description'] : "")); ?></td>
                </tr>
                <tr>
                    <td>Price</td>
                    <td><input type="type" class="form-control" value="<?= (!empty($resultArr) ? $resultArr['price'] : "") ?>" name="price" /></td>
                </tr>
                <tr>
                    <td>Days</td>
                    <td><input type="type" class="form-control" value="<?= (!empty($resultArr) ? $resultArr['days'] : "") ?>" name="days" /></td>
                </tr>
                <tr>
                    <td>Short Introduction</td>
                    <td><textarea rows="5" class="form-control" name="shortIntro"><?= (!empty($resultArr) ? $resultArr['shortIntro'] : "") ?> </textarea></td>
                </tr>
                <tr>
                    <td>Destinations</td>
                    <td><select class="form-control" name="destinationsSN"> 
                            <option value="0">Please Select</option>
                            <?php foreach ($destinationsArr as $destination) { ?>
                                <option value="<?= $destination['sn'] ?>" <?= (!empty($resultArr) ? ($resultArr['destinationsSN'] == $destination['sn'] ? "selected" : "" ) : "") ?>><?= $destination['name'] ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Featured</td>
                    <td>
                        <input type="radio"  value="Y" <?= (!empty($resultArr) ? ($resultArr['featured'] == "Y" ? "checked" : "") : "") ?> name="featured"  /> Yes
                        <input type="radio"  value="N" <?= (!empty($resultArr) ? ($resultArr['featured'] == "N" ? "checked" : "") : "checked") ?> name="featured"  /> No
                    </td>
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