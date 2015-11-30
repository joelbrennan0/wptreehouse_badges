<h3>Treehouse Badges Plugin</h3>

<div class="wrap">

	<h2>Admin Area</h2>
	<div id="col-container">

		<div id="col-left" style="float:left">

			<div class="col-wrap">
				<h3><?php echo $wptreehouse_username; ?></h3>
				<div class="inside">
					<img width="100%" src="<?php echo $wptreehouse_profile->{'gravatar_url'}; ?>">

					<ul>
						<li>Total Badges: <?php echo count($wptreehouse_profile->{'badges'}); ?></li>
						<li>Total Points: <?php echo $wptreehouse_profile->{'points'}->{'total'}; ?></li>
					</ul>

					<form name="wptreehouse_username_form" method="post" action="">

					<input type="hidden" name="wptreehouse_form_submitted" value="Y">
						<p>
							<label for="wptreehouse_username">
								Usermame
							</label>
						</p>
						<p>
							<input type="text" name="wptreehouse_username" value="<?php echo $wptreehouse_username ?>" /><br>
						</p>									
						<p>
							<input class="button-primary" type="submit" name="treehouse_username_submit" value="update" />
						</p>
					</form>
				</div>
			</div>
			<!-- /col-wrap -->

		</div>
		<!-- /col-left -->

		<div id="col-right">

			<div class="col-wrap">
				<h3><span>Column One</span></h3>
				<div class="inside">
					<h3><?php esc_attr_e( 'Tables', 'wp_admin_style' ); ?></h3>

					<?php if (!isset($wptreehouse_username) || $wptreehouse_username == '') :?>

					<form name="wptreehouse_username_form" method="post" action="">

					<input type="hidden" name="wptreehouse_form_submitted" value="Y">
						<table class="form-table">
							<tr>
								<td>
									<label for="wptreehouse_username">
										Treehouse usermame
									</label>
								</td>
								<td>
								<input type="text" name="wptreehouse_username" placeholder="username" class="regular-text code" /><br>
								</td>
							</tr>
						</table>
						<p>
							<input class="button-primary" type="submit" name="treehouse_username_submit" value="Save" />
						</p>
					</form>
				</div>
			</div>
			<!-- /col-wrap -->
		<?php else : ?>
			<div class="col-wrap">
				<h3><span>Recent Badges</span></h3>
				<div class="inside">
					<?php require( 'badges-loop.php' ); ?>
				</div>

			</div>

			<div class="col-wrap">
				<h3><span>Recent Badges</span></h3>
				<div class="inside">
				<pre><code>
					<?php var_dump( $wptreehouse_profile ); 
					?>
				</code></pre>
					
				</div>

			</div>
			<!-- /col-wrap -->
		<?php endif; ?>

		</div>
		<!-- /col-right -->

	</div>
	<!-- /col-container -->

</div> <!-- .wrap -->