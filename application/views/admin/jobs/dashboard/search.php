
<script type="text/javascript">
    <?php if($this->uri->segment(4)>0){ ?>
    var editMode = true;
    var editId = <?php echo $this->uri->segment(4);?>;
    <?php }else{ ?>
    var editMode = false;
    <?php } ?>
</script>
<style type="text/css">
    .nav.spacing li {
        margin: 0 5px;
    }
</style>
<div class="container" ng-controller="searchEditCtrl" ng-scope="scope">
    <form role="form" method="POST" action="" enctype="multipart/form-data" ng-submit="searchCustomer()">
        <div class="col-md-6">
            <div class="">
                <fieldset class="scheduler-border">
                    <div class="form-group">
                        <label for="text">Search by customer</label>
                        <input type="text" class="form-control" id="text"
                               placeholder="Enter product code"
                               ng-model="search.customer_name">
                    </div>
                </fieldset>
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-4">
                <ul class="nav navbar-nav spacing pull-left">
                    <li role="presentation">
                        <button class="btn btn-success" type="submit"><span
                                class="glyphicons glyphicons-plus"></span> Save
                        </button>
                    </li>
                    <li role="presentation">
                        <button type="reset" class="btn btn-danger" ng-click="resetProduct()"><span
                                class="glyphicons glyphicons-remove-2"></span> Reset
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </form>
</div>
<hr>
<script type="text/javascript" src="<?php echo base_url('assets/themes/default/ckeditor/ckeditor.js'); ?>" defer="true"
        async="async"></script>
<script type="text/javascript" src="<?php echo base_url('assets/angular/jobs/edit.js'); ?>"></script>