<?php
	$rdata = array_map('to_utf8', array_map('nl2br', array_map('html_attr_tags_ok', $rdata)));
	$jdata = array_map('to_utf8', array_map('nl2br', array_map('html_attr_tags_ok', $jdata)));
?>
<script>
	$j(function(){
		var tn = 'questions';

		/* data for selected record, or defaults if none is selected */
		var data = {
			courseId: <?php echo json_encode(array('id' => $rdata['courseId'], 'value' => $rdata['courseId'], 'text' => $jdata['courseId'])); ?>,
			moduleId: <?php echo json_encode(array('id' => $rdata['moduleId'], 'value' => $rdata['moduleId'], 'text' => $jdata['moduleId'])); ?>,
			assessmentId: <?php echo json_encode(array('id' => $rdata['assessmentId'], 'value' => $rdata['assessmentId'], 'text' => $jdata['assessmentId'])); ?>
		};

		/* initialize or continue using studemy.cache for the current table */
		studemy.cache = studemy.cache || {};
		studemy.cache[tn] = studemy.cache[tn] || studemy.ajaxCache();
		var cache = studemy.cache[tn];

		/* saved value for courseId */
		cache.addCheck(function(u, d){
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'courseId' && d.id == data.courseId.id)
				return { results: [ data.courseId ], more: false, elapsed: 0.01 };
			return false;
		});

		/* saved value for moduleId */
		cache.addCheck(function(u, d){
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'moduleId' && d.id == data.moduleId.id)
				return { results: [ data.moduleId ], more: false, elapsed: 0.01 };
			return false;
		});

		/* saved value for assessmentId */
		cache.addCheck(function(u, d){
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'assessmentId' && d.id == data.assessmentId.id)
				return { results: [ data.assessmentId ], more: false, elapsed: 0.01 };
			return false;
		});

		cache.start();
	});
</script>

