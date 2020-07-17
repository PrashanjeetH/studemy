<?php
	$rdata = array_map('to_utf8', array_map('nl2br', array_map('html_attr_tags_ok', $rdata)));
	$jdata = array_map('to_utf8', array_map('nl2br', array_map('html_attr_tags_ok', $jdata)));
?>
<script>
	$j(function(){
		var tn = 'courses';

		/* data for selected record, or defaults if none is selected */
		var data = {
			instituteNumber: <?php echo json_encode(array('id' => $rdata['instituteNumber'], 'value' => $rdata['instituteNumber'], 'text' => $jdata['instituteNumber'])); ?>,
			teacher: <?php echo json_encode(array('id' => $rdata['teacher'], 'value' => $rdata['teacher'], 'text' => $jdata['teacher'])); ?>,
			subjects: <?php echo json_encode(array('id' => $rdata['subjects'], 'value' => $rdata['subjects'], 'text' => $jdata['subjects'])); ?>
		};

		/* initialize or continue using studemy.cache for the current table */
		studemy.cache = studemy.cache || {};
		studemy.cache[tn] = studemy.cache[tn] || studemy.ajaxCache();
		var cache = studemy.cache[tn];

		/* saved value for instituteNumber */
		cache.addCheck(function(u, d){
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'instituteNumber' && d.id == data.instituteNumber.id)
				return { results: [ data.instituteNumber ], more: false, elapsed: 0.01 };
			return false;
		});

		/* saved value for teacher */
		cache.addCheck(function(u, d){
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'teacher' && d.id == data.teacher.id)
				return { results: [ data.teacher ], more: false, elapsed: 0.01 };
			return false;
		});

		cache.start();
	});
</script>

