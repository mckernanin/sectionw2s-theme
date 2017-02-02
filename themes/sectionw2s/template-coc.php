<?php
/**
 * Template Name: COC Profiles
 */
get_header();

$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() ); ?>

<div id="main-content">

<?php if ( ! $is_page_builder_used ) : ?>

	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">

<?php endif; ?>
			<?php the_post();

				// Get 'lec-member' posts
				$team_posts = get_posts( array(
					'post_type' => 'positions',
					'posts_per_page' => -1, // Unlimited posts
					'orderby' => 'menu_order', // Order in admin
					'order' => 'ASC',
				) );

				if ( $team_posts ):
				?>
				<div class="intro">
					<h2><?php the_title(); ?></h2>
					<p class="lead"><?php the_content(); ?></p>
				</div>
				<div class="filter-buttons">
					<div class="group">
						<label>Filter</label>
						<a class="filter" data-filter="all" href="#">All</a>
						<a class="filter" data-filter=".lodge-ha-kin-skay-a-ki" href="#">Ha-Kin-Skay-A-Ki</a>
						<a class="filter" data-filter=".lodge-mic-o-say" href="#">Mic-o-say</a>
						<a class="filter" data-filter=".lodge-tahosa" href="#">Tahosa</a>
						<a class="filter" data-filter=".lodge-tupwee" href="#">Tupwee</a>
						<a class="filter" data-filter=".open" href="#">Open Positions</a>
					</div>
					<div class="group">
						<label>Sort</label>
						<a class="sort active" data-sort="default" href="#">by Position</a>						
						<a class="sort" data-sort="lname:asc" href="#">by Last Name</a>
					</div>
				</div>			
				<section class="row profiles">
					<?php 
					foreach ( $team_posts as $post ): 
					setup_postdata($post);

					$person = get_field('person')[0];
					
					// Resize and CDNize thumbnails using Automattic Photon service
					$thumb_src = null;
					if ( has_post_thumbnail($person) ) {
						$src = wp_get_attachment_image_src( get_post_thumbnail_id( $person ), 'medium' );
						$thumb_src = $src[0];
					}

					else $thumb_src = get_field('placeholder', 'option');

					$first_name = get_field('first_name', $person);
					$last_name = get_field('last_name', $person);
					$youth_or_adviser = get_field('youth_or_adviser', $person);
					$lodge = get_field('lodge', $person);
					$membership_level = get_field('membership_level', $person);
					$phone_number = get_field('phone_number', $person[0]);
					$last_initial_only = get_field('last_initial_only', $person);

					if (get_field('is_position_available') == true) {
						$available = 'open';
					} else {
						$available = '';
					};

					if ( $last_initial_only == true ) {
						$lname_final = substr($last_name, 0, 1);
					} else {
						$lname_final = $last_name;
					};
					
					?>
					<article class="col-sm-6 profile <?php echo ' lodge-' . strtolower($lodge); ?> <?php echo $available; ?>" data-lname="<?php echo $lname_final; ?>">

						<div class="profile-header">
							<img src="<?php echo $thumb_src; ?>" alt="Headshot" class="img-circle">
						</div>
						
						<div class="profile-content">
							<p class="lead position"><?php the_title(); ?></p>
							<h3>
							<?php if ($available == 'true') {
								echo "Position Available";
							} else {
								echo $first_name.' '.$lname_final;
							}
							?>
							</h3>
							<?php if (!empty($lodge)) : ?><p class="levelandchapter"><?php echo $membership_level; ?> Member of <?php echo $lodge; ?></p><?php endif; ?>	
							<?php if ( is_user_logged_in() ) : ?>	
								<?php if (!empty($phone_number)) : ?>
	    							<p class="phonenumber"><span class="icon_phone"></span><?php echo get_field('phone_number'); ?></p>
								<?php endif; ?>
							<?php endif; ?>	
						</div>
						
						<div class="profile-footer">
							<a href="mailto:<?php echo antispambot( get_field('team_email', $person) ); ?>"><span class="dashicons dashicons-email"></span></a>
							<?php if (current_user_can('edit_posts')) { ?><a href="<?php echo get_edit_post_link(); ?>" target="_blank"><div class="dashicons dashicons-edit"></div></a><?php } ?>
						</div>
					</article>
					<?php endforeach; ?>
				</section>
<?php endif; ?>

<?php if ( ! $is_page_builder_used ) : ?>

			</div> <!-- #left-area -->

		</div> <!-- #content-area -->
	</div> <!-- .container -->

<?php endif; ?>

</div> <!-- #main-content -->

<?php get_footer(); ?>