<!DOCTYPE html>
<html lang="PL">

<head>
<title><?php echo $NAZWA_STRONY?></title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="./styles/bootstrap.min.css" rel="stylesheet">
<link href="./styles/navbar-fixed-side.css" rel="stylesheet">
<link href="./styles/style.css" rel="stylesheet" type="text/css" >
<link rel="Stylesheet" type="text/css" href="./styles/menu.css" />
<style>
body {
    height:640px;
    background: url("./img/school-2648668__340.jpg") no-repeat;
    background-position: center center;
    background-size: 100% ;
    z-index: 1;
}

#text {
    background: rgba(255,239,132,.50); 
    top: 0px;
    right: 0;
    bottom: 0;
    left: 0px;
    z-index: 2;
}
#middle {
    background: rgba(255,239,132,.50); 
    z-index: 0;
}
#bottom{
   background: rgba(255,239,132,.50); 
   bottom:10px;
    z-index: 3;
}
.btn-warning.active, .btn-warning:active, .open>.dropdown-toggle.btn-warning {
    color: #fff;
    background-color: rgba(255,239,132,.50);
    border-color: rgba(255,239,132,.50);
}
.navbar-default .navbar-nav>.active>a, .navbar-default .navbar-nav>.active>a:focus, .navbar-default .navbar-nav>.active>a:hover {
    color: #555;
    background-color: #00B7EF;
}
.navbar-default {
    background-color: rgba(255,239,132,.0);
    color: #555;
    z-index: 0;
}

</style>


</head>
<body>

<div class="container-fluid row">
    <div id="top" class="row" >
		
        <div id="NAGLOWEK" class="col-xs-12">
        <img src="./img/logo.png" alt="Logo szkoly" id="obr0">
		<?php require_once 'szablony/naglowek.php';?>
		</div>
	</div>


	<div id="middle" class="col-xs-12 container-fluid row Main">
		<div class="col-sm-3 col-lg-2">
                <nav class="navbar navbar-default navbar-fixed-side">
                    <div class="container">
                        <div class="navbar-header">
                            <button class="navbar-toggle" data-target=".navbar-collapse" data-toggle="collapse">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a class="navbar-brand">MENU</a>
                        </div>
                        <div class="collapse navbar-collapse">
                            <?php require_once 'szablony/menu.php';?>
                        </div>
                    </div>
                </nav>
		</div>
		<div id="text" class="col-sm-9 col-lg-10">
		<?php echo $TRESC?>
		</div>
	</div>
	

	<div id="bottom" class="row Footer">
        <div id="STOPKA" class="col-xs-12"><?php require_once 'szablony/stopka.php';?>
		</div>
    </div>
</div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="./js/bootstrap.min.js"></script>
</body>

</html>