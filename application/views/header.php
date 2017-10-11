<?php echo doctype('html5'); ?>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>MultiMediaLT</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet"  href="<?php echo base_url();?>bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="<?php echo base_url();?>dist/css/skins/skin-blue.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>styles.css">
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="<?php echo base_url();?>plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="<?php echo base_url();?>functions.js"></script>
</head>
<body class="hold-transition skin-blue fixed sidebar-collapse sidebar-mini">
<div class="wrapper">
    <header class="main-header">
        <a href="<?php echo base_url();?>" class="logo">
            <span class="logo-mini"><b>MM</b>LT</span>
            <span class="logo-lg"><b>MultiMedia</b>LT</span>
        </a>
        <nav class="navbar navbar-static-top" role="navigation">
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <?php if($this->session->userdata('role_id') == 1){
                        echo '<li class="user">';
                        if (strpos(base_url(uri_string()), 'site') !== false) {
                            echo '<a href="'.base_url().'" ><i class="fa fa-exchange" aria-hidden="true"></i></a>';
                        }
                        else{
                            echo '<a href="'.base_url().'site/news" ><i class="fa fa-exchange" aria-hidden="true"></i></a>';
                        }
                        echo "</li>";
                    } ?>
                    <li class="user">
                        <a>
                            <i class="fa fa-user"></i> <?php echo $this->session->userdata('name'); ?>
                        </a>
                    </li>
                    <li class="logout">
                        <a href="<?php echo base_url()."user/logout"; ?>">
                            <i class="fa fa-times"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>