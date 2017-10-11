<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="<?php echo base_url(uri_string()) == base_url()."admin/main" ? "active " : "" ;?>">
                <a href="<?php echo base_url()."admin/main";?>">
                    <i class="fa fa-info"></i>
                    <span>Main</span>
                </a>
            </li>
            <li class="<?php echo base_url(uri_string()) == base_url()."admin/users" ? "active " : "" ;?>">
                <a href="<?php echo base_url()."admin/users";?>">
                    <i class="fa fa-child"></i>
                    <span>Users</span>
                </a>
            </li>
            <li class="<?php echo base_url(uri_string()) == base_url()."admin/articles" ? "active " : "" ;?>">
                <a href="<?php echo base_url()."admin/articles";?>">
                    <i class="fa fa-book"></i>
                    <span>Articles</span>
                </a>
            </li>
            <li class="<?php echo base_url(uri_string()) == base_url()."admin/comments" ? "active " : "" ;?>">
                <a href="<?php echo base_url()."admin/comments";?>">
                    <i class="fa fa-quote-left"></i>
                    <span>Comments</span>
                </a>
            </li>
        </ul>
    </section>
</aside>