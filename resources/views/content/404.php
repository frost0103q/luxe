<div class="app-content wrapper">
	<main id="main" class="app-main">
		<div class="entry entry--error">
			<h1 class="entry__title"><?php esc_html_e( 'Page not found', 'luxe' ) ?></h1>
			<div class="entry__content">
				<p><?php esc_html_e( 'It looks like you stumbled upon a page that does not exist. Perhaps a search might help.', 'luxe' ) ?></p>
				<?php get_search_form() ?>
			</div>
		</div>
	</main>

	<?php if ( Luxe\display_sidebar() ) : ?>
		<?php Hybrid\View\display( 'sidebar', 'primary', [ 'name' => 'primary' ] ) ?>
	<?php endif; ?>
</div>
