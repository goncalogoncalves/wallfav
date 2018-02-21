<div id="navigation" class="navbar navbar-default navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="logo" href="/">wall<span style="color:#206b70;">fav</span></a>
		</div>
		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li><a href="/?o=s#desc">Description</a></li>
				<li><a href="/?o=s#features">Features</a></li>
				<?php if (isset($_SESSION['su_email'])) {?>
					<li><a href="/dashboard">Dashboard</a></li>
					<li><a href="/login/logout">Logout</a></li>
				<?php } else {?>
					<!--<li><a href="/register">Register</a></li>-->
					<li><a href="/login">Login</a></li>
				<?php }?>
			</ul>
		</div>
	</div>
</div>
