# Public Repo for living-sober Kleo child theme

This repo contains the child theme for Kleo that we have used on the [livingsober.org.nz](https://livingsober.org.nz) website from 2018-2023.

The child theme needs to be installed side by side with the parent theme. Use of child themes should be considered best practice, it allows you to overide parts of the parent theme without having to modify it. This allows for updates to be pulled from the theme developers without a need to deal with merge conflicts. Note you still may have braking changes that are being imported, but at least this way you can roll back to an earlier parent theme version if need be.

The LS child theme contains a few types of files:

-   functions.php contains any custom php code
-   php overide files, mostly for templates
-   php overide files for plugins
-   custom js, namely the sober calculator
-   custom styles to alter the themes appearance to match our desired brand/look

Note when setting this up you will need copy out the sober-calculator html snippet into a html block in a page on the site - likely into a page builder block.
