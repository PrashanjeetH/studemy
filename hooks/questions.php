<?php
	// For help on using hooks, please refer to https://bigprof.com/studemy/help/working-with-generated-web-database-application/hooks

	function questions_init(&$options, $memberInfo, &$args){

		return TRUE;
	}

	function questions_header($contentType, $memberInfo, &$args){
		$header='';

		switch($contentType){
			case 'tableview':
				$header='';
				break;

			case 'detailview':
				$header='';
				break;

			case 'tableview+detailview':
				$header='';
				break;

			case 'print-tableview':
				$header='';
				break;

			case 'print-detailview':
				$header='';
				break;

			case 'filters':
				$header='';
				break;
		}

		return $header;
	}

	function questions_footer($contentType, $memberInfo, &$args){
		$footer='';

		switch($contentType){
			case 'tableview':
				$footer='';
				break;

			case 'detailview':
				$footer='';
				break;

			case 'tableview+detailview':
				$footer='';
				break;

			case 'print-tableview':
				$footer='';
				break;

			case 'print-detailview':
				$footer='';
				break;

			case 'filters':
				$footer='';
				break;
		}

		return $footer;
	}

	function questions_before_insert(&$data, $memberInfo, &$args){

		return TRUE;
	}

	function questions_after_insert($data, $memberInfo, &$args){

		return TRUE;
	}

	function questions_before_update(&$data, $memberInfo, &$args){

		return TRUE;
	}

	function questions_after_update($data, $memberInfo, &$args){

		return TRUE;
	}

	function questions_before_delete($selectedID, &$skipChecks, $memberInfo, &$args){

		return TRUE;
	}

	function questions_after_delete($selectedID, $memberInfo, &$args){

	}

	function questions_dv($selectedID, $memberInfo, &$html, &$args){

	}

	function questions_csv($query, $memberInfo, &$args){

		return $query;
	}
	function questions_batch_actions(&$args){

		return array();
	}
