<?php require APP_PATH . '/src/Wallfav/Views/layouts/top_inicio.php'; ?>

<?php require APP_PATH . '/src/Wallfav/Views/layouts/topbar.php'; ?>


<div class="container">
	<div class="row centered">
		<div class="col-lg-12" style="padding-bottom: 20px; ">
			<img src="<?php echo BASE_URL; ?>img/logo_wallfav.png" height="58" width="229">
			<h3>Put your favourite websites on a wall!</h3>
			<br>
		</div>
		<div class="col-lg-12">
			<img class="img-responsive" src="img/app-bg.png" alt="wallfav - put your favourite websites on a wall!">
		</div>
	</div>
</div>

<section id="desc" name="desc"></section>
<div class="website-descricao text-center">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<h2>Organize your websites in a simple and intuitive way!</h2>
				<p>Don't you ever lose a link again.</p>
				<p><a class="btn btn-lg btn-primary " href="/login/register" style="padding: 16px 35px;"><i class="fa fa-fw fa-check"></i> Register Now, it's FREE!</a></p>
			</div>
		</div>
	</div>
</div>

<section id="features" name="features"></section>
<div class="section" style="padding: 30px;">
	<div class="container">
		<h1 class="centered features-title">Features</h1>
		<div class="row">
			<div class="col-lg-4 col-md-4">
				<h3><i class="fa fa-fw fa-circle-o"></i> Unlimited Websites</h3>
				<p>Save as much as you want.</p>
			</div>
			<div class="col-lg-4 col-md-4">
				<h3><i class="fa fa-fw fa-arrows"></i> Drag & Drop</h3>
				<p>A simple way to organize your websites, just move them around in an intuitive way.</p>
			</div>
			<div class="col-lg-4 col-md-4">
				<h3><i class="fa fa-fw fa-cloud-download"></i> Export Your Websites</h3>
				<p>You can always save your websites to your computer. So be relax and enjoy being here ;).</p>
			</div>


		</div>
		<div class="row">
			<div class="col-lg-4 col-md-4">
				<h3><i class="fa fa-fw fa-globe"></i> Discover New Websites</h3>
				<p>You can find everyday new and exciting websites so you wont be bored.</p>
			</div>


			<div class="col-lg-4 col-md-4">
				<h3><i class="fa fa-fw fa-desktop"></i> Multiple Devices</h3>
				<p>You can reach your websites from your computer, from a tablet or your smartphone. The information is saved in the cloud.</p>
			</div>
			<div class="col-lg-4 col-md-4">
				<h3><i class="fa fa-fw fa-check"></i> Simplicity</h3>
				<p>Find your websites fast. Don't you ever search in many subfolders again!</p>
			</div>
		</div>
		<div class="row">

			<!--<div class="col-lg-4 col-md-4">
				<h3><i class="fa fa-fw fa-asterisk"></i> Cross Browser</h3>
				<p>Use it Chrome, Firefox, IE, Opera or other browser of your choosing.</p>
			</div>
			<div class="col-lg-4 col-md-4">
				<h3><i class="fa fa-fw fa-adjust"></i> Clear Layout</h3>
				<p>Be pleased just from watching your wall.</p>
			</div>-->
			<!--<div class="col-lg-4 col-md-4">
				<h3><i class="fa fa-fw fa-bolt"></i> Blazing Fast</h3>
				<p>Enjoy the speed.</p>
			</div>-->
		</div>

	</div>
</div>

<div class="website-coffee text-center">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<h2>If you like the website, buy me a coffee!</h2>
				<p>Every Web Developer needs one.</p>
				<p>
					<a class="btn btn-lg btn-primary " target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=goncalofbg%40gmail%2ecom&lc=US&item_name=Goncalo%20Goncalves%20%2d%20WallFav&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted"><i class="fa fa-fw fa-coffee"></i> Buy me a coffee!</a>
					<br><span style="font-size:0.9em;">(paypal)</span>
				</p>
			</div>
		</div>
	</div>
</div>

<div class="container ">
	<div class="row">
		<div class="col-lg-12" >





			<table class="table-social">
				<tr>
					<td colspan="3">
						<div ><a target="_blank" title="Wallfav Facebook Page" href="https://www.facebook.com/wallfav"><i class="fa fa-fw fa-facebook"></i> Facebook Page</a></div>
						<div ><a target="_blank" title="Wallfav Twitter Page" href="https://twitter.com/wallfav"><i class="fa fa-fw fa-twitter"></i> Twitter Page</a></div>
						<div ><a target="_blank" title="Wallfav Google+ Page" href="https://plus.google.com/b/112182452867330583039/112182452867330583039/about"><i class="fa fa-fw fa-google-plus"></i> Google+ Page</a></div>
					</td>
				</tr></table>
				<table class="table-social">
					<tr>
						<td>
							<div class="fb-share-button" data-href="https://www.facebook.com/wallfav" data-type="button"></div>
						</td>
						<td>
							<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://wallfav.com/" data-text="wallfav.com looks awesome!" data-count="none" data-hashtags="wallfav">Tweet</a>
							<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
						</td>
						<td>
							<div class="g-plus" data-action="share" data-annotation="none" data-href="http://wallfav.com/"></div>
						</td>
					</tr>
				</table>

			</div>
		</div>
	</div>

	<?php require APP_PATH . '/src/Wallfav/Views/layouts/bottom_inicio.php'; ?>

