<div class="row">
    <div class="col-xs-12">
        <div class="padding">
            <h2>Enquire <span>Now</span></h2>
            <form method="POST" id="enquiryForm" class="form-horizontal">
                <input type="hidden" name="packageName" value="<?= $packageName ?>" />
                <input type="hidden" name="packageSN" value="<?= $packageSN ?>" />

                <div class="row marginTop10">
                    <div class="col-sm-4 text-right">Name<span class="red">*</span>	</div>
                    <div class="col-sm-8">
                        <input type="text" name="firstName" class="form-control">
                    </div>
                </div>


                <div class="row marginTop10">
                    <div class="col-sm-4 text-right">Email<span class="red">*</span>	</div>
                    <div class="col-sm-8">
                        <input type="text" name="email" id="email" class="form-control">
                    </div>
                </div>
                <div class="row marginTop10">
                    <div class="col-sm-4 text-right">Phone No<span class="red">*</span>	</div>
                    <div class="col-sm-8">
                        <input type="text" name="phoneNo" id="phoneNo" class="form-control">
                    </div>
                </div>

                <div class="row marginTop10">
                    <div class="col-sm-4 text-right">Comments<span class="red">*</span>	</div>
                    <div class="col-sm-8">
                        <textarea class="form-control" name="comments" id="comments" rows="5"></textarea>
                    </div>
                </div>

                <div class="row marginTop10">
                    <div class="col-sm-4 text-right">	</div>
                    <div class="col-sm-8">
                        <button class="btn btnSubmit" name="submitForm">submit</button>
                    </div>
                </div>
            </form>


            <div class="alert alert-success">
                Thank you for submitting the form. We will get back to you as soon as possible.
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        jQuery.validator.addMethod("lettersonly", function (value, element) {
            return this.optional(element) || /^[a-z\ \-]+$/i.test(value);
        }, "Letters only please");
        $('#enquiryForm').validate({
            rules: {
                firstName: {lettersonly: true, minlength: 1, required: true},
                email: {email: true, required: true},
                phoneNo: {required: true},
                comments: {required: true}
            },
            highlight: function (element) {
                $(element).closest('.control-group').removeClass('success').addClass('error');
            },
            success: function (element) {
                element
                        // .text('OK!').addClass('valid')
                        .closest('.control-group').removeClass('error').addClass('success');
            },
            submitHandler: function (frm) {
                var data1 = $('#enquiryForm').serializeArray();
                //  $("#loadingOuter").show();
                $(".btnSubmit").hide();
                $.post('<?= base_url() ?>pages/enquiryNowSubmit/', data1, function (data) {

                    if (data == "true") {
                        $(".alert-success").show();
                        $("#enquiryForm").hide();
                    } else {
                        alert('Problem in submission...');
                        $(".btnSubmit").show();
                    }
                    //  $("#loadingOuter").hide();
                    return false;
                });

            }
        });
        $(".alert-success").hide();
    });
</script>