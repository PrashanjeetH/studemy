<?php
	$currDir = dirname(__FILE__);
	require("{$currDir}/incCommon.php");
	$GLOBALS['page_title'] = $Translation['view or rebuild fields'];
	include("{$currDir}/incHeader.php");

	/*
		$schema: [ tablename => [ fieldname => [ studemy => '...', 'db' => '...'], ... ], ... ]
	*/

	/* application schema as created in studemy */
	$schema = array(
		'assessments' => array(
			'assessmentId' => array('studemy' => 'INT(11) unsigned not null primary key auto_increment '),
			'assessmentName' => array('studemy' => 'VARCHAR(40) not null '),
			'instituteNumber' => array('studemy' => 'INT(4) unsigned zerofill null '),
			'courseId' => array('studemy' => 'INT(4) unsigned zerofill null ')
		),
		'courses' => array(
			'courseId' => array('studemy' => 'INT(4) unsigned zerofill not null primary key auto_increment '),
			'instituteNumber' => array('studemy' => 'INT(4) unsigned zerofill null '),
			'courseName' => array('studemy' => 'VARCHAR(100) not null '),
			'courseCode' => array('studemy' => 'VARCHAR(5) null unique '),
			'link' => array('studemy' => 'VARCHAR(255) null '),
			'teacher' => array('studemy' => 'INT(11) null '),
			'subjects' => array('studemy' => 'INT(11) null '),
			'description' => array('studemy' => 'LONGTEXT null '),
			'file' => array('studemy' => 'MEDIUMBLOB null '),
			'amount' => array('studemy' => 'INT(5) null '),
			'dateUploaded' => array('studemy' => 'DATE null '),
			'isActivate' => array('studemy' => 'TINYINT(3) unsigned null default \'0\' '),
			'isApproved' => array('studemy' => 'TINYINT(1) unsigned null default \'0\' ')
		),
		'institutes' => array(
			'instituteNumber' => array('studemy' => 'INT(4) unsigned zerofill not null primary key auto_increment '),
			'instituteName' => array('studemy' => 'VARCHAR(100) not null '),
			'instituteCode' => array('studemy' => 'VARCHAR(6) not null unique '),
			'phone' => array('studemy' => 'BIGINT(15) null '),
			'email' => array('studemy' => 'VARCHAR(100) null '),
			'pincode' => array('studemy' => 'MEDIUMINT(7) null '),
			'city' => array('studemy' => 'VARCHAR(20) null '),
			'state' => array('studemy' => 'VARCHAR(20) null '),
			'ownerName' => array('studemy' => 'VARCHAR(40) null '),
			'ownerPhone' => array('studemy' => 'BIGINT(20) null '),
			'ownerEmail' => array('studemy' => 'VARCHAR(100) null '),
			'adminName' => array('studemy' => 'VARCHAR(40) null '),
			'adminPhone' => array('studemy' => 'BIGINT(20) null '),
			'adminEmail' => array('studemy' => 'VARCHAR(100) null '),
			'subjects' => array('studemy' => 'VARCHAR(1000) null ')
		),
		'modules' => array(
			'moduleId' => array('studemy' => 'INT(11) unsigned not null primary key auto_increment '),
			'instituteNumber' => array('studemy' => 'INT(4) unsigned zerofill null '),
			'courseId' => array('studemy' => 'INT(4) unsigned zerofill null '),
			'assessmentId' => array('studemy' => 'INT(11) unsigned null '),
			'moduleName' => array('studemy' => 'VARCHAR(40) null '),
			'link' => array('studemy' => 'VARCHAR(100) null '),
			'description' => array('studemy' => 'LONGTEXT null '),
			'file' => array('studemy' => 'MEDIUMBLOB null ')
		),
		'questions' => array(
			'id' => array('studemy' => 'INT(11) not null primary key auto_increment '),
			'courseId' => array('studemy' => 'INT(4) unsigned zerofill null '),
			'moduleId' => array('studemy' => 'INT(11) unsigned null '),
			'assessmentId' => array('studemy' => 'INT(11) unsigned null '),
			'question' => array('studemy' => 'VARCHAR(255) null '),
			'option1' => array('studemy' => 'VARCHAR(100) null '),
			'option2' => array('studemy' => 'VARCHAR(100) null '),
			'option3' => array('studemy' => 'VARCHAR(100) null '),
			'option4' => array('studemy' => 'VARCHAR(100) null '),
			'answer' => array('studemy' => 'TINYINT(1) null default \'1\' ')
		),
		'students' => array(
			'id' => array('studemy' => 'INT(11) not null primary key auto_increment '),
			'instituteNumber' => array('studemy' => 'INT(4) unsigned zerofill null '),
			'username' => array('studemy' => 'VARCHAR(20) not null unique '),
			'password' => array('studemy' => 'VARCHAR(255) null '),
			'firstname' => array('studemy' => 'VARCHAR(20) null '),
			'middlename' => array('studemy' => 'VARCHAR(20) null '),
			'lastname' => array('studemy' => 'VARCHAR(20) null '),
			'gender' => array('studemy' => 'VARCHAR(6) null '),
			'email' => array('studemy' => 'VARCHAR(100) null '),
			'phone' => array('studemy' => 'BIGINT(15) unsigned null '),
			'dob' => array('studemy' => 'DATE null '),
			'signupDate' => array('studemy' => 'DATETIME null '),
			'city' => array('studemy' => 'VARCHAR(40) null '),
			'state' => array('studemy' => 'VARCHAR(40) null ')
		),
		'subjects' => array(
			'subjectid' => array('studemy' => 'INT(11) not null primary key auto_increment '),
			'subjectName' => array('studemy' => 'INT(11) null ')
		),
		'teachers' => array(
			'id' => array('studemy' => 'INT(11) not null primary key auto_increment '),
			'firstname' => array('studemy' => 'VARCHAR(40) not null '),
			'middlename' => array('studemy' => 'VARCHAR(20) null '),
			'lastname' => array('studemy' => 'VARCHAR(40) null '),
			'instituteNumber' => array('studemy' => 'INT(4) unsigned zerofill null '),
			'phone' => array('studemy' => 'BIGINT(20) null '),
			'email' => array('studemy' => 'VARCHAR(100) null '),
			'pincode' => array('studemy' => 'INT(7) null '),
			'city' => array('studemy' => 'VARCHAR(40) null '),
			'state' => array('studemy' => 'VARCHAR(40) null '),
			'subjects' => array('studemy' => 'INT(11) null ')
		)
	);

	$table_captions = getTableList();

	/* function for preparing field definition for comparison */
	function prepare_def($def) {
		$def = strtolower($def);

		/* ignore 'null' */
		$def = preg_replace('/\s+not\s+null\s*/', '%%NOT_NULL%%', $def);
		$def = preg_replace('/\s+null\s*/', ' ', $def);
		$def = str_replace('%%NOT_NULL%%', ' not null ', $def);

		/* ignore length for int data types */
		$def = preg_replace('/int\s*\([0-9]+\)/', 'int', $def);

		/* make sure there is always a space before mysql words */
		$def = preg_replace('/(\S)(unsigned|not null|binary|zerofill|auto_increment|default)/', '$1 $2', $def);

		/* treat 0.000.. same as 0 */
		$def = preg_replace('/([0-9])*\.0+/', '$1', $def);

		/* treat unsigned zerofill same as zerofill */
		$def = str_ireplace('unsigned zerofill', 'zerofill', $def);

		/* ignore zero-padding for date data types */
		$def = preg_replace("/date\s*default\s*'([0-9]{4})-0?([1-9])-0?([1-9])'/", "date default '$1-$2-$3'", $def);

		return trim($def);
	}

	/**
	 *  @brief creates/fixes given field according to given schema
	 *  @return integer: 0 = error, 1 = field updated, 2 = field created
	 */
	function fix_field($fix_table, $fix_field, $schema, &$qry) {
		if(!isset($schema[$fix_table][$fix_field])) return 0;

		$def = $schema[$fix_table][$fix_field];
		$field_added = $field_updated = false;
		$eo['silentErrors'] = true;

		// field exists?
		$res = sql("show columns from `{$fix_table}` like '{$fix_field}'", $eo);
		if($row = db_fetch_assoc($res)){
			// modify field
			$qry = "alter table `{$fix_table}` modify `{$fix_field}` {$def['studemy']}";
			sql($qry, $eo);

			// remove unique from db if necessary
			if($row['Key'] == 'UNI' && !stripos($def['studemy'], ' unique')){
				// retrieve unique index name
				$res_unique = sql("show index from `{$fix_table}` where Column_name='{$fix_field}' and Non_unique=0", $eo);
				if($row_unique = db_fetch_assoc($res_unique)){
					$qry_unique = "drop index `{$row_unique['Key_name']}` on `{$fix_table}`";
					sql($qry_unique, $eo);
					$qry .= ";\n{$qry_unique}";
				}
			}

			return 1;
		}

		// missing field is defined as PK and table has another PK field?
		$current_pk = getPKFieldName($fix_table);
		if(stripos($def['studemy'], 'primary key') !== false && $current_pk !== false) {
			// if current PK is not another studemy-defined field, then rename it.
			if(!isset($schema[$fix_table][$current_pk])) {
				// no need to include 'primary key' in definition since it's already a PK field
				$redef = str_ireplace(' primary key', '', $def['studemy']);
				$qry = "alter table `{$fix_table}` change `{$current_pk}` `{$fix_field}` {$redef}";
				sql($qry, $eo);
				return 1;
			}

			// current PK field is another studemy-defined field
			// this happens if table had a PK field in studemy then it was unset as PK
			// and another field was created and set as PK
			// in that case, drop PK index from current PK
			// and also remove auto_increment from it if defined
			// then proceed to creating the missing PK field
			$pk_def = str_ireplace(' auto_increment', '', $schema[$fix_table][$current_pk]);
			sql("alter table `{$fix_table}` modify `{$current_pk}` {$pk_def}", $eo);
		}

		// create field
		$qry = "alter table `{$fix_table}` add column `{$fix_field}` {$def['studemy']}";
		sql($qry, $eo);
		return 2;
	}

	/* process requested fixes */
	$fix_table = (isset($_GET['t']) ? $_GET['t'] : false);
	$fix_field = (isset($_GET['f']) ? $_GET['f'] : false);
	$fix_all = (isset($_GET['all']) ? true : false);

	if($fix_field && $fix_table) $fix_status = fix_field($fix_table, $fix_field, $schema, $qry);

	/* retrieve actual db schema */
	foreach($table_captions as $tn => $tc){
		$eo['silentErrors'] = true;
		$res = sql("show columns from `{$tn}`", $eo);
		if($res){
			while($row = db_fetch_assoc($res)){
				if(!isset($schema[$tn][$row['Field']]['studemy'])) continue;
				$field_description = strtoupper(str_replace(' ', '', $row['Type']));
				$field_description = str_ireplace('unsigned', ' unsigned', $field_description);
				$field_description = str_ireplace('zerofill', ' zerofill', $field_description);
				$field_description = str_ireplace('binary', ' binary', $field_description);
				$field_description .= ($row['Null'] == 'NO' ? ' not null' : '');
				$field_description .= ($row['Key'] == 'PRI' ? ' primary key' : '');
				$field_description .= ($row['Key'] == 'UNI' ? ' unique' : '');
				$field_description .= ($row['Default'] != '' ? " default '" . makeSafe($row['Default']) . "'" : '');
				$field_description .= ($row['Extra'] == 'auto_increment' ? ' auto_increment' : '');

				$schema[$tn][$row['Field']]['db'] = '';
				if(isset($schema[$tn][$row['Field']])){
					$schema[$tn][$row['Field']]['db'] = $field_description;
				}
			}
		}
	}

	/* handle fix_all request */
	if($fix_all){
		foreach($schema as $tn => $fields){
			foreach($fields as $fn => $fd){
				if(prepare_def($fd['studemy']) == prepare_def($fd['db'])) continue;
				fix_field($tn, $fn, $schema, $qry);
			}
		}

		redirect('admin/pageRebuildFields.php');
		exit;
	}
