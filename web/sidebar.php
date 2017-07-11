<!--sidebar-->

<aside class="sidebar-left-collapse">

        <a href="#" class="company-logo"><img src="assets/images/futurehome.png" width=100px; heigt=100px;></a>

        <div class="sidebar-links">

            <div class="link-blue selected">

                <a href="index.php">
                    <i class="fa fa-home"></i>Home
                </a>

                <ul class="sub-links">
                    <li><a href="index.php">Documentation</a></li>
                </ul>

            </div>

            <div class="link-red">

                <a href="#">
                    <i class="fa fa-picture-o"></i>Rooms
                </a>

                <ul class="sub-links">
                    <li><a href="rooms.php">All Rooms</a></li>
                </ul>

            </div>
<?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1){
            echo '<div class="link-yellow">
                <a href="#">
                    <i class="fa fa-keyboard-o"></i>Manage
                </a>

                <ul class="sub-links">
                    <li><a href="user.php?type=new">New users</a></li>
                    <li><a href="user.php?type=all">All Users</a></li>
                    <li><a href="user.php?type=admin">Admins</a></li>
                </ul>

            </div>';
}?>

            <div class="link-green">

                <a href="#">
                    <i class="fa fa-map-marker"></i>Places
                </a>

                <ul class="sub-links">
                    <li><a href="#">Link 1</a></li>
                    <li><a href="#">Link 2</a></li>
                    <li><a href="#">Link 3</a></li>
                    <li><a href="#">Link 4</a></li>
                </ul>

            </div>

        </div>

    </aside>

