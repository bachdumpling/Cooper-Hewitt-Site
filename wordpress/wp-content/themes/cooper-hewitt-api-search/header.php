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
            font-family: "Cooper Hewitt";
            src: url("<?php echo get_template_directory_uri(); ?>/fonts/CooperHewitt-Book.otf") format("opentype");
            font-weight: 400;
            font-style: normal;
        }

        @font-face {
            font-family: "Cooper Hewitt";
            src: url("<?php echo get_template_directory_uri(); ?>/fonts/CooperHewitt-Bold.otf") format("opentype");
            font-weight: 700;
            font-style: normal;
        }

        @font-face {
            font-family: "Cooper Hewitt";
            src: url("<?php echo get_template_directory_uri(); ?>/fonts/CooperHewitt-Heavy.otf") format("opentype");
            font-weight: 900;
            font-style: normal;
        }

        @font-face {
            font-family: "Cooper Hewitt";
            src: url("<?php echo get_template_directory_uri(); ?>/fonts/CooperHewitt-Medium.otf") format("opentype");
            font-weight: 500;
            font-style: normal;
        }

        @font-face {
            font-family: "Cooper Hewitt";
            src: url("<?php echo get_template_directory_uri(); ?>/fonts/CooperHewitt-Light.otf") format("opentype");
            font-weight: 300;
            font-style: normal;
        }

        @font-face {
            font-family: "Cooper Hewitt";
            src: url("<?php echo get_template_directory_uri(); ?>/fonts/CooperHewitt-Thin.otf") format("opentype");
            font-weight: 100;
            font-style: normal;
        }

        @font-face {
            font-family: "Cooper Hewitt";
            src: url("<?php echo get_template_directory_uri(); ?>/fonts/CooperHewitt-Semibold.otf") format("opentype");
            font-weight: 600;
            font-style: normal;
        }

        /* 2) Re-apply as Tailwind's base */
        @layer base {

            html,
            body {
                font-family: "Cooper Hewitt", sans-serif;
                min-height: 100vh;
            }
        }
    </style>
</head>

<body <?php body_class('flex flex-col min-h-screen'); ?>>
    <header class="site-header w-full flex flex-row justify-between items-center px-6 py-4 bg-black text-white">
        <img src="<?php echo get_template_directory_uri(); ?>/images/CH_logo-digital_white.svg" alt="Cooper Hewitt Logo"
            class="w-20 h-auto md:w-32">
        <div class="flex flex-col justify-end items-end">
            <h1 class="text-xs sm:text-sm md:text-xl font-bold">Cooper Hewitt Code Assignment</h1>
            <h6 class="text-xs sm:text-sm md:text-sm italic font-medium">by Bach Le</h6>
        </div>

    </header>