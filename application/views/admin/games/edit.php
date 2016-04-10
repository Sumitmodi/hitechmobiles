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
                        Edit Game
                    </th>
                </tr>
                <tr>
                    <td> Team 1</td>
                    <td>
                        <select name="team1" class="form-control">
                            <option value="0">--Please Select-</option>
                            <?php foreach ($teamsArr as $teams) { ?>
                                <option value="<?=$teams['sn']?>" <?= (!empty($resultArr) ? ($resultArr['team1']==$teams['sn']?"selected":"") : "") ?>><?=$teams['name']?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td> Team 2</td>
                    <td>
                        <select name="team2" class="form-control">
                            <option value="0">--Please Select-</option>
                            <?php foreach ($teamsArr as $teams) { ?>
                                <option value="<?=$teams['sn']?>" <?= (!empty($resultArr) ? ($resultArr['team2']==$teams['sn']?"selected":"") : "") ?>><?=$teams['name']?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Game Date</td>
                    <td>
                        <input type="text" class="form-control"  data-date-format="DD-MM-YYYY hh:mm a" name="gameDateTime" value="<?= (!empty($resultArr) ? date("d-m-Y h:i a", strtotime($resultArr['gameDateTime'])) : "") ?>" id="datetimepicker2">
                    </td>
                </tr>
                <tr>
                    <td>Played</td>
                    <td>
                        <input type="radio"  value="Y" <?= (!empty($resultArr) ? ($resultArr['played'] == "Y" ? "checked" : "") : "checked") ?> name="played"  /> Yes
                        <input type="radio"  value="N" <?= (!empty($resultArr) ? ($resultArr['played'] == "N" ? "checked" : "") : "") ?> name="played"  /> No
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
<script type="text/javascript">
    $(function () {
        $('#datetimepicker1, #datetimepicker2').datetimepicker();
    });
</script>