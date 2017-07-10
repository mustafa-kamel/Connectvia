<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSE2017 - <?php echo TITLE; ?></title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/Basic-Header.css">
    <link rel="stylesheet" href="assets/css/custom.css">
<!--sidebar assets-->
    <link rel="stylesheet" href="assets/css/sidebar-collapse.css">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
    <link href='http://fonts.googleapis.com/css?family=Cookie' rel='stylesheet' type='text/css'>
<!--footer assets-->
    <link rel="stylesheet" href="assets/css/footer-distributed.css">
    <script src="assets/js/jquery.min.js"></script>

</head>

<body>
    <div>
        <nav class="navbar navbar-default navigation-clean-button">
            <div class="container">
                <div class="navbar-header"><a class="navbar-brand" href="#">CSE2017 Mr. Home</a>
                    <button class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
                </div>
                <div class="collapse navbar-collapse" id="navcol-1">
                    <ul class="nav navbar-nav">
                        <li class="active" role="presentation"><a href="#">Home</a></li>
                        <li role="presentation"><a href="rooms.php">Rooms</a></li>
                        <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" href="#">Others<span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li role="presentation"><a href="user.php?type=new">New Users</a></li>
                                <li role="presentation"><a href="user.php?type=all">All Users</a></li>
                                <li role="presentation"><a href="user.php?type=admin">Admins</a></li>
                            </ul>
                        </li>
                    </ul>
                    <p class="navbar-text navbar-right actions" style="padding-right: 80px;"><a class="btn btn-default action-button" role="button" href="logout.php">Log out</a></p>
                </div>
            </div>
        </nav>
    </div>

    <!--Content-->

                