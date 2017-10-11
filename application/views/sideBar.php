<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="<?php echo base_url(uri_string()) == base_url() || base_url(uri_string()) == base_url()."site/news" ? "active " : "" ;?>">
                <a href="<?php echo base_url();?>">
                    <i class="fa fa-newspaper-o"></i>
                    <span>News</span>
                </a>
            </li>
            <li class="<?php echo base_url(uri_string()) == base_url()."site/browse" ? "active " : "" ;?>">
                <a href="<?php echo base_url()."site/browse";?>">
                    <i class="fa fa-globe"></i>
                    <span>Browse</span>
                </a>
            </li>
            <li class="<?php echo base_url(uri_string()) == base_url()."site/dataTables" ? "active " : "" ;?>">
                <a href="<?php echo base_url()."site/dataTables";?>">
                    <i class="fa fa-table"></i>
                    <span>Data</span>
                </a>
            </li>
            <li class="<?php echo base_url(uri_string()) == base_url()."site/analyse" ? "active " : "" ;?>">
                <a href="<?php echo base_url()."site/analyse";?>">
                    <i class="fa fa-bar-chart"></i>
                    <span>Analyse</span>
                </a>
            </li>
        </ul>
    </section>
</aside>