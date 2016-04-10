<div class="row text-center">
    <div class="col-xs-12">
        <div class="boxBlack" onclick="steps(1, '<?= $resultArr['team1'] ?>');">
            <img src="<?= $this->config->item('teams_url') . $resultArr['Team1Flag'] ?>" />
            <span class="padding"><?= $resultArr['Team1'] ?></span>
        </div><br/>
        VS<br/>
        <div class="boxBlack" onclick="steps(1, '<?= $resultArr['team2'] ?>');">
            <img src="<?= $this->config->item('teams_url') . $resultArr['Team2Flag'] ?>" />
            <span class="padding"><?= $resultArr['Team2'] ?></span>
        </div>
    </div>
</div>
