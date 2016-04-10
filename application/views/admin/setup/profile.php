<div class="container">
    <div class="table-responsive">
        <?php if (!empty($status)){ ?>
        <div class="alert alert-success">
            <?=$status?>
        </div>
        <?php } ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="sn" value="<?= (!empty($resultArr)?$resultArr['sn']:0) ?>" />
            <table class="table table-bordered" cellpadding="5" cellspacing="0">
                <tr>
                    <th colspan="2">
                        <?= (!empty($resultArr)?"Edit":"Add") ?> Profile
                    </th>
                </tr>
                <tr>
                    <td>Logo</td>
                    <td><?php if(!empty($resultArr) && !empty($resultArr['logo'])){ ?>
                        <img src="<?=$this->config->item('profile_url').'t_'.$resultArr['logo']?>" />
                    <?php } ?>
                        <input type="file" name="logo" /></td>
                </tr>
                <tr>
                    <td>Name</td>
                    <td><input type="type" class="form-control" value="<?=(!empty($resultArr)?$resultArr['name']:"")?>" name="name" /></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><?=(!empty($resultArr)?$resultArr['email']:"")?></td>
                </tr>
                    
                 <tr>
                    <td>Password</td>
                    <td><input type="password" class="form-control" value="" name="password" /></td>
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