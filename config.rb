# This is the configuration file for Compass (http://compass-style.org/)
# See http://compass-style.org/help/tutorials/configuration-reference/ on how to use this file

#environment     = :development
environment     = :production

sass_dir        = "sass"
css_dir         = "css"
images_dir      = "images"
relative_assets = true

# The output style for the compiled css. One of: :nested, :expanded, :compact, or :compressed.
output_style    = (environment == :development) ? :expanded : :compact
sass_options    = (environment == :development) ? {:debug_info => true} : {}