?>

<?php if($fix_status == 1 || $fix_status == 2){ ?>
	<div class="alert alert-info alert-dismissable">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<i class="glyphicon glyphicon-info-sign"></i>
		<?php
			$originalValues = array('<ACTION>', '<FIELD>', '<TABLE>', '<QUERY>');
			$action = ($fix_status == 2 ? 'create' : 'update');
			$replaceValues = array($action, $fix_field, $fix_table, $qry);
			echo str_replace($originalValues, $replaceValues, $Translation['create or update table']);
		?>
	</div>
<?php } ?>

<div class="page-header"><h1>
	<?php echo $Translation['view or rebuild fields'] ; ?>
	<button type="button" class="btn btn-default" id="show_deviations_only"><i class="glyphicon glyphicon-eye-close"></i> <?php echo $Translation['show deviations only'] ; ?></button>
	<button type="button" class="btn btn-default hidden" id="show_all_fields"><i class="glyphicon glyphicon-eye-open"></i> <?php echo $Translation['show all fields'] ; ?></button>
</h1></div>

<p class="lead"><?php echo $Translation['compare tables page'] ; ?></p>

<div class="alert summary"></div>
<table class="table table-responsive table-hover table-striped">
	<thead><tr>
		<th></th>
		<th><?php echo $Translation['field'] ; ?></th>
		<th><?php echo $Translation['studemy definition'] ; ?></th>
		<th><?php echo $Translation['database definition'] ; ?></th>
		<th id="fix_all"></th>
	</tr></thead>

	<tbody>
	<?php foreach($schema as $tn => $fields){ ?>
		<tr class="text-info"><td colspan="5"><h4 data-placement="left" data-toggle="tooltip" title="<?php echo str_replace ( "<TABLENAME>" , $tn , $Translation['table name title']) ; ?>"><i class="glyphicon glyphicon-th-list"></i> <?php echo $table_captions[$tn]; ?></h4></td></tr>
		<?php foreach($fields as $fn => $fd){ ?>
			<?php $diff = ((prepare_def($fd['studemy']) == prepare_def($fd['db'])) ? false : true); ?>
			<?php $no_db = ($fd['db'] ? false : true); ?>
			<tr class="<?php echo ($diff ? 'warning' : 'field_ok'); ?>">
				<td><i class="glyphicon glyphicon-<?php echo ($diff ? 'remove text-danger' : 'ok text-success'); ?>"></i></td>
				<td><?php echo $fn; ?></td>
				<td class="<?php echo ($diff ? 'bold text-success' : ''); ?>"><?php echo $fd['studemy']; ?></td>
				<td class="<?php echo ($diff ? 'bold text-danger' : ''); ?>"><?php echo thisOr($fd['db'], $Translation['does not exist']); ?></td>
				<td>
					<?php if($diff && $no_db){ ?>
						<a href="pageRebuildFields.php?t=<?php echo $tn; ?>&f=<?php echo $fn; ?>" class="btn btn-success btn-xs btn_create" data-toggle="tooltip" data-placement="top" title="<?php echo $Translation['create field'] ; ?>"><i class="glyphicon glyphicon-plus"></i> <?php echo $Translation['create it'] ; ?></a>
					<?php }elseif($diff){ ?>
						<a href="pageRebuildFields.php?t=<?php echo $tn; ?>&f=<?php echo $fn; ?>" class="btn btn-warning btn-xs btn_update" data-toggle="tooltip" title="<?php echo $Translation['fix field'] ; ?>"><i class="glyphicon glyphicon-cog"></i> <?php echo $Translation['fix it'] ; ?></a>
					<?php } ?>
				</td>
			</tr>
		<?php } ?>
	<?php } ?>
	</tbody>
</table>
<div class="alert summary"></div>

<style>
	.bold{ font-weight: bold; }
	[data-toggle="tooltip"]{ display: block !important; }
</style>

<script>
	$j(function(){
		$j('[data-toggle="tooltip"]').tooltip();

		$j('#show_deviations_only').click(function(){
			$j(this).addClass('hidden');
			$j('#show_all_fields').removeClass('hidden');
			$j('.field_ok').hide();
		});

		$j('#show_all_fields').click(function(){
			$j(this).addClass('hidden');
			$j('#show_deviations_only').removeClass('hidden');
			$j('.field_ok').show();
		});

		$j('.btn_update, #fix_all').click(function(){
			return confirm("<?php echo $Translation['field update warning'] ; ?>");
		});

		var count_updates = $j('.btn_update').length;
		var count_creates = $j('.btn_create').length;
		if(!count_creates && !count_updates){
			$j('.summary').addClass('alert-success').html("<?php echo $Translation['no deviations found'] ; ?>");
		}else{
			var fieldsCount = "<?php echo $Translation['error fields']; ?>";
			fieldsCount = fieldsCount.replace(/<CREATENUM>/, count_creates ).replace(/<UPDATENUM>/, count_updates);


			$j('.summary')
				.addClass('alert-warning')
				.html(
					fieldsCount +
					'<br><br>' +
					'<a href="pageBackupRestore.php" class="alert-link">' +
						'<b><?php echo addslashes($Translation['backup before fix']); ?></b>' +
					'</a>'
				);

			$j('<a href="pageRebuildFields.php?all=1" class="btn btn-danger btn-block"><i class="glyphicon glyphicon-cog"></i> <?php echo addslashes($Translation['fix all']); ?></a>').appendTo('#fix_all');
		}
	});
</script>

<?php
	include("{$currDir}/incFooter.php");
?>
