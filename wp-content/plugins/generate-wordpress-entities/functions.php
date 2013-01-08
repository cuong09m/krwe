<?php
/**
 * 'random' for random name generation
 * -1 for random count and parent
 */
function gwe_generate_posts($name='random', $count=-1, $comments=-1, $cats = array(-1), $thumb = -1, $custom_keys = array(), $custom_gens = array(), $custom_vals = array()) {
	$curids = gwe_get_category_ids();
	
	get_categories(array(
		'hide_empty' => false,
		'exclude' => 1,
	));
	
	if($count==-1) $count = rand(20,40);	
	
	for($i = 1; $i <= $count; $i++) {
		if($comments==-1) {
			$comments = rand(0,1000);
			if($comments > 500) $comments = 2;
			else if($comments > 250) $comments = 1;
			else $comments = 0;
		}
		$gencomments = ($comments == 2);
		$comment_status = ($comments == 0) ? 'closed' : 'open';
		if($thumb==-1) {
			if(gwe_gauss(1,10) > 5) $thumb = 1;
			else $thumb = 0;
		}
		$genthumb = ($thumb == 1);
			
		$catids = array();
		$rndcats = false;
		foreach($cats as $cat) {
			$catid = intval($cat);
			if($catid == 0) continue;
			if($catid == -1) {
				$rndcats = true;
				break;
			}
			$catids[] = $catid;
		}
		
		if($rndcats == true) {			
			$keys = array();
			if(count($curids) > 2) {
				$howmuch = rand(2, min(5, count($curids)-1) );
				$keys = array_rand($curids, $howmuch);
			}
			else {
				$keys = array_rand($curids);
			}
					
			$catids = array();
			if($keys) {
				foreach($keys as $k) {
					$catids[] = $curids[$k];
				}
			}
		}
	
		$postname = ($name == 'random') ? Lorem::Title() : $name . " $i";
		
		$posttags = array();
		$tc = rand(0,5);
		for($x = 0; $x < $tc; $x++) {
			$ti = trim(Lorem::Ipsum(1, 'txt', false), "\t\r\n\n .");
			$posttags[] = $ti;
		}
		
		try {
			$html = Lorem::Html();
			if(gwe_gauss(1,10) > 5) { // insert image
				$upload_dir = wp_upload_dir();
				$img = $upload_dir['path'] . '/' . uniqid('imggen_') . '.jpg';

				$width = rand(intval(get_option('thumbnail_size_w', 150)), intval(get_option('medium_size_w', 300)));
				$height = rand(intval(get_option('thumbnail_size_h', 150)), intval(get_option('medium_size_h', 300)));

				$im = imagecreate($width, $height);
				for ($n = 0; $n < 4; $n++) {
					$color = imagecolorallocate($im, rand(0, 255), rand(0, 255), rand(0, 255));
					$x = $width/2 * ($n % 2);
					$y = $height/2 * (int) ($n >= 2);
					imagefilledrectangle($im, $x, $y, $x + $width/2, $y + $height/2, $color);
				}

				// Make a perfect circle in the image middle.
				$color = imagecolorallocate($im, rand(0, 255), rand(0, 255), rand(0, 255));
				$smaller_dimension = min($width, $height);
				imageellipse($im, $width/2, $height/2, $smaller_dimension, $smaller_dimension, $color);

				imagejpeg($im, $img);
				@chmod($img, 0666);
				
				$align = (gwe_gauss(1,10) > 5) ? 'left' : 'right';
				$imgname = pathinfo($img, PATHINFO_FILENAME);
				
				$html = '<a href="' . $upload_dir['url'] . '/' . $imgname . '.jpg"><img class="align' . $align . ' size-medium" title="' . $imgname . '" src="' . $upload_dir['url'] . '/' . $imgname . '.jpg" alt="" /></a>' . $html;
			}
			
			
			$postid = wp_insert_post(array(
				'post_title' => $postname,
				'post_content' => $html,
				'post_status' => 'publish',
				'post_author' => 1,
				'post_type' => 'post',
				'comment_status' => $comment_status,
				'post_excerpt' => Lorem::Sentence(),
				'tags_input' => $posttags,
				'post_category' => $catids
			));
			
			foreach($custom_keys as $index => $key) {
				if(!empty($key)) {				
					$val = '';
					$gen = isset($custom_gens[$index]) ? $custom_gens[$index] : '';
					if(!empty($gen)) {
						switch($gen) {
							case 'Custom': $val = $custom_vals[$index]; break;
							case 'Word': $val = Lorem::Word(); break;
							case 'Sentence': $val = Lorem::Sentence(); break;
							case 'Email': $val = Lorem::Email(); break;
							case 'Number_1_5': $val = rand(1, 5); break;
							case 'Number_0_100': $val = rand(0, 100); break;
						}					
						if(!empty($val)) {					
							update_post_meta($postid, $key, $val);
						}
					}
				}
			}
			if($genthumb) gwt_generate_thumb($postid);
			if($gencomments) gwe_generate_comments($postid);
		}
		catch(Exception $e) {}
	}
}

/**
 * 'random' for random name generation
 * -1 for random count and parent
 */
function gwe_generate_pages($name='random', $count=-1, $parent=-1, $comments=-1) {
	if($count==-1) $count = rand(3,10);
	if($comments==-1) {
		$comments = rand(0,1000);
		if($comments > 500) $comments = 2;
		else if($comments > 250) $comments = 1;
		else $comments = 0;
	}
	
	$gencomments = ($comments == 2);
	$comment_status = ($comments == 0) ? 'closed' : 'open';
		
	
	$ids = gwe_get_page_ids();
	$ids[] = 0;
	
	for($i = 1; $i <= $count; $i++) {
		$pagename = ($name == 'random') ? Lorem::Title() : $name . " $i";
				
		$pageparent = $parent;		
		if($pageparent == -1) {
			if(gwe_gauss(1,10) > 5) {
				$pageparent = 0;
			}
			else {
				$pageparent = $ids[array_rand($ids)];
			}			
			$pageparent = $ids[array_rand($ids)];
		}
		
		try {
			$pageid = wp_insert_post(array(
				'post_title' => $pagename,
				'post_content' => Lorem::Html(),
				'post_status' => 'publish',
				'post_author' => 1,
				'post_type' => 'page',
				'comment_status' => $comment_status,
				'post_parent' => $pageparent
			));
			$ids[] = $pageid;
			
			if($gencomments) gwe_generate_comments($pageid);
		}
		catch(Exception $e) {}
	}
}

/**
 * 'random' for random name generation
 * -1 for random count and parent
 */
function gwe_generate_categories($name='random', $count=-1, $parent=-1) {
	if($count==-1) $count = rand(3,10);
	
	$ids = gwe_get_category_ids();
	$ids[] = 0;
	
	for($i = 1; $i <= $count; $i++) {
		$catname = ($name == 'random') ? Lorem::Title() : $name . " $i";
		
		$catparent = $parent;		
		if($catparent == -1) {
			if(gwe_gauss(1,10) > 5) $catparent = 0;
			else $catparent = $ids[array_rand($ids)];
		}
		
		try {
			$catid = wp_create_category($catname, $catparent);
			$ids[] = $catid;
		}
		catch(Exception $e) {}
	}
}

function gwe_generate_comments($postid) {
	$ctime = current_time('mysql');
	$howmutch = rand(1,10);
	
	for($x = 0; $x < $howmutch; $x++) {
		wp_insert_comment(array(
			'comment_post_ID' => $postid,
			'comment_author' => ucfirst(Lorem::Word()),
			'comment_author_email' => Lorem::Email(),
			'comment_author_url' => 'http://',
			'comment_content' => Lorem::Sentence(),
			'comment_type' => '',
			'comment_parent' => 0,
			'user_id' => 1,
			'comment_author_IP' => '127.0.0.1',
			'comment_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)',
			'comment_date' => $ctime,
			'comment_approved' => 1
		));
	}
}

function gwt_generate_thumb($postid) {
	$upload_dir = wp_upload_dir();
	
	$img = $upload_dir['path'] . '/' . uniqid('imggen_') . '.jpg';

	$width = rand(max(intval(get_option('thumbnail_size_w', 150)), 150), max(intval(get_option('large_size_w', 1024)), 1024));
	$height = rand(max(intval(get_option('thumbnail_size_h', 150)), 150), max(intval(get_option('large_size_h', 1024)), 1024));

	$im = imagecreate($width, $height);
	for ($n = 0; $n < 4; $n++) {
		$color = imagecolorallocate($im, rand(0, 255), rand(0, 255), rand(0, 255));
		$x = $width/2 * ($n % 2);
		$y = $height/2 * (int) ($n >= 2);
		imagefilledrectangle($im, $x, $y, $x + $width/2, $y + $height/2, $color);
	}

	// Make a perfect circle in the image middle.
	$color = imagecolorallocate($im, rand(0, 255), rand(0, 255), rand(0, 255));
	$smaller_dimension = min($width, $height);
	imageellipse($im, $width/2, $height/2, $smaller_dimension, $smaller_dimension, $color);

	imagejpeg($im, $img);
	@chmod($img, 0666);

	$wp_filetype = wp_check_filetype($img, null);
	$attachment = array(
		'post_mime_type' => $wp_filetype['type'],
		'post_title' => pathinfo($img, PATHINFO_FILENAME),
		'post_name' => pathinfo($img, PATHINFO_FILENAME),
		'post_content' => '',
		'post_parent' => $postid,
		'post_excerpt' => '',
		'post_status' => 'inherit'
	);
	$attachment_id = wp_insert_attachment($attachment, $img, $postid);
	if($attachment_id != 0) {
		$attachment_data = wp_generate_attachment_metadata($attachment_id, $img);
		wp_update_attachment_metadata($attachment_id, $attachment_data);
		update_post_meta($postid, '_thumbnail_id', $attachment_id);
	}
}

function gwe_delete_categories() {
	$ids = gwe_get_category_ids();
	
	foreach($ids as $id) {
		wp_delete_category($id);
	}	
}

function gwe_delete_pages() {
	$ids = gwe_get_page_ids();
	
	foreach($ids as $id) {
		wp_delete_post($id, true);
	}	
}

function gwe_delete_posts() {
	$ids = gwe_get_post_ids();
	foreach($ids as $id) {
		wp_delete_post($id, true);
	}	
}

function gwe_get_page_ids() {
	$ids = array();
	
	$pages = get_posts(array(
		'numberposts' => -1,
		'post_type' => 'page'
	));
	
	foreach($pages as $page) {
		$ids[] = $page->ID;
	}

	return $ids;
}

function gwe_get_post_ids() {
	$ids = array();
	
	$posts = get_posts(array(
		'numberposts' => -1
	));
	
	foreach($posts as $post) {
		$ids[] = $post->ID;
	}

	return $ids;
}

function gwe_get_category_ids() {
	$ids = array();
	
	$categories = get_categories(array(
		'hide_empty' => false,
		'exclude' => 1,
	));
	
	
	foreach($categories as $category) {
		$ids[] = $category->cat_ID;
	}
	
	return $ids;
}

function gwe_dropdown_count() {
	$count_opts = '<select id="gwe_count" name="gwe_count">';
	$count_opts .= '<option value="-1">random</option>';
	for($i = 0; $i <= 100; $i++) {		
		$count_opts .= '<option value="' . $i . '">' . $i . '</option>';
	}
	$count_opts .= '</select>';
	echo $count_opts;
}

function gwe_dropdown_page() {
	wp_dropdown_pages(array('name' => 'gwe_page_parent'));
	echo '<script type="text/javascript">jQuery(document).ready(function($) {
				$("#gwe_page_parent").html("<option value=\"-1\">random</option><option value=\"0\">None</option>"+$("#gwe_page_parent").html());
		});</script>';
}

function gwe_dropdown_category() {
	wp_dropdown_categories(array('hide_empty' => 0, 'hide_if_empty' => false, 'taxonomy' => 'category', 'name' => 'gwe_cat_parent', 'orderby' => 'name', 'hierarchical' => true));
	echo '<script type="text/javascript">jQuery(document).ready(function($) {
				$("#gwe_cat_parent").html("<option value=\"-1\">random</option><option value=\"0\">None</option>"+$("#gwe_cat_parent").html());
		});</script>';
}

function gwe_gauss($min = 0, $max = 1) {
	$mean = ($max - $min) / 2;
	$std_dev = 1;
	
	$x=(float)rand()/(float)getrandmax();
    $y=(float)rand()/(float)getrandmax();
        
	$u=sqrt(-2*log($x))*cos(2*pi()*$y);
	
	return $u*$std_dev+$mean;
}



///////////////////////////////////////////////////////////
class Lorem{
	private static $instance = NULL;
	private function __construct() {}
	private function __clone(){}

	public static function getInstance() {
		if (!self::$instance)
		{
			self::$instance = new LoremIpsumGenerator();
		}
		return self::$instance;
	}
	
	public static function Ipsum($count = 100, $format = 'html', $loremipsum = true) {
		return self::getInstance()->getContent($count, $format, $loremipsum);
	}
	
	public static function Word() {
		return trim(self::Ipsum(1, 'txt', false), "\t\r\n\n .");
	}
	
	public static function Sentence() {
		return ucfirst(trim(self::Ipsum(rand(4,10), 'txt', false), "\t\r\n\n "));
	}
	
	public static function Email() {
		return self::Word() . '@' . self::Word() . '.com';
	}
	
	public static function Title() {
		return ucfirst(trim(self::Ipsum(rand(1,3), 'txt', false), "\t\r\n\n ."));
	}
	
	public static function Html() {
		$tags = array('a', 'b', 'i', 'u');
		$html = array();
		foreach($tags as $tag) {
			$attr = '';
			if($tag == 'a') {
				$attr = trim(self::Ipsum(1, 'txt', false), "\t\r\n\n .");
				$attr = ' href="http://' . $attr . '.com" ';
			}	
			$w = trim(self::Ipsum(rand(1,3), 'txt', false), "\t\r\n\n .");
			$p = trim(self::Ipsum(1, 'txt', false), "\t\r\n\n .");
			$s = trim(self::Ipsum(1, 'txt', false), "\t\r\n\n .");
			$html[] = sprintf(" %s <$tag$attr>%s</$tag> %s ", $w, $p, $s);
			foreach($tags as $tag2) {
				if($tag == $tag2) continue;
				$attr2 = '';
				if($tag2 == 'a') {
					$attr2 = trim(self::Ipsum(1, 'txt', false), "\t\r\n\n .");
					$attr2 = ' href="http://' . $attr2 . '.com" ';
				}
				$w = trim(self::Ipsum(rand(1,3), 'txt', false), "\t\r\n\n .");
				$html[] = sprintf(" %s <$tag$attr><$tag2$attr2>%s</$tag2></$tag> %s ", $w, $p, $s);
			}
		}

		for($i = 0; $i < 15; $i++)
			$html[] = sprintf(" %s ", trim(self::Ipsum(rand(5, 20), 'txt', false), "\t\r\n\n ."));

		$paragraphs = array();
		$paragraphs_count = rand(5,10);
		for($i = 0; $i < $paragraphs_count; $i++) {
			$part1 = ucfirst(trim($html[array_rand($html)], "\t\r\n\n ."));
			$part2 = trim($html[array_rand($html)], "\t\r\n\n .");
			$paragraphs[] = sprintf("<p>%s%s</p>", $part1, $part2);
		}

		for($i = 2; $i <= 3; $i++) {
			$txt = ucfirst(trim(self::Ipsum(rand(2, 6), 'txt', false), "\t\r\n\n ."));
			$paragraphs[] = sprintf("<h$i>%s</h$i>", $txt);
		}

		if(rand(1,100) < 50) {
			$ul = '<ul>';
			$ul_count = rand(3, 10);
			for($i = 0; $i < $ul_count; $i++) {
				$txt = ucfirst(trim(self::Ipsum(rand(2, 6), 'txt', false), "\t\r\n\n ."));
				$ul = $ul . sprintf("<li>%s</li>", $txt);
			}
			$ul = $ul . '</ul>';
			$paragraphs[] = $ul;
		}

		if(rand(1,100) > 50) {
			$ol = '<ol>';
			$ol_count = rand(3, 10);
			for($i = 0; $i < $ol_count; $i++) {
				$txt = ucfirst(trim(self::Ipsum(rand(2, 6), 'txt', false), "\t\r\n\n ."));
				$ol = $ol . sprintf("<li>%s</li>", $txt);
			}
			$ol = $ol . '</ol>';
			$paragraphs[] = $ol;
		}

		shuffle($paragraphs);
		$html = '';
		foreach($paragraphs as $p) {
			$html = $html . $p;
		}
		
		return $html;
	}
}


class LoremIpsumGenerator {
	/**
	*	Copyright (c) 2009, Mathew Tinsley (tinsley@tinsology.net)
	*	All rights reserved.
	*
	*	Redistribution and use in source and binary forms, with or without
	*	modification, are permitted provided that the following conditions are met:
	*		* Redistributions of source code must retain the above copyright
	*		  notice, this list of conditions and the following disclaimer.
	*		* Redistributions in binary form must reproduce the above copyright
	*		  notice, this list of conditions and the following disclaimer in the
	*		  documentation and/or other materials provided with the distribution.
	*		* Neither the name of the organization nor the
	*		  names of its contributors may be used to endorse or promote products
	*		  derived from this software without specific prior written permission.
	*
	*	THIS SOFTWARE IS PROVIDED BY MATHEW TINSLEY ''AS IS'' AND ANY
	*	EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
	*	WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
	*	DISCLAIMED. IN NO EVENT SHALL <copyright holder> BE LIABLE FOR ANY
	*	DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
	*	(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
	*	LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
	*	ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
	*	(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
	*	SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
	*/
	
	private $words, $wordsPerParagraph, $wordsPerSentence;
	
	function __construct($wordsPer = 100)
	{
		$this->wordsPerParagraph = $wordsPer;
		$this->wordsPerSentence = 24.460;
		$this->words = array(
		'lorem',
		'ipsum',
		'dolor',
		'sit',
		'amet',
		'consectetur',
		'adipiscing',
		'elit',
		'curabitur',
		'vel',
		'hendrerit',
		'libero',
		'eleifend',
		'blandit',
		'nunc',
		'ornare',
		'odio',
		'ut',
		'orci',
		'gravida',
		'imperdiet',
		'nullam',
		'purus',
		'lacinia',
		'a',
		'pretium',
		'quis',
		'congue',
		'praesent',
		'sagittis',
		'laoreet',
		'auctor',
		'mauris',
		'non',
		'velit',
		'eros',
		'dictum',
		'proin',
		'accumsan',
		'sapien',
		'nec',
		'massa',
		'volutpat',
		'venenatis',
		'sed',
		'eu',
		'molestie',
		'lacus',
		'quisque',
		'porttitor',
		'ligula',
		'dui',
		'mollis',
		'tempus',
		'at',
		'magna',
		'vestibulum',
		'turpis',
		'ac',
		'diam',
		'tincidunt',
		'id',
		'condimentum',
		'enim',
		'sodales',
		'in',
		'hac',
		'habitasse',
		'platea',
		'dictumst',
		'aenean',
		'neque',
		'fusce',
		'augue',
		'leo',
		'eget',
		'semper',
		'mattis',
		'tortor',
		'scelerisque',
		'nulla',
		'interdum',
		'tellus',
		'malesuada',
		'rhoncus',
		'porta',
		'sem',
		'aliquet',
		'et',
		'nam',
		'suspendisse',
		'potenti',
		'vivamus',
		'luctus',
		'fringilla',
		'erat',
		'donec',
		'justo',
		'vehicula',
		'ultricies',
		'varius',
		'ante',
		'primis',
		'faucibus',
		'ultrices',
		'posuere',
		'cubilia',
		'curae',
		'etiam',
		'cursus',
		'aliquam',
		'quam',
		'dapibus',
		'nisl',
		'feugiat',
		'egestas',
		'class',
		'aptent',
		'taciti',
		'sociosqu',
		'ad',
		'litora',
		'torquent',
		'per',
		'conubia',
		'nostra',
		'inceptos',
		'himenaeos',
		'phasellus',
		'nibh',
		'pulvinar',
		'vitae',
		'urna',
		'iaculis',
		'lobortis',
		'nisi',
		'viverra',
		'arcu',
		'morbi',
		'pellentesque',
		'metus',
		'commodo',
		'ut',
		'facilisis',
		'felis',
		'tristique',
		'ullamcorper',
		'placerat',
		'aenean',
		'convallis',
		'sollicitudin',
		'integer',
		'rutrum',
		'duis',
		'est',
		'etiam',
		'bibendum',
		'donec',
		'pharetra',
		'vulputate',
		'maecenas',
		'mi',
		'fermentum',
		'consequat',
		'suscipit',
		'aliquam',
		'habitant',
		'senectus',
		'netus',
		'fames',
		'quisque',
		'euismod',
		'curabitur',
		'lectus',
		'elementum',
		'tempor',
		'risus',
		'cras' );
	}
		
	function getContent($count, $format = 'html', $loremipsum = true)
	{
		$format = strtolower($format);
		
		if($count <= 0)
			return '';

		switch($format)
		{
			case 'txt':
				return $this->getText($count, $loremipsum);
			case 'plain':
				return $this->getPlain($count, $loremipsum);
			default:
				return $this->getHTML($count, $loremipsum);
		}
	}
	
	private function getWords(&$arr, $count, $loremipsum)
	{
		$i = 0;
		if($loremipsum)
		{
			$i = 2;
			$arr[0] = 'lorem';
			$arr[1] = 'ipsum';
		}
		
		for($i; $i < $count; $i++)
		{
			$index = array_rand($this->words);
			$word = $this->words[$index];
			//echo $index . '=>' . $word . '<br />';
			
			if($i > 0 && $arr[$i - 1] == $word)
				$i--;
			else
				$arr[$i] = $word;
		}
	}
	
	private function getPlain($count, $loremipsum, $returnStr = true)
	{
		$words = array();
		$this->getWords($words, $count, $loremipsum);
		//print_r($words);
		
		$delta = $count;
		$curr = 0;
		$sentences = array();
		while($delta > 0)
		{
			$senSize = $this->gaussianSentence();
			//echo $curr . '<br />';
			if(($delta - $senSize) < 4)
				$senSize = $delta;

			$delta -= $senSize;
			
			$sentence = array();
			for($i = $curr; $i < ($curr + $senSize); $i++)
				$sentence[] = $words[$i];

			$this->punctuate($sentence);
			$curr = $curr + $senSize;
			$sentences[] = $sentence;
		}
		
		if($returnStr)
		{
			$output = '';
			foreach($sentences as $s)
				foreach($s as $w)
					$output .= $w . ' ';
					
			return $output;
		}
		else
			return $sentences;
	}
	
	private function getText($count, $loremipsum)
	{
		$sentences = $this->getPlain($count, $loremipsum, false);
		$paragraphs = $this->getParagraphArr($sentences);
		
		$paragraphStr = array();
		foreach($paragraphs as $p)
		{
			$paragraphStr[] = $this->paragraphToString($p);
		}
		
		$paragraphStr[0] = "\t" . $paragraphStr[0];
		return implode("\n\n\t", $paragraphStr);
	}
	
	private function getParagraphArr($sentences)
	{
		$wordsPer = $this->wordsPerParagraph;
		$sentenceAvg = $this->wordsPerSentence;
		$total = count($sentences);
		
		$paragraphs = array();
		$pCount = 0;
		$currCount = 0;
		$curr = array();
		
		for($i = 0; $i < $total; $i++)
		{
			$s = $sentences[$i];
			$currCount += count($s);
			$curr[] = $s;
			if($currCount >= ($wordsPer - round($sentenceAvg / 2.00)) || $i == $total - 1)
			{
				$currCount = 0;
				$paragraphs[] = $curr;
				$curr = array();
				//print_r($paragraphs);
			}
			//print_r($paragraphs);
		}
		
		return $paragraphs;
	}
	
	private function getHTML($count, $loremipsum)
	{
		$sentences = $this->getPlain($count, $loremipsum, false);
		$paragraphs = $this->getParagraphArr($sentences);
		//print_r($paragraphs);
		
		$paragraphStr = array();
		foreach($paragraphs as $p)
		{
			$paragraphStr[] = "<p>\n" . $this->paragraphToString($p, true) . '</p>';
		}
		
		//add new lines for the sake of clean code
		return implode("\n", $paragraphStr);
	}
	
	private function paragraphToString($paragraph, $htmlCleanCode = false)
	{
		$paragraphStr = '';
		foreach($paragraph as $sentence)
		{
			foreach($sentence as $word)
				$paragraphStr .= $word . ' ';
				
			if($htmlCleanCode)
				$paragraphStr .= "\n";
		}		
		return $paragraphStr;
	}
	
	/*
	* Inserts commas and periods in the given
	* word array.
	*/
	private function punctuate(& $sentence)
	{
		$count = count($sentence);
		$sentence[$count - 1] = $sentence[$count - 1] . '.';
		
		if($count < 4)
			return $sentence;
		
		$commas = $this->numberOfCommas($count);
		
		for($i = 1; $i <= $commas; $i++)
		{
			$index = (int) round($i * $count / ($commas + 1));
			
			if($index < ($count - 1) && $index > 0)
			{
				$sentence[$index] = $sentence[$index] . ',';
			}
		}
	}
	
	/*
	* Determines the number of commas for a
	* sentence of the given length. Average and
	* standard deviation are determined superficially
	*/
	private function numberOfCommas($len)
	{
		$avg = (float) log($len, 6);
		$stdDev = (float) $avg / 6.000;
		
		return (int) round($this->gauss_ms($avg, $stdDev));
	}
	
	/*
	* Returns a number on a gaussian distribution
	* based on the average word length of an english
	* sentence.
	* Statistics Source:
	*	http://hearle.nahoo.net/Academic/Maths/Sentence.html
	*	Average: 24.46
	*	Standard Deviation: 5.08
	*/
	private function gaussianSentence()
	{
		$avg = (float) 24.460;
		$stdDev = (float) 5.080;
		
		return (int) round($this->gauss_ms($avg, $stdDev));
	}
	
	/*
	* The following three functions are used to
	* compute numbers with a guassian distrobution
	* Source:
	* 	http://us.php.net/manual/en/function.rand.php#53784
	*/
	private function gauss()
	{   // N(0,1)
		// returns random number with normal distribution:
		//   mean=0
		//   std dev=1
		
		// auxilary vars
		$x=$this->random_0_1();
		$y=$this->random_0_1();
		
		// two independent variables with normal distribution N(0,1)
		$u=sqrt(-2*log($x))*cos(2*pi()*$y);
		$v=sqrt(-2*log($x))*sin(2*pi()*$y);
		
		// i will return only one, couse only one needed
		return $u;
	}

	private function gauss_ms($m=0.0,$s=1.0)
	{
		return $this->gauss()*$s+$m;
	}

	private function random_0_1()
	{
		return (float)rand()/(float)getrandmax();
	}

}