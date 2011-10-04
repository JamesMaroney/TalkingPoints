<?php
    function navClass($navName){
        global $action;
        return ($navName == $action)? "active" : "";
    }
?>

<!DOCTYPE html>
<head>
    <link rel="stylesheet" type="text/css" href="admin.css" />
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
</head>
<body>
    <div id="pg">
        <div id="hd">
            <div class="docwrap">
                <h1>Talking Points</h1> <h2>Administration for <?= $config->school->name ?></h2>
            </div>
        </div>
        <?php if($showNav) { ?>
        <div id="nav">
            <div class="docwrap">
                <ul>
                    <li class="logout"><a href="?action=logout">Logout</a></li>
                    <li class="<?=navClass("points") ?>"><a href="?action=points">Today's Talking Points</a></li>
                    <!--
                    <li class="<?=navClass("users") ?>"><a href="?action=users">Users</a></li>
                    <li class="<?=navClass("config") ?>"><a href="?action=config">System Configuration</a></li>
                    -->
                </ul>
            </div>
        </div>
        <?php } ?>
        <div id="bd">