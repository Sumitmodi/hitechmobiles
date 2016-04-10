<div class="container container1">
    <div class="row  text-center">
        <div class="col-xs-12">
            <h1>MAKE YOUR PICK</h1>
            <div class="steps">
                <div class="step step1"> 01 </div>
                <div class="step step2"> 02 </div>
                <div class="step step3"> 03 </div>
                <div class="step step4"> 04 </div>
                <div class="step step5"> 05 </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <h2 class="linethrough text-center"><span>CHOOSE A TEAM</span></h2>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <form method="POST" id="makeapickform">
                <input type="hidden" name="gameSN" id="gameSN" value="<?= $gameSN ?>" />
                <input type="hidden" name="step1SN" id="step1SN" value="0" />
                <input type="hidden" name="step2SN" id="step2SN" value="0" />
                <input type="hidden" name="step3SN" id="step3SN" value="0" />
                <input type="hidden" name="step4SN" id="step4SN" value="0" />
                <input type="hidden" name="step5SN" id="step5SN" value="0" />
                <div id="step1"></div>
                <div id="step2"></div>
                <div id="step3"></div>
                <div id="step4"></div>
                <div id="step5"></div>
            </form>
        </div>
    </div>
</div>
<script>
    $().ready(function () {
        $(".step1").addClass("stepactive");
        $.get('/players/makeapick/<?= $gameSN ?>/1', function (data) {
            $("#step1").html(data);
        });
        steps(0, '');

    });
    function steps(no, selected) {
        //console.log(no + " = " + selected);
        $noIncre = (parseInt(no) + 1);
        if ($noIncre > 0) {
            $("#step" + no + "SN").val(selected);
            $("#step" + no).hide();

        }
        $.post('/players/makeapick/<?= $gameSN ?>/' + $noIncre, $("#makeapickform").serialize(), function (data) {
            $("#step" + $noIncre).html(data);
            $("#step" + $noIncre).show();
           // console.log($noIncre + " = " + selected);
        });
    }
    function checkDD(no, selected){
        var flag=0;
        var stepValue = "";
        $(".dd"+no+" select").each(function(){
            if ($(this).val() == ""){
                flag=1;
            }else{
                stepValue += $(this).parent().attr('data-stepSN')+";"+$(this).val()+";;";
            }
        });
        if (flag==1){
            return false;
        }
        steps(no, stepValue);
    }
</script>