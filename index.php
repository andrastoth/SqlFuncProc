<!doctype html>
	<html>
	<head>
		<title>SqlProcFunc</title>
   		<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
		<style type="text/css">

		.panel-footer {
			display: inline-table;
			width: 100%;
			}
		.panel-body td{

			white-space: pre-wrap;
			}
		.container{
			margin-top: 20px;
			width: 100%;
		}
    </style>
	</head>
	<body>
		<div class="container">
		<div class="panel panel-info">
  			<div class="panel-heading">
				<h3>Get instance with PDO connection parameters and wiev functions</h3>
  			</div>
	  		<div class="panel-body">
					<?php
					
						require_once('SqlFuncProc.php');
						//$func = SqlFuncProc::getInstance("mysql:host=YOURSERVER;dbname=AdventureWorks2012;charset=utf8", yourname, yourpass);
						$func = SqlFuncProc::getInstance("sqlsrv:Server=YOURSERVER;Database=AdventureWorks2012", null, null);
						echo $func->wievFunc('', "table table-bordered");
					?>	
			 </div>
			 <div class="panel-footer">
<code id="codeNoerror" name="code"><pre>
&lt;?php
	require_once('SqlFuncProc.php');
	// get instance with PDO connection data
	//$func = SqlFuncProc::getInstance("mysql:host=YOURSERVER;dbname=AdventureWorks2012;charset=utf8", yourname, yourpass);
	$func = SqlFuncProc::getInstance("sqlsrv:Server=YOURSERVER;Database=AdventureWorks2012", null, null);
	// Functions list from folder with parameters : wievFunc($name = "", $class="")
	// $name: chars from filename, $class: table class
	echo $func->wievFunc("", "table table-bordered");
?&gt;
</pre></code>			 	
			 </div>
		</div>
		<div class="panel panel-info">
  			<div class="panel-heading">
				<h3>Wiev procedures</h3>
  			</div>
	  		<div class="panel-body">
					<?php
						echo $func->wievProc('', "table table-bordered");
					?>	
			 </div>
			 <div class="panel-footer">
<code id="codeNoerror" name="code"><pre>
&lt;?php
	// Processes list from folder with parameters : wievFunc($name = "", $class="")
	// $name: chars from filename, $class: table class
	echo $func->wievProc("", "table table-bordered");
?&gt;
</pre></code>			 	
			 </div>
		</div>
		<div class="panel panel-danger">
  			<div class="panel-heading">
  			<h3>Run process: error</h3>
  			</div>
	  		<div class="panel-body">
					<?php
					$data = $func->runProc('error');
					print_r($data);
					?>	
			 </div>
			 <div class="panel-footer">
<code id="codeNoerror" name="code"><pre>
&lt;?php
	//Run error proc runProc($proc, $params = array(), $force = false);
	//$proc: process name, $params: parameter array
	$data = $func->runProc('error');
	print_r($data);
?&gt;
</pre></code>			 	
			 </div>
		</div>
		<div class="panel panel-success">
  			<div class="panel-heading">
  			<h3>Run process: noerror</h3>
  			</div>
	  		<div class="panel-body">
					<?php
					$data = $func->runProc('noerror', array(50));
					print_r($data);
					?>	
			 </div>
			 <div class="panel-footer">
<code id="codeNoerror" name="code"><pre>
&lt;?php
	//Run noerror proc runProc($proc, $params = array(), $force = false);
	//$proc: process name, $params: parameter array
	$data = $func->runProc('noerror', array(50));
	print_r($data);
?&gt;
</pre></code>				 	
			 </div>
		</div>
		<div class="panel panel-primary">
  			<div class="panel-heading">
				<h3>Run function OrganizationTree and create HTML table</h3>
  			</div>
	  		<div class="panel-body">
					<?php
						$data = $func->runFunc("OrganizationTree",array(1,0), 10);
						$data = $func->getHTMLtable($data, "", "table table-striped", true);
						echo $data;
					?>	
			 </div>
			 <div class="panel-footer">

<code id="codeNoerror" name="code"><pre>
&lt;?php
	//Run OrganizationTree func: runFunc($proc, $params = array(), $limit = false) 
	//$proc: process name, $params: parameter array, $limit : returned rows count
	//return  array()
  	$data = $func->runFunc("OrganizationTree",array(1,0), 10);
  	//Create HTML table from array: getHTMLtable($array = array(), $id = "", $class = "", $head = true) 
  	echo $func->getHTMLtable($data, "", "table table-striped", true);
?&gt;
</pre></code>		 	
			 </div>
			</div>
		</div>
	</body>
	</html>
