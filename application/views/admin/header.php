<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Menu</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="dropdown <?= ($navActive == "setup" ? "active" : "") ?>"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Setup</a>
                    <ul class="dropdown-menu">
                        <li><a href="/admin/profile">Profile</a></li>
                        <?php if ($this->session->userdata('logged_hitech_adminParentSN') == 0) { ?>
                            <li><a href="/admin/profile/users">Staff</a></li>
                        <?php } ?>
                    </ul>
                </li>

 <li class="<?= ($navActive == "customers" ? "active" : "") ?>"><a href="<?php echo base_url();?>admin/customers">Customers</a></li>
 <li class="<?= ($navActive == "products" ? "active" : "") ?>"><a class=" dropdown-toggle"  id="dropdownMenu1"  data-toggle="dropdown" aria-expanded="true" href="#">Products <span class="caret"></span></a>
     <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
         <li><a href="<?php echo base_url();?>admin/products">Product in view</a></li>
         <li><a href="<?php echo base_url();?>admin/products_locations">Locations</a></li>
         <li><a href="<?php echo base_url();?>admin/warehouse">Warehouse</a></li>
         <li><a href="<?php echo base_url();?>admin/category">Category</a></li>
         <li><a href="<?php echo base_url();?>admin/compatibles">Compatibles</a></li>
         <li><a href="<?php echo base_url();?>admin/vendor">Vendor</a></li>
     </ul>
 
 </li>
<!-- <li class="<?= ($navActive == "packages" ? "active" : "") ?>"><a href="<?= base_url() ?>admin/packages">Packages </a></li>
 <li class="<?= ($navActive == "brochures" ? "active" : "") ?>"><a href="<?= base_url() ?>admin/brochures">Brochures</a></li>
 <li class="<?= ($navActive == "flyers" ? "active" : "") ?>"><a href="<?= base_url() ?>admin/flyers">Flyers</a></li>
 <li class="<?= ($navActive == "pages" ? "active" : "") ?>"><a href="<?= base_url() ?>admin/pages">Pages</a></li>
 <li class="<?= ($navActive == "enquiries" ? "active" : "") ?>"><a href="<?= base_url() ?>admin/enquiries">Enquiries</a></li>
 <li class=""><a href="<?= base_url() ?>admin/logout">Logout</a></li>-->
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
