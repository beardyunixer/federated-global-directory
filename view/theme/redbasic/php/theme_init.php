<?php

head_add_css('library/font_awesome/css/font-awesome.min.css');
head_add_css('library/bootstrap/css/bootstrap.min.css');
head_add_css('library/bootstrap-tagsinput/bootstrap-tagsinput.css');
head_add_css('view/theme/redbasic/css/bootstrap-red.css');
head_add_css('library/datetimepicker/jquery.datetimepicker.css');
//head_add_css('library/colorpicker/css/colorpicker.css');
head_add_css('library/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css');
require_once('include/plugin.php');

head_add_css('library/tiptip/tipTip.css');
head_add_css('library/jgrowl/jquery.jgrowl.css');
head_add_css('library/jRange/jquery.range.css');

head_add_css('view/theme/redbasic/css/conversation.css');
head_add_css('view/theme/redbasic/css/widgets.css');
head_add_css('view/theme/redbasic/css/colorbox.css');
head_add_css('library/justifiedGallery/justifiedGallery.min.css');

head_add_js('jquery.js');
//head_add_js('jquery-migrate-1.1.1.js');
head_add_js('library/justifiedGallery/jquery.justifiedGallery.min.js');
head_add_js('library/sprintf.js/dist/sprintf.min.js');

//head_add_js('jquery-compat.js');
head_add_js('spin.js');
head_add_js('jquery.spin.js');
head_add_js('jquery.textinputs.js');
head_add_js('autocomplete.js');
head_add_js('library/jquery-textcomplete/jquery.textcomplete.js');
//head_add_js('library/colorbox/jquery.colorbox.js');
head_add_js('library/jquery.timeago.js');
head_add_js('library/readmore.js/readmore.js');
//head_add_js('library/jquery_ac/friendica.complete.js');
//head_add_js('library/tiptip/jquery.tipTip.minified.js');
head_add_js('library/jgrowl/jquery.jgrowl_minimized.js');
//head_add_js('library/tinymce/jscripts/tiny_mce/tiny_mce.js');
head_add_js('library/cryptojs/components/core-min.js');
head_add_js('library/cryptojs/rollups/aes.js');
head_add_js('library/cryptojs/rollups/rabbit.js');
head_add_js('library/cryptojs/rollups/tripledes.js');
//head_add_js('library/stylish_select/jquery.stylish-select.js');
head_add_js('acl.js');
head_add_js('webtoolkit.base64.js');
head_add_js('main.js');
head_add_js('crypto.js');
head_add_js('library/jRange/jquery.range.js');
//head_add_js('docready.js');
head_add_js('library/colorbox/jquery.colorbox-min.js');

head_add_js('library/jquery.AreYouSure/jquery.are-you-sure.js');
head_add_js('library/tableofcontents/jquery.toc.js');

head_add_js('library/bootstrap/js/bootstrap.min.js');
head_add_js('library/bootbox/bootbox.min.js');
head_add_js('library/bootstrap-tagsinput/bootstrap-tagsinput.js');
head_add_js('library/datetimepicker/jquery.datetimepicker.js');
//head_add_js('library/colorpicker/js/colorpicker.js');
head_add_js('library/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.js');
//head_add_js('library/bootstrap-colorpicker/src/js/docs.js');


// FIXME This seems like a terrible idea, can we get rid of it?
// Only about three people still use RedMatrix, and none of them
// have updated yet, can we just kill this?
$channel = get_app()->get_channel();
if($channel && file_exists($channel['channel_address'] . '.js'))
	head_add_js('/' . $channel['channel_address'] . '.js');
