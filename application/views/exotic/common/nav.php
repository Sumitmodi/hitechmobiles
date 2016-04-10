<nav class="navbg navbar">
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
                <li class="<?= ($navActive == "index" ? "active" : "") ?>"><a href="<?= $this->config->item('base_url') ?>pages/info/index">Home </a><span>|</span> </li>
                <li class="<?= ($navActive == "about-us" ? "active" : "") ?>"><a href="<?= $this->config->item('base_url') ?>pages/info/about-us">About us </a><span>|</span></li>
                <li class="dropdown destinationmenu <?= ($navActive == "destination" ? "active" : "") ?>"> <a href="#" class="dropdown-toggle" data-toggle="dropdown">Destination </a><span>|</span>
                    <ul class="dropdown-menu">
                        <?php foreach ($destinationMenuArr as $destination) { ?>
                            <li><a href="<?= $this->config->item('base_url') ?>destination/packages/<?= str_replace(' ', '-', $destination['name']) . "/" . $destination['sn'] ?>"><?= $destination['name'] ?> </a></li>
                        <?php } ?>
                    </ul>
                </li>
                <li class="dropdown <?= ($navActive == "destinationInfo" ? "active" : "") ?>"> <a href="#" class="dropdown-toggle" data-toggle="dropdown">Destination Info </a><span>|</span>
                    <ul class="dropdown-menu">
                        <?php foreach ($destinationMenuArr as $destination) { ?>
                            <li><a href="<?= $this->config->item('base_url') ?>destination/info/<?= str_replace(' ', '-', $destination['name']) . "/" . $destination['sn'] ?>"><?= $destination['name'] ?> </a></li>
                        <?php } ?>
                    </ul>
                </li>
                <li class="<?= ($navActive == "brochures" ? "active" : "") ?>"><a href="<?= $this->config->item('base_url') ?>pages/brochures">Brochures </a><span>|</span></li>
                <li class="<?= ($navActive == "flyers" ? "active" : "") ?>"><a href="<?= $this->config->item('base_url') ?>pages/flyers">Flyers </a><span>|</span></li>
                <li class="<?= ($navActive == "links" ? "active" : "") ?>"><a href="<?= $this->config->item('base_url') ?>pages/info/links">Links </a><span>|</span></li>
                <li class="<?= ($navActive == "contact-us" ? "active" : "") ?>"><a href="<?= $this->config->item('base_url') ?>pages/info/contact-us">Contact us </a><span>|</span></li>

            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>