<ul class="wptreehouse-badges">

	<?php
	$recentBadges =  array_reverse($wptreehouse_profile->{'badges'});
	for( $i = 0; $i < 20; $i++ ): ?>
	<li>
		<ul>
			<li>
				<div class="badge-wrap">
					<img src="<?php echo $recentBadges[$i]->{'icon_url'}; ?>">	
				</div>							
			</li>
			<?php if ($recentBadges[$i]->{'url'} != $wptreehouse_profile->{'profile_url'} ): ?>							
			<li class="wptreehouse-badge-name">
				<a href="<?php echo $recentBadges[$i]->{'url'} ?>" target="_blank"><?php echo $recentBadges[$i]->{'name'} ?></a>
			</li>
			<li class="wptreehouse-project-name">
			<?php $courseName = $recentBadges[$i]->{'courses'}[0]->{'title'};
			$courseUrl = $recentBadges[$i]->{'courses'}[0]->{'url'}; ?>
				<a href="<?php echo $courseUrl ?>" target="_blank"><?php echo $courseName; ?></a>
			</li>
			<?php else: ?>
			<li class="wptreehouse-badge-name">
				<?php echo $recentBadges[$i]->{'name'} ?>
			</li>
			<?php endif; ?>
		</ul>									
	</li>								
	<?php endfor; ?>

</ul>