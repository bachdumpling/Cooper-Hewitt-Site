<?php get_header(); ?>

<main class="max-w-3xl mx-auto mt-10 flex flex-col items-center justify-center w-full">
    <div class="site-header" class="w-full flex flex-col items-center justify-center">
        <img src="<?php echo get_template_directory_uri(); ?>/images/CH_logo-digital_black.svg" alt="Cooper Hewitt Logo"
            class="w-full h-auto">
        <h1 class="text-2xl font-bold"><?php bloginfo('name'); ?></h1>
    </div>
    
    <h2>Search the Cooper Hewitt API</h2>
</main>

<?php get_footer(); ?>