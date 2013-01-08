<?php

/*
Copyright 2008-2009 iThemes (email: support@ithemes.com)

Written by Chris Jean
Version 1.5.0

Version History
	1.4.6 - 2009-07-14
		Merged into classes directory
	1.4.7 - 2010-07-15
		Improved get_file_from_url and get_url_from_file to better handle
			WordPress 3.0 multisite paths and URLs.
	1.5.0 - 2010-12-14
		Added ITFileUtility functions:
			write
			get_writable_uploads_directory
			find_writable_path
			create_writable_path
			create_writable_file
			get_file_listing
			mkdir
			copy
			delete_directory
		Added compat function sys_get_temp_dir
*/


if ( !class_exists( 'ITFileUtility' ) ) {
	class ITFileUtility {
		function file_uploaded( $file_id ) {
			if ( ! empty( $_FILES[$file_id] ) && ( '4' != $_FILES[$file_id]['error'] ) )
				return true;
			return false;
		}
		
		function upload_file( $file_id ) {
			$overrides = array( 'test_form' => false );
			$file = wp_handle_upload( $_FILES[$file_id], $overrides );
			
			if ( isset( $file['error'] ) )
				return new WP_Error( 'upload_error', $file['error'] );
			
			$url = $file['url'];
			$type = $file['type'];
			$file = $file['file'];
			$title = preg_replace( '/\.[^.]+$/', '', basename( $file ) );
			$content = '';
			
			if ( $image_meta = @wp_read_image_metadata( $file ) ) {
				if ( trim( $image_meta['title'] ) )
					$title = $image_meta['title'];
				if ( trim( $image_meta['caption'] ) )
					$content = $image_meta['caption'];
			}
			
			$attachment = array(
				'post_mime_type'	=> $type,
				'guid'				=> $url,
				'post_title'		=> $title,
				'post_content'		=> $content
			);
			
			$id = wp_insert_attachment( $attachment, $file );
			if ( !is_wp_error( $id ) )
				wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $file ) );
			
			
			$data = array(
				'id'		=> $id,
				'url'		=> $url,
				'type'		=> $type,
				'file'		=> $file,
				'title'		=> $title,
				'caption'	=> $content
			);
			
			return $data;
		}
		
		function get_image_dimensions( $file ) {
			if ( is_numeric( $file ) ) {
				$file_info = ITFileUtility::get_file_attachment( $file );
				
				if ( false === $file_info )
					return new WP_Error( 'error_loading_image_attachment', "Could not find requested file attachment ($file)" );
				
				$file = $file_info['file'];
			}
			
			list ( $width, $height, $type ) = getimagesize( $file );
			
			return array( $width, $height );
		}
		
		function resize_image( $file, $max_w = 0, $max_h = 0, $crop = true, $suffix = null, $dest_path = null, $jpeg_quality = 90 ) {
			if ( is_numeric( $file ) ) {
				$file_info = ITFileUtility::get_file_attachment( $file );
				
				if ( false === $file_info )
					return new WP_Error( 'error_loading_image_attachment', "Could not find requested file attachment ($file)" );
				
				$file = $file_info['file'];
			}
			else
				$file_attachment_id = '';
			
			if ( preg_match( '/\.ico$/', $file ) )
				return array( 'file' => $file, 'url' => ITFileUtility::get_url_from_file( $file ), 'name' => basename( $file ) );
			
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
			
			$image = wp_load_image( $file );
			if ( ! is_resource( $image ) )
				return new WP_Error( 'error_loading_image', $image );
			
			list( $orig_w, $orig_h, $orig_type ) = getimagesize( $file );
			$dims = ITFileUtility::_image_resize_dimensions( $orig_w, $orig_h, $max_w, $max_h, $crop );
			if ( ! $dims )
				return new WP_Error( 'error_resizing_image', "Could not resize image" );
			list( $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h ) = $dims;
			
			
			if ( ( $orig_w == $dst_w ) && ( $orig_h == $dst_h ) )
				return array( 'file' => $file, 'url' => ITFileUtility::get_url_from_file( $file ), 'name' => basename( $file ) );
			
			if ( ! $suffix )
				$suffix = "resized-image-${dst_w}x${dst_h}";
			
			$info = pathinfo( $file );
			$dir = $info['dirname'];
			$ext = $info['extension'];
			$name = basename( $file, ".${ext}" );
			
			if ( ! is_null( $dest_path ) && $_dest_path = realpath( $dest_path ) )
				$dir = $_dest_path;
			$destfilename = "${dir}/${name}-${suffix}.${ext}";
			
			
			if ( file_exists( $destfilename ) ) {
				if ( filemtime( $file ) > filemtime( $destfilename ) )
					unlink( $destfilename );
				else
					return array( 'file' => $destfilename, 'url' => ITFileUtility::get_url_from_file( $destfilename ), 'name' => basename( $destfilename ) );
			}
			
			
			// ImageMagick cannot resize animated PNG files yet, so this only works for
			// animated GIF files.
			$animated = false;
			if ( ITFileUtility::is_animated_gif( $file ) ) {
				$coalescefilename = "${dir}/${name}-coalesced-file.${ext}";
				
				if ( ! file_exists( $coalescefilename ) )
					system( "convert $file -coalesce $coalescefilename" );
				
				if ( file_exists( $coalescefilename ) ) {
					system( "convert -crop ${src_w}x${src_h}+${src_x}+${src_y}! $coalescefilename $destfilename" );
					
					if ( file_exists( $destfilename ) ) {
						system( "mogrify -resize ${dst_w}x${dst_h} $destfilename" );
						system( "convert -layers optimize $destfilename" );
						
						$animated = true;
					}
				}
			}
			
			
			if ( ! $animated ) {
				$newimage = imagecreatetruecolor( $dst_w, $dst_h );
				
				// preserve PNG transparency
				if ( IMAGETYPE_PNG == $orig_type && function_exists( 'imagealphablending' ) && function_exists( 'imagesavealpha' ) ) {
					imagealphablending( $newimage, false );
					imagesavealpha( $newimage, true );
				}
				
				imagecopyresampled( $newimage, $image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h );
				
				// we don't need the original in memory anymore
				if ( $orig_type == IMAGETYPE_GIF ) {
					if ( ! imagegif( $newimage, $destfilename ) )
						return new WP_Error( 'resize_path_invalid', __( 'Resize path invalid' ) );
				}
				elseif ( $orig_type == IMAGETYPE_PNG ) {
					if ( ! imagepng( $newimage, $destfilename ) )
						return new WP_Error( 'resize_path_invalid', __( 'Resize path invalid' ) );
				}
				else {
					// all other formats are converted to jpg
					$destfilename = "{$dir}/{$name}-{$suffix}.jpg";
					if ( ! imagejpeg( $newimage, $destfilename, apply_filters( 'jpeg_quality', $jpeg_quality ) ) )
						return new WP_Error( 'resize_path_invalid', __( 'Resize path invalid' ) );
				}
				
				imagedestroy( $newimage );
			}
			
			imagedestroy( $image );
			
			
			// Set correct file permissions
			$stat = stat( dirname( $destfilename ) );
			$perms = $stat['mode'] & 0000666; //same permissions as parent folder, strip off the executable bits
			@ chmod( $destfilename, $perms );
			
			
			return array( 'file' => $destfilename, 'url' => ITFileUtility::get_url_from_file( $destfilename ), 'name' => basename( $destfilename ) );
		}
		
		// Customized image_resize_dimensions() from 2.6.3 wp-admin/includes/media.php (cheanged to resize to fill on crop)
		function _image_resize_dimensions( $orig_w, $orig_h, $dest_w = 0, $dest_h = 0, $crop = false ) {
			if ( ( $orig_w <= 0 ) || ( $orig_h <= 0 ) )
				return new WP_Error ( 'error_resizing_image', "Supplied invalid original dimensions ($orig_w, $orig_h)" );
			if ( ( $dest_w < 0 ) || ( $dest_h < 0 ) )
				return new WP_Error ( 'error_resizing_image', "Supplied invalid destination dimentions ($dest_w, $dest_h)" );
			
			
			if ( ( $dest_w == 0 ) || ( $dest_h == 0 ) )
				$crop = false;
			
			
			$new_w = $dest_w;
			$new_h = $dest_h;
			
			$s_x = 0;
			$s_y = 0;
			
			$crop_w = $orig_w;
			$crop_h = $orig_h;
			
			
			if ( $crop ) {
				$cur_ratio = $orig_w / $orig_h;
				$new_ratio = $dest_w / $dest_h;
				
				if ( $cur_ratio > $new_ratio ) {
					$crop_w = floor( $orig_w / ( ( $dest_h / $orig_h ) / ( $dest_w / $orig_w ) ) );
					$s_x = floor( ( $orig_w - $crop_w ) / 2 );
				}
				elseif ( $new_ratio > $cur_ratio ) {
					$crop_h = floor( $orig_h / ( ( $dest_w / $orig_w ) / ( $dest_h / $orig_h ) ) );
					$s_y = floor( ( $orig_h - $crop_h ) / 2 );
				}
			}
			else
				list( $new_w, $new_h ) = wp_constrain_dimensions( $orig_w, $orig_h, $dest_w, $dest_h );
			
			
			return array( 0, 0, $s_x, $s_y, $new_w, $new_h, $crop_w, $crop_h );
		}
		
		function get_url_from_file( $file ) {
			$url = '';
			
			if ( ( $uploads = wp_upload_dir() ) && ( false === $uploads['error'] ) ) {
				if ( 0 === strpos( $file, $uploads['basedir'] ) )
					$url = str_replace( $uploads['basedir'], $uploads['baseurl'], $file );
				else if ( false !== strpos( $file, 'wp-content/uploads' ) )
					$url = $uploads['baseurl'] . substr( $file, strpos( $file, 'wp-content/uploads' ) + 18 );
			}
			
			if ( empty( $url ) )
				$url = get_option( 'siteurl' ) . str_replace( '\\', '/', str_replace( rtrim( ABSPATH, '\\\/' ), '', $file ) );
			
			return $url;
		}
		
		function get_file_from_url( $url ) {
			$file = '';
			
			if ( ( $uploads = wp_upload_dir() ) && ( false === $uploads['error'] ) ) {
				if ( 0 === strpos( $url, $uploads['baseurl'] ) )
					$file = str_replace( $uploads['baseurl'], $uploads['basedir'], $url );
				else if ( false !== strpos( $url, 'wp-content/uploads' ) )
					$file = $uploads['basedir'] . substr( $url, strpos( $url, 'wp-content/uploads' ) + 18 );
			}
			
			if ( empty( $file ) )
				$file = ABSPATH . get_option( 'upload_path' ) . '/' . ltrim( $url, get_option( 'siteurl' ) . '/files' );
			
			return $file;
		}
		
		function get_mime_type( $file ) {
			if ( preg_match( '|^https?://|', $file ) )
				$file = get_file_from_url( $file );
			
			return mime_content_type( $file );
		}
		
		function get_file_attachment( $id ) {
			if ( wp_attachment_is_image( $id ) ) {
				$post = get_post( $id );
				
				$file = array();
				$file['ID'] = $id;
				$file['file'] = get_attached_file( $id );
				$file['url'] = wp_get_attachment_url( $id );
				$file['title'] = $post->post_title;
				$file['name'] = basename( get_attached_file( $id ) );
				
				return $file;
			}
			
			return false;
		}
		
		function delete_file_attachment( $id ) {
			if ( wp_attachment_is_image( $id ) ) {
				$file = get_attached_file( $id );
				
				$info = pathinfo( $file );
				$ext = $info['extension'];
				$name = basename( $file, ".$ext" );
				
				
				if ( $dir = opendir( dirname( $file ) ) ) {
					while ( false !== ( $filename = readdir( $dir ) ) ) {
						if ( preg_match( "/^$name-resized-image-\d+x\d+\.$ext$/", $filename ) )
							unlink( dirname( $file ) . '/' . $filename );
						elseif ( "$name-coalesced-file.$ext" === $filename )
							unlink( dirname( $file ) . '/' . $filename );
					}
					
					closedir( $dir );
				}
				
				unlink( $file );
				
				
				return true;
			}
			
			return false;
		}
		
		// Can only detect animated GIF files, which is fine because ImageMagick doesn't seem
		// to be able to resize animated PNG (MNG) files yet.
		function is_animated_gif( $file ) {
			$filecontents=file_get_contents($file);
			
			$str_loc=0;
			$count=0;
			while ($count < 2) # There is no point in continuing after we find a 2nd frame
			{
				$where1=strpos($filecontents,"\x00\x21\xF9\x04",$str_loc);
				if ($where1 === FALSE)
				{
					break;
				}
				else
				{
					$str_loc=$where1+1;
					$where2=strpos($filecontents,"\x00\x2C",$str_loc);
					if ($where2 === FALSE)
					{
						break;
					}
					else
					{
						if ($where1+8 == $where2)
						{
							$count++;
						}
						$str_loc=$where2+1;
					}
				}
			}
			
			if ($count > 1)
				return(true);
			return(false);
		}
		
		function write( $path, $content, $append = false ) {
			$mode = ( false === $append ) ? 'w' : 'a';
			
			if ( ! is_dir( dirname( $path ) ) ) {
				ITFileUtility::mkdir( dirname( $path ) );
				
				if ( ! is_dir( dirname( $path ) ) )
					return false;
			}
			
			if ( false === ( $handle = fopen( $path, $mode ) ) )
				return false;
			
			$result = fwrite( $handle, $content );
			fclose( $handle );
			
			if ( false === $result )
				return false;
			return true;
		}
		
		function get_writable_uploads_directory( $directory ) {
			$uploads = wp_upload_dir();
			
			if ( ! is_array( $uploads ) || ( false !== $uploads['error'] ) )
				return false;
			
			
			$path = "{$uploads['basedir']}/$directory";
			
			if ( ! is_dir( $path ) ) {
				ITFileUtility::mkdir( $path );
				
				if ( ! is_dir( $path ) )
					return false;
			}
			if ( ! is_writable( $path ) )
				return false;
			
			$directory_info = array(
				'path'		=> $path,
				'url'		=> "{$uploads['baseurl']}/$directory",
			);
			
			return $directory_info;
		}
		
		function find_writable_path( $args = array() ) {
			$default_args = array(
				'private'			=> true,
				'possible_paths'	=> array(),
			);
			
			$args = array_merge( $default_args, $args );
			
			foreach ( (array) $args['possible_paths'] as $path ) {
				if ( ! is_dir( $path ) )
					ITFileUtility::mkdir( $path );
				
				if ( is_writable( $path ) ) {
					$writable_dir = $path;
					break;
				}
			}
			
			if ( empty( $writable_dir ) || ! is_writable( $writable_dir ) ) {
				$uploads_dir_data = wp_upload_dir();
				
				if ( is_writable( $uploads_dir_data['basedir'] ) )
					$writable_dir = $uploads_dir_data['basedir'];
				else if ( is_writable( $uploads_dir_data['path'] ) )
					$writable_dir = $uploads_dir_data['path'];
				else if ( is_writable( dirname( __FILE__ ) ) )
					$writable_dir = dirname( __FILE__ );
				else if ( is_writable( ABSPATH ) )
					$writable_dir = ABSPATH;
				else if ( true === $args['private'] )
					return new WP_Error( 'no_private_writable_path', 'Unable to find a writable path within the private space' );
				else
					$writable_dir = sys_get_temp_dir();
			}
			
			if ( empty( $writable_dir ) || ! is_dir( $writable_dir ) || ! is_writable( $writable_dir ) )
				return new WP_Error( 'no_writable_path', 'Unable to find a writable path' );
			
			$writable_dir = preg_replace( '|/+$|', '', $writable_dir );
			
			return $writable_dir;
		}
		
		function create_writable_path( $args = array() ) {
			$default_args = array(
				'name'				=> 'temp-deleteme',
				'private'			=> true,
				'possible_paths'	=> array(),
				'permissions'		=> 0700,
			);
			
			$args = array_merge( $default_args, $args );
			
			
			$writable_dir = ITFileUtility::find_writable_path( array( 'private' => $args['private'], 'possible_paths' => $args['possible_paths'] ) );
			
			if ( is_wp_error( $writable_dir ) )
				return $writable_dir;
			
			
			$test_dir_name = $args['name'];
			$count = 0;
			
			while ( is_dir( "$writable_dir/$test_dir_name" ) ) {
				$count++;
				$test_dir_name = "{$args['name']}-$count";
			}
			
			$path = "$writable_dir/$test_dir_name";
			
			if ( false === ITFileUtility::mkdir( $path, $args['permissions'] ) )
				return new WP_Error( 'create_path_failed', 'Unable to create a writable path' );
			if ( ! is_writable( $path ) )
				return new WP_Error( 'create_writable_path_failed', 'Unable to create a writable path' );
			
			return $path;
		}
		
		function create_writable_file( $args ) {
			$default_args = array(
				'name'				=> 'deleteme',
				'extension'			=> '.tmp',
				'private'			=> true,
				'possible_paths'	=> array(),
				'permissions'		=> 0700,
			);
			
			$args = array_merge( $default_args, $args );
			
			
			$writable_dir = ITFileUtility::find_writable_path( array( 'private' => $args['private'], 'possible_paths' => $args['possible_paths'] ) );
			
			if ( is_wp_error( $writable_dir ) )
				return $writable_dir;
			
			
			$test_file_name = "{$args['name']}{$args['extension']}";
			$count = 0;
			
			while ( is_file( "$writable_dir/$test_file_name" ) ) {
				$count++;
				$test_file_name = "{$args['name']}-$count{$args['extension']}";
			}
			
			$file = "$writable_dir/$test_file_name";
			
			if ( false === ITFileUtility::write( $file, '' ) )
				return new WP_Error( 'create_file_failed', 'Unable to create the file' );
			@chmod( $file, $args['permissions'] );
			
			if ( ! is_writable( $file ) )
				return new WP_Error( 'create_writable_file_failed', 'The file was successfully created but cannot be written to' );
			
			return $file;
		}
		
		function get_file_listing( $path ) {
			if ( ! is_dir( $path ) )
				return false;
			
			$files = array_merge( glob( "$path/*" ), glob( "$path/.*" ) );
			$contents = array();
			
			foreach ( (array) $files as $file ) {
				if ( in_array( basename( $file ), array( '.', '..' ) ) )
					continue;
				
				if ( is_dir( $file ) )
					$contents[basename( $file )] = ITFileUtility::get_file_listing( $file );
				else if ( is_file( $file ) )
					$contents[basename( $file )] = true;
			}
			
			return $contents;
		}
		
		function mkdir( $directory, $mode = 0755 ) {
			if ( is_dir( $directory ) )
				return true;
			if ( is_file( $directory ) )
				return false;
			
			if ( ! is_dir( dirname( $directory ) ) ) {
				if ( false === ITFileUtility::mkdir( dirname( $directory ), $mode ) )
					return false;
			}
			
			if ( false === @mkdir( $directory, $mode ) )
				return false;
			
			return true;
		}
		
		function copy( $source, $destination, $args = array() ) {
			$default_args = array(
				'max_depth'		=> 100,
				'folder_mode'	=> 0755,
				'file_mode'		=> 0744,
				'ignore_files'	=> array(),
			);
			$args = array_merge( $default_args, $args );
			
			ITFileUtility::_copy( $source, $destination, $args );
		}
		
		function _copy( $source, $destination, $args, $depth = 0 ) {
			if ( $depth > $args['max_depth'] )
				return true;
			
			if ( is_file( $source ) ) {
				if ( is_dir( $destination ) || preg_match( '|/$|', $destination ) ) {
					$destination = preg_replace( '|/+$|', '', $destination );
					
					$destination = "$destination/" . basename( $source );
				}
				
				if ( false === ITFileUtility::mkdir( dirname( $destination ), $args['folder_mode'] ) )
					return false;
				
				if ( false === @copy( $source, $destination ) )
					return false;
				
				@chmod( $destination, $args['file_mode'] );
				
				return true;
			}
			else if ( is_dir( $source ) || preg_match( '|/\*$|', $source ) ) {
				if ( preg_match( '|/\*$|', $source ) )
					$source = preg_replace( '|/\*$|', '', $source );
				else if ( preg_match( '|/$|', $destination ) )
					$destination = $destination . basename( $source );
				
				$destination = preg_replace( '|/$|', '', $destination );
				
				$files = array_diff( array_merge( glob( $source . '/.*' ), glob( $source . '/*' ) ), array( $source . '/.', $source . '/..' ) );
				
				if ( false === ITFileUtility::mkdir( $destination, $args['folder_mode'] ) )
					return false;
				
				$result = true;
				
				foreach ( (array) $files as $file ) {
					if ( false === ITFileUtility::_copy( $file, "$destination/", $args, $depth + 1 ) )
						$result = false;
				}
				
				return $result;
			}
			
			return false;
		}
		
		function delete_directory( $path ) {
			if ( ! is_dir( $path ) )
				return true;
			
			$files = array_merge( glob( "$path/*" ), glob( "$path/.*" ) );
			$contents = array();
			
			foreach ( (array) $files as $file ) {
				if ( in_array( basename( $file ), array( '.', '..' ) ) )
					continue;
				
				if ( is_dir( $file ) )
					ITFileUtility::delete_directory( $file );
				else if ( is_file( $file ) )
					@unlink( $file );
			}
			
			@rmdir( $path );
			
			if ( ! is_dir( $path ) )
				return true;
			return false;
		}
	}
}

if( ! function_exists( 'mime_content_type' ) ) {
	function mime_content_type( $filename ) {
		$mime_types = array(
			'txt' => 'text/plain',
			'htm' => 'text/html',
			'html' => 'text/html',
			'php' => 'text/html',
			'css' => 'text/css',
			'js' => 'application/javascript',
			'json' => 'application/json',
			'xml' => 'application/xml',
			'swf' => 'application/x-shockwave-flash',
			'flv' => 'video/x-flv',
			'png' => 'image/png',
			'jpe' => 'image/jpeg',
			'jpeg' => 'image/jpeg',
			'jpg' => 'image/jpeg',
			'gif' => 'image/gif',
			'bmp' => 'image/bmp',
			'ico' => 'image/vnd.microsoft.icon',
			'tiff' => 'image/tiff',
			'tif' => 'image/tiff',
			'svg' => 'image/svg+xml',
			'svgz' => 'image/svg+xml',
			'zip' => 'application/zip',
			'rar' => 'application/x-rar-compressed',
			'exe' => 'application/x-msdownload',
			'msi' => 'application/x-msdownload',
			'cab' => 'application/vnd.ms-cab-compressed',
			'mp3' => 'audio/mpeg',
			'qt' => 'video/quicktime',
			'mov' => 'video/quicktime',
			'pdf' => 'application/pdf',
			'psd' => 'image/vnd.adobe.photoshop',
			'ai' => 'application/postscript',
			'eps' => 'application/postscript',
			'ps' => 'application/postscript',
			'doc' => 'application/msword',
			'rtf' => 'application/rtf',
			'xls' => 'application/vnd.ms-excel',
			'ppt' => 'application/vnd.ms-powerpoint',
			'odt' => 'application/vnd.oasis.opendocument.text',
			'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
		);
		
		$ext = strtolower( array_pop( explode( '.', $filename ) ) );
		
		if ( array_key_exists( $ext, $mime_types ) )
			return $mime_types[$ext];
		elseif ( function_exists( 'finfo_open' ) ) {
			$finfo = finfo_open( FILEINFO_MIME );
			$mimetype = finfo_file( $finfo, $filename );
			finfo_close( $finfo );
			return $mimetype;
		}
		else
			return 'application/octet-stream';
	}
}

if ( ! function_exists( 'sys_get_temp_dir' ) ) {
	function sys_get_temp_dir() {
		if ( $temp = getenv('TMP') )
			return $temp;
		if ( $temp = getenv('TEMP') )
			return $temp;
		if ( $temp = getenv('TMPDIR') )
			return $temp;
		
		$temp = tempnam( dirname( __FILE__ ), '' );
		if ( file_exists( $temp ) ) {
			unlink( $temp );
			return dirname( $temp );
		}
		return null;
	}
}
