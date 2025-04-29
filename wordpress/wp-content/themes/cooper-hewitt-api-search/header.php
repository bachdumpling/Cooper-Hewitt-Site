<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <?php wp_head(); ?>

    <!-- 1) Define the font files -->
    <style>
        @font-face {
            font-family: "Cooper Hewitt Book";
            src: url("<?php echo get_template_directory_uri(); ?>/fonts/CooperHewitt-Book.otf") format("opentype");
            font-weight: 400;
            font-style: normal;
        }

        @font-face {
            font-family: "Cooper Hewitt Bold";
            src: url("<?php echo get_template_directory_uri(); ?>/fonts/CooperHewitt-Bold.otf") format("opentype");
            font-weight: 700;
            font-style: normal;
        }

        /* 2) Re-apply as Tailwind’s base */
        @layer base {

            html,
            body {
                font-family: "Cooper Hewitt Book", sans-serif;
            }

            h1,
            h2,
            h3,
            h4,
            h5,
            h6 {
                font-family: "Cooper Hewitt Bold", sans-serif;
            }
        }
    </style>
</head>

<body <?php body_class(); ?>>
    <header>
        <div class="site-header" class="w-full flex flex-col items-center justify-center">
            <img src="<?php echo get_template_directory_uri(); ?>/images/CH_logo-digital_black.svg"
                alt="Cooper Hewitt Logo" class="w-20 h-auto">
            <!-- <h1 class="text-2xl font-bold"><?php bloginfo('name'); ?></h1> -->
        </div>
    </header>