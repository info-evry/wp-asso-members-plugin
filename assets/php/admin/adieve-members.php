<?php

if (!function_exists('adieve_set_members_page')) {
	function adieve_set_members_page()
	{
		add_menu_page(
			"Membres de l'ADIEVE",
			'Membres',
			'manage_options',
			'adieve_members',
			'adieve_members_page',
			'dashicons-buddicons-buddypress-logo'
		);
	}
}
add_action('admin_menu', 'adieve_set_members_page');

if (!function_exists('adieve_members_page')) {
	function adieve_members_page()
	{
		$members = new Members_List();
		?><div class="wrap">
			<h1 class="h1"><?php esc_html_e("Membres de l'ADIEVE", 'adieve'); ?></h1>
			<div id="post-body" class="metabox-holder columns-7">
				<div id="post-body-content">
					<div class="meta-box-sortables ui-sortable">
						<form method="post" id="posts-filter"><?php
																		$members->prepare_items();
																		$members->display();
																		?></form>
					</div>
				</div>
			</div>
			<br class="clear">
		</div><?php
					}
				}
