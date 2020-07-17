<?php
	$rdata = array_map('to_utf8', array_map('nl2br', array_map('html_attr_tags_ok', $rdata)));
	$jdata = array_map('to_utf8', array_map('nl2br', array_map('html_attr_tags_ok', $jdata)));
?>
<script>
	$j(function(){
		var tn = 'membership_cache';

		/* data for selected record, or defaults if none is selected */
		var data = {
		};

		/* initialize or continue using studemy.cache for the current table */
		studemy.cache = studemy.cache || {};
		studemy.cache[tn] = studemy.cache[tn] || studemy.ajaxCache();
		var cache = studemy.cache[tn];

		cache.start();
	});
</script>

