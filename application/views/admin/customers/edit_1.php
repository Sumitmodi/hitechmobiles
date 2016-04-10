<div class="container"   ng-controller="customersController">

    <div class="table-responsive">

        <?php if (!empty($status)) { ?>

            <div class="alert alert-success">

                <?= $status ?>

            </div>

        <?php } ?>

        <div role="tabpanel">



            <!-- Nav tabs -->

            <ul class="nav nav-tabs" role="tablist" id="myTab">

                <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">General</a></li>

                <?php if (!empty($resultArr)) { ?>

                    <li role="presentation"><a href="#purchasehistory" aria-controls="purchasehistory" role="tab" data-toggle="tab">Purchase History</a></li>

                    <li role="presentation"><a href="#attachments" aria-controls="attachments" role="tab" data-toggle="tab">Attachments</a></li>

                    <li role="presentation"><a href="#notes" aria-controls="notes" role="tab" data-toggle="tab">Notes/Task/Events</a></li>

                <?php } else { ?>

                    <li role="presentation"   class="disabled"><a href="#" aria-controls="" role="tab" data-toggle="tab">Purchase History</a></li>

                    <li role="presentation"  class="disabled"><a href="#" aria-controls="" role="tab" data-toggle="tab">Attachments</a></li>

                    <li role="presentation"  class="disabled"><a href="#" aria-controls="" role="tab" data-toggle="tab">Notes/Task/Events</a></li>

                <?php } ?>

            </ul>



            <!-- Tab panes -->

            <div class="tab-content">

                <div role="tabpanel" class="tab-pane active" id="home">

                    <h2>Customer: <?= (!empty($resultArr) ? $resultArr['name'] : "Creating..") ?></h2>

                    <form method="POST">

                        <input type="hidden" name="sn" value="<?= (!empty($resultArr) ? $resultArr['sn'] : 0) ?>" />

                        <input type="hidden" name="action" value="General" />

                        <div class="floatleft width50">

                            <table class="table  table-bordered">

                                <tr>

                                    <th colspan="2">

                                        General Details

                                    </th>

                                </tr>

                                <tr>

                                    <td>Name</td>

                                    <td><input type="type" class="form-control" value="<?= (!empty($resultArr) ? $resultArr['name'] : "") ?>" name="name" /></td>

                                </tr>

                                <tr>

                                    <td>Company Name</td>

                                    <td><input type="type" class="form-control" value="<?= (!empty($resultArr) ? $resultArr['companyName'] : "") ?>" name="companyName" /></td>

                                </tr>

                                <tr>

                                    <td>Status</td>

                                    <td>

                                        <input type="radio"  value="Y" <?= (!empty($resultArr) ? ($resultArr['status'] == "Y" ? "checked" : "checked") : "checked") ?> name="status"  /> Enable

                                        <input type="radio"  value="N" <?= (!empty($resultArr) ? ($resultArr['status'] == "N" ? "checked" : "") : "") ?> name="status"  /> Disable

                                    </td>

                                </tr>

                            </table>

                            <br/><br/>

                            <table class="table  table-bordered">

                                <tr>

                                    <td colspan="2">

                                        <b>Billing / Main Details</b>

                                    </td>

                                </tr>

                                <tr>

                                    <td>Address</td>

                                    <td><textarea name="address" class="form-control" rows="3"><?= (!empty($resultArr) ? $resultArr['address'] : "") ?></textarea></td>

                                </tr>



                                <tr>

                                    <td>City</td>

                                    <td><input type="type" class="form-control" value="<?= (!empty($resultArr) ? $resultArr['city'] : "") ?>" name="city" /></td>

                                </tr>

                                <tr>

                                    <td>Postcode</td>

                                    <td><input type="type" class="form-control" value="<?= (!empty($resultArr) ? $resultArr['postcode'] : "") ?>" name="postcode" /></td>

                                </tr>

                                <tr>

                                    <td>Region/State</td>

                                    <td><input type="type" class="form-control" value="<?= (!empty($resultArr) ? $resultArr['state'] : "") ?>" name="state" /></td>

                                </tr>

                                <tr>

                                    <td>Country</td>

                                    <td><input type="type" class="form-control" value="<?= (!empty($resultArr) ? $resultArr['country'] : "") ?>" name="country" /></td>

                                </tr>

                                <tr>

                                    <td>Email</td>

                                    <td><input type="type" class="form-control" value="<?= (!empty($resultArr) ? $resultArr['email'] : "") ?>" name="email" /></td>

                                </tr>

                                <tr>

                                    <td>Website</td>

                                    <td><input type="type" class="form-control" value="<?= (!empty($resultArr) ? $resultArr['email'] : "http://") ?>" name="website" /></td>

                                </tr>

                                <tr>

                                    <td>Phone</td>

                                    <td><input type="type" class="form-control" value="<?= (!empty($resultArr) ? $resultArr['phone'] : "") ?>" name="phone" /></td>

                                </tr>

                                <tr>

                                    <td>Fax</td>

                                    <td><input type="type" class="form-control" value="<?= (!empty($resultArr) ? $resultArr['fax'] : "") ?>" name="fax" /></td>

                                </tr>

                                <tr>

                                    <td>Mobile</td>

                                    <td><input type="type" class="form-control" value="<?= (!empty($resultArr) ? $resultArr['mobile'] : "") ?>" name="mobile" /></td>

                                </tr>

                            </table>



                        </div>

                        <div class="floatleft width45 marginLeft5">

                            <table class="table  table-bordered">

                                <tr>

                                    <td colspan="2">

                                        <b>Attributes</b>

                                    </td>

                                </tr>

                                <tr>

                                    <td>Subscribe to mailing list?</td>

                                    <td><input type="checkbox" class="form-control" value="Y" <?= (!empty($resultArr) ? ($resultArr['mailingList'] == "Y" ? "checked" : "") : "") ?> name="mailingList" /></td>

                                </tr>

                                <tr>

                                    <td>Feedback Enabled?</td>

                                    <td><input type="checkbox" class="form-control" value="Y" <?= (!empty($resultArr) ? ($resultArr['feedbackEnabled'] == "Y" ? "checked" : "") : "") ?> name="feedbackEnabled" /></td>

                                </tr>

                                <tr>

                                    <td>Customer need Account?</td>

                                    <td><input type="checkbox" class="form-control" value="Y" <?= (!empty($resultArr) ? ($resultArr['anAccount'] == "Y" ? "checked" : "") : "") ?> name="anAccount" /></td>

                                </tr>

                                <tr>

                                    <td>Use Shipping Address?</td>

                                    <td><input type="checkbox" class="form-control" value="Y" <?= (!empty($resultArr) ? ($resultArr['anAccount'] == "Y" ? "checked" : "") : "") ?> name="useShippingAddress" /></td>

                                </tr>

                            </table>

                            <br/><br/> 

                            <table class="table  table-bordered">

                                <tr>

                                    <td>

                                        <b>Comments</b>

                                    </td>

                                </tr>

                                <tr>

                                    <td><textarea name="comments" class="form-control" rows="3"><?= (!empty($resultArr) ? $resultArr['comments'] : "") ?></textarea></td>

                                </tr>

                            </table>

                            <br/><br/> 

                            <table class="table  table-bordered">

                                <tr>

                                    <td>

                                        <b>More Email Address</b>

                                    </td>

                                    <td>

                                        <a href="#" class="btn btn-success">Add</a>

                                    </td>

                                </tr>

                            </table>

                        </div>

                        <div class="clear submitBox">

                            <input type="submit" class="btn btn-success" value="Save!" />

                        </div>

                    </form>                    

                </div>

                <div role="tabpanel" class="tab-pane" id="purchasehistory">







                </div>

                <div role="tabpanel" class="tab-pane" id="attachments">

                    <?php if (!empty($resultArr)) { ?>

                        <b>Attachments</b>

                        <table class="table table-bordered table-hover table-stripped">

                            <thead>

                                <tr>

                                    <th>File</th>

                                    <th>Description</th>

                                    <th>&nbsp;</th>

                                </tr>

                            </thead>

                            <form method="POST" enctype="multipart/form-data">

                                <tr>

                                    <td><input type="hidden" name="sn" value="0" />

                                        <input type="hidden" name="action" value="Attachments" />

                                        <input type="hidden" name="customerSN" value="<?= (!empty($resultArr) ? $resultArr['sn'] : 0) ?>" />

                                        <input type="file" class="form-control" name="file" /></td>

                                    <td><input type="text" class="form-control" value="" name="description" /></td>

                                    <td><input type="submit" class="btn btn-success" value="Add!" /></td>

                                </tr>

                            </form>

                            <?php foreach ($attachmentArr as $attachment) { ?>

                                <form method="POST" enctype="multipart/form-data">

                                    <tr>

                                        <td><input type="hidden" name="sn" value="<?= $attachment['sn'] ?>" />

                                            <input type="hidden" name="action" value="Attachments" />

                                            <input type="hidden" name="customerSN" value="<?= (!empty($resultArr) ? $resultArr['sn'] : 0) ?>" />

                                            <?php if (!empty($attachment['file'])) { ?>

                                                <?php

                                                if (pathinfo($attachment['file'], PATHINFO_EXTENSION) == "pdf") {

                                                    ?>

                                                    <a target="_blank" href="<?= $this->config->item('customers_url') . $attachment['file'] ?>">Link</a>

                                                <?php } else { ?>

                                                    <img src="<?= $this->config->item('customers_url') . $attachment['file'] ?>" width = "50" />

                                                    <?php

                                                }

                                            }

                                            ?>

                                            <input type="file" class="form-control" name="file" /></td>

                                        <td><input type="text" class="form-control" value="<?= $attachment['description'] ?>" name="description" /></td>

                                        <td>

                                            <input type="submit" class="btn btn-success" value="Save" />

                                            <a data-sn="<?= $attachment['sn'] ?>" class="btn btn-danger btnDeleteAttachment">Delete</a>

                                        </td>

                                    </tr>

                                </form>

                            <?php } ?>

                        </table>

                    <?php } ?>



                </div>

                <div role="tabpanel" class="tab-pane" id="notes">...</div>

            </div>



        </div>

        <script>

            $('#myTab a').click(function (e) {

                e.preventDefault()

                $(this).tab('show')

            })

        </script>



    </div>

</div>



<script>



            var app = angular.module('app', ['angular-table', 'selectionModel']);





</script>