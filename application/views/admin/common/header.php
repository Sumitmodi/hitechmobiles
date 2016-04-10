<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">i</a>
            <a class="navbar-brand" href="#">Q</a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="dropdown <?= ($navActive == "setup" ? "active" : "") ?>">
                    <a class="dropdown-toggle"
                       data-toggle="dropdown" href="#">Setup</a>
                    <ul class="dropdown-menu">
                        <li><a href="/admin/profile">Profile</a></li>
                        <?php if ($this->session->userdata('logged_hitech_adminParentSN') == 0) { ?>
                            <li><a href="/admin/profile/users">Staff</a></li>
                        <?php } ?>
                    </ul>
                </li>

                <li class="<?= ($navActive == "slider" ? "active" : "") ?>"><a
                        href="<?php echo base_url(); ?>admin/slider">Slider</a></li>
                <li class="<?= ($navActive == "customers" ? "active" : "") ?>"><a
                        href="<?php echo base_url(); ?>admin/customers">Customers</a></li>
                <li class="<?= ($navActive == "products" ? "active" : "") ?>">
                    <a class=" dropdown-toggle"
                       id="dropdownMenu1"
                       data-toggle="dropdown"
                       aria-expanded="true" href="#">Products
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                        <li><a href="<?php echo base_url(); ?>admin/products">Product in view</a></li>
                        <li><a href="<?php echo base_url(); ?>admin/products_locations">Locations</a></li>
                        <li><a href="<?php echo base_url(); ?>admin/warehouse">Warehouse</a></li>
                        <li><a href="<?php echo base_url(); ?>admin/category">Category</a></li>
                        <li><a href="<?php echo base_url(); ?>admin/compatibles">Compatibles</a></li>
                        <li><a href="<?php echo base_url(); ?>admin/vendor">Vendor</a></li>
                    </ul>
                </li>

                <li class="<?= ($navActive == "pages" ? "active" : "") ?>">
                    <a
                        href="<?php echo base_url(); ?>admin/pages/pages">Pages</a></li>
                <li class="<?= ($navActive == "dealers" ? "active" : "") ?>">
                    <a
                        href="<?php echo base_url(); ?>admin/dealers/dealers">Dealers</a></li>

                <li class="<?= ($navActive == "jobs" ? "active" : "") ?>">
                    <!--<a class=" dropdown-toggle" id="dropdownMenu1"
                       data-toggle="dropdown" aria-expanded="true"
                       href="#">Jobs/Repair <span
                            class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                        <li><a href="<?php echo base_url(); ?>admin/jobs?action=dashboard">Dashboard</a></li>
                        <li><a href="<?php echo base_url(); ?>admin/jobs/deviceType">Device Type</a></li>
                    </ul>-->
                    <a href="<?php echo base_url('admin/jobs?action=dashboard'); ?>">Jobs &amp; Repair</a>
                </li>

                <li class="<?= ($navActive == "order" ? "active" : "") ?>">
                    <a class=" dropdown-toggle"
                       id="dropdownMenu1" data-toggle="dropdown"
                       aria-expanded="true" href="#">Order <span
                            class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                        <li><a href="<?php echo base_url(); ?>admin/order">Order</a></li>
                        <li><a href="<?php echo base_url(); ?>admin/payment_type">Payment Type</a></li>
                        <li><a href="<?php echo base_url(); ?>admin/shipping_type">Shipping Type</a></li>
                    </ul>
                </li>

                <li class="<?= ($navActive == "utility" ? "active" : "") ?>">
                    <a class=" dropdown-toggle"
                       id="dropdownMenu1"
                       data-toggle="dropdown"
                       aria-expanded="true" href="#">Utility
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                        <li><a href="<?php echo base_url(); ?>admin/country">Country</a></li>
                    </ul>
                </li>
                <li class="<?= ($navActive == "purchases" ? "active" : "") ?>">
                    <a href="<?php echo base_url('admin/purchases'); ?>">Purchases</a>
                </li>
                <li class="<?= ($navActive == "testimonials" ? "active" : "") ?>">
                    <a href="<?php echo base_url('admin/testimonials'); ?>">Testimonials</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

