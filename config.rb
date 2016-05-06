http_path = "/"
# sass_dir = "_assets/sass"
# css_dir = "assets/css"
# images_dir = "_assets/img"

# You can select your preferred output style here
# (can be overridden via the command line):
# output_style = :compressed

# The directory where generated images are kept. It is relative to the
# project_path. Defaults to the value of images_dir:

# To enable relative paths to assets via compass helper functions. Uncomment:
relative_assets = true

environment = :production
output_style = (environment == :production) ? :compressed : :expanded

# To disable debugging comments that display the original
# location of your selectors. Uncomment:
line_comments = true

# Make a copy of sprites with a name that has no uniqueness of the hash.
on_sprite_saved do |filename|
	if File.exists?(filename)
		FileUtils.cp filename, filename.gsub(%r{-s[a-z0-9]{10}\.png$}, '.png')
		# Note: Compass outputs both with and without random hash images.
		# To not keep the one with hash, add: (Thanks to RaphaelDDL for this)
		FileUtils.rm_rf(filename)
	end
end

# Replace in stylesheets generated references to sprites
# by their counterparts without the hash uniqueness.
on_stylesheet_saved do |filename|
	if File.exists?(filename)
		css = File.read filename
		File.open(filename, 'w+') do |f|
			f << css.gsub(%r{-s[a-z0-9]{10}\.png}, '.png')
		end
	end
end
