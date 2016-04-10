<style type="text/css">
    .container {
        width: 100%;
    }

    .box {
        margin-top: 15px;
        background: yellow;
        padding: 10px;
        border: solid 1px #ccc;
        border-radius: 5px
    }

    .l-box {
        border: solid 1px #ccc;
        border-radius: 5px;
        padding: 10px;
    }

    .col-md-6 {
        padding: 0;
    }

    fieldset.scheduler-border {
        border: 1px groove #ddd !important;
        border-radius: 5px;
        padding: 0 1.4em 1.4em 1.4em !important;
        margin: 0 0 1.5em 0 !important;
        -webkit-box-shadow: 0px 0px 0px 0px #000;
        box-shadow: 0px 0px 0px 0px #000;
    }

    legend.scheduler-border {
        width: inherit; /* Or auto */
        padding: 0 10px; /* To give a bit of padding on the left and right */
        border-bottom: none;

    }

    hr {
        border-top-style: dashed;
        color: #aaa;
    }
</style>
<style type="text/css">
    .nav.spacing li {
        margin: 0 5px;
    }

    .search-res {
        background-color: #fff;
        list-style: outside none none;
        margin: 0 auto;
        padding: 0;
        width: 100%;
        border: none;
    }

    .search-res li {
        padding: 10px;
        background-color: #00113a;
        border-bottom: 1px solid #fff;
        cursor: pointer;
        color: #FFF;
        opacity: 0.8;
    }

    .search-res li:hover {
        opacity: 1.0;
    }

    .search-res li a {
        color: #FFF;
    }

    .multi-dropdown li {
        cursor: pointer;
        padding: 5px;
    }

    .multi-dropdown li:hover {
        background-color: #eee;
    }

    .multi-dropdown li:hover a {
        background-color: #eee !important;
    }

    .multi-dropdown li.selected a:link, .multi-dropdown li.selected a:visited {
        background-color: none;
    }

    .multi-dropdown li.selected {
        background-color: #ddd;
        /*color:#FFF;*/
        border-bottom: 1px solid #fff;
    }

    .multi-dropdown li.unselected {
        background-color: none;
        color: #000;
    }

    .btn-group button {
        min-width: 150px;
    }

    .upload-button {
        cursor: pointer;
    }
</style>
<!-- Main Content -->
<script src="<?php echo base_url(); ?>assets/dd.js"></script>
<script src="<?php echo base_url(); ?>assets/themes/default/js/angular-file-upload-shim.js"></script>
<script src="<?php echo base_url(); ?>assets/themes/default/js/angular-file-upload.js"></script>
<script src="<?php echo base_url(); ?>assets/themes/default/js/notify.min.js"></script>
<script type="text/javascript">
    var apikey = '<?php echo $this->config->item('hitech_apikey'); ?>';
    var postUrl = '<?php echo base_url('api/pages/edit'); ?>';
    <?php if($this->uri->segment(4)>0){ ?>
    var editMode = true;
    var editId = <?php echo $this->uri->segment(4);?>;
    <?php }else{ ?>
    var editMode = false;
    <?php } ?>
</script>
<div class="container" ng-controller="pagesEditCtrl" ng-scope="scope">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <form role="form" method="POST" action="" enctype="multipart/form-data" ng-submit="addPages()">
                <div class="table-responsive">
                    <div class="form-group">
                        <label for="text">Page name</label>
                        <input type="text" class="form-control" id="text"
                               placeholder="Title of the page"
                               ng-model="pages.name">
                    </div>
                    <div>
                        <label>Slug Name</label>
                        <input type="text" class="form-control" id="text"
                               placeholder="Enter page slug name"
                               ng-model="pages.slug_name">
                    </div>
                    <div class="form-group">
                        <label for="text">Description</label>
                <textarea ng-model="pages.description"
                          class="ckeditor" name="description"
                          placeholder="Enter page desciption" ng-value="pages.description">{{pages.description}}</textarea>
                    </div>
                    <div>
                        <label>Meta Title</label>
                        <input type="text" class="form-control" id="text"
                               placeholder="Enter page meta title"
                               ng-model="pages.meta_title">
                    </div>
                    <div>
                        <label>Meta description</label>
                <textarea ng-model="pages.meta_description"
                          class="form-control" name="meta_description"
                          placeholder="Enter page meta desciption"></textarea>
                    </div>
                    <div>
                        <label>Meta keywords</label>
                <textarea ng-model="pages.meta_keywords"
                          class="form-control" name="meta_keys"
                          placeholder="Enter page meta keywords"></textarea>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="row">
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
    </div>
</div><!-- container -->

<hr>
<script type="text/javascript">
    var apikey = '<?php echo $this->config->item('hitech_apikey');?>';
    var app = angular.module('app', ['ngFileUpload']);
</script>
<script type="text/javascript" src="<?php echo base_url('assets/themes/default/ckeditor/ckeditor.js'); ?>" defer="true"
        async="async"></script>

<script type="text/javascript" src="<?php echo base_url('assets/angular/pages/edit.js'); ?>"></script>

<script>

    /*function deleteAccessories(id) {
     debugger;
     var row = id.parentNode.parentNode.rowIndex;
     document.getElementById("accessories_table").deleteRow(row);
     }*/
</script>