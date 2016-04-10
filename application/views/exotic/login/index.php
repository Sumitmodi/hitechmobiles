<section>
    <div class="container container1">
        <div class="row">
            <div class="col-xs-12 text-center">
                <img class="howzappLogo" src="<?= $this->config->item('theme_howzapp_url') ?>img/howzapp_logo.png" /><br/>
                <p><a class="btn btn-success playnow" href="/welcome/playnow" >PLAY NOW</a></p>
                <p><b>CRICKET LIVES HERE</b></p>
                <p>The ICC Cricket World Cup has just got more exciting. Simply guess how the first wickets will be taken to win your share of $50,000. HOWZAT!</p>
                <p>
                    SKY SPORT have all 49 games LIVE and SKY HOWZAPP! lets you make a pick for all of them. Will the batter be bowled, caught by the keeper or maybe run out? Give it 
                    a go. It’s free, easy as to play and a lot 
                    of fun. Good luck!
                </p>
               

            </div>
        </div>
    </div>
</section>
<script>
    $().ready(function () {
        $(".playnow").click(function () {
// Otherwise, show Login dialog first.
            FB.login(function (response) {
                onLogin(response);

                //console.log(response);
            }, {scope: 'user_friends, email'});
            return false;
        });
    });
</script>