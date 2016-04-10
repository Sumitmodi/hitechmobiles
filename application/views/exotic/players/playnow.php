<div class="container container1">
    <div class="row">
        <div class="col-xs-12 text-center">
            <h1>PLAY NOW</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 text-center">
            <div class="greenText">COMING UP</div>
            <?php foreach ($resultArr as $result){ $date=new DateTime($result['gameDateTime']) ?>
            <fieldset class="match">
                <legend><?=$result['Team1']?> VS <?=$result['Team2']?></legend>
                <img src="<?=$this->config->item('teams_url').$result['Team1Flag']?>" />
                vs 
                <img src="<?=$this->config->item('teams_url').$result['Team2Flag']?>" />
                <a class="btn btn-success playnow" href="/players/makeapick/<?=$result['sn']?>" >MAKE A PICK</a>
                <div class="detail">1 Match Pick left - <?=$date->format('D j M - h:ia')?>
            </fieldset>
            <?php } ?>
        </div>
    </div>
</div>