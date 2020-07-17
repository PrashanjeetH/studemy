<?php
	// check this file's MD5 to make sure it wasn't called before
	$prevMD5=@implode('', @file(dirname(__FILE__).'/setup.md5'));
	$thisMD5=md5(@implode('', @file("./updateDB.php")));
	if($thisMD5==$prevMD5){
		$setupAlreadyRun=true;
	}else{
		// set up tables
		if(!isset($silent)){
			$silent=true;
		}

		// set up tables
		setupTable('assessments', "create table if not exists `assessments` (   `assessmentId` INT(11) unsigned not null auto_increment , primary key (`assessmentId`), `assessmentName` VARCHAR(40) not null , `instituteNumber` INT(4) unsigned zerofill null , `courseId` INT(4) unsigned zerofill null ) CHARSET utf8", $silent);
		setupIndexes('assessments', array('instituteNumber','courseId'));
		setupTable('courses', "create table if not exists `courses` (   `courseId` INT(4) unsigned zerofill not null auto_increment , primary key (`courseId`), `instituteNumber` INT(4) unsigned zerofill null , `courseName` VARCHAR(100) not null , `courseCode` VARCHAR(5) null , unique `courseCode_unique` (`courseCode`), `link` VARCHAR(255) null , `teacher` INT(11) null , `subjects` INT(11) null , `description` LONGTEXT null , `file` MEDIUMBLOB null , `amount` INT(5) null , `dateUploaded` DATE null , `isActivate` TINYINT(3) unsigned null default '0' , `isApproved` TINYINT(1) unsigned null default '0' ) CHARSET utf8", $silent, array( " ALTER TABLE `courses` CHANGE `subjects` `subjects` BLOB null "," ALTER TABLE `courses` CHANGE `subjects` `subjects` BLOB null "," ALTER TABLE `courses` CHANGE `subjects` `subjects` BLOB null "," ALTER TABLE `courses` CHANGE `subjects` `subjects` BLOB null "," ALTER TABLE `courses` CHANGE `subjects` `subjects` BLOB null "," ALTER TABLE `courses` CHANGE `subjects` `subjects` BLOB null "," ALTER TABLE `courses` CHANGE `subjects` `subjects` BLOB null "," ALTER TABLE `courses` CHANGE `subjects` `subjects` BLOB null "," ALTER TABLE `courses` CHANGE `subjects` `subjects` BLOB null "," ALTER TABLE `courses` CHANGE `subjects` `subjects` BLOB null "," ALTER TABLE `courses` CHANGE `subjects` `subjects` BLOB null "," ALTER TABLE `courses` CHANGE `subjects` `subjects` BLOB null "));
		setupIndexes('courses', array('instituteNumber','teacher','subjects'));
		setupTable('institutes', "create table if not exists `institutes` (   `instituteNumber` INT(4) unsigned zerofill not null auto_increment , primary key (`instituteNumber`), `instituteName` VARCHAR(100) not null , `instituteCode` VARCHAR(6) not null , unique `instituteCode_unique` (`instituteCode`), `phone` BIGINT(15) null , `email` VARCHAR(100) null , `pincode` MEDIUMINT(7) null , `city` VARCHAR(20) null , `state` VARCHAR(20) null , `ownerName` VARCHAR(40) null , `ownerPhone` BIGINT(20) null , `ownerEmail` VARCHAR(100) null , `adminName` VARCHAR(40) null , `adminPhone` BIGINT(20) null , `adminEmail` VARCHAR(100) null , `subjects` VARCHAR(1000) null ) CHARSET utf8", $silent);
		setupTable('modules', "create table if not exists `modules` (   `moduleId` INT(11) unsigned not null auto_increment , primary key (`moduleId`), `instituteNumber` INT(4) unsigned zerofill null , `courseId` INT(4) unsigned zerofill null , `assessmentId` INT(11) unsigned null , `moduleName` VARCHAR(40) null , `link` VARCHAR(100) null , `description` LONGTEXT null , `file` MEDIUMBLOB null ) CHARSET utf8", $silent);
		setupIndexes('modules', array('instituteNumber','courseId','assessmentId'));
		setupTable('questions', "create table if not exists `questions` (   `id` INT(11) not null auto_increment , primary key (`id`), `courseId` INT(4) unsigned zerofill null , `moduleId` INT(11) unsigned null , `assessmentId` INT(11) unsigned null , `question` VARCHAR(255) null , `option1` VARCHAR(100) null , `option2` VARCHAR(100) null , `option3` VARCHAR(100) null , `option4` VARCHAR(100) null , `answer` TINYINT(1) null default '1' ) CHARSET utf8", $silent);
		setupIndexes('questions', array('courseId','moduleId','assessmentId'));
		setupTable('students', "create table if not exists `students` (   `id` INT(11) not null auto_increment , primary key (`id`), `instituteNumber` INT(4) unsigned zerofill null , `username` VARCHAR(20) not null , unique `username_unique` (`username`), `password` VARCHAR(255) null , `firstname` VARCHAR(20) null , `middlename` VARCHAR(20) null , `lastname` VARCHAR(20) null , `gender` VARCHAR(6) null , `email` VARCHAR(100) null , `phone` BIGINT(15) unsigned null , `dob` DATE null , `signupDate` DATETIME null , `city` VARCHAR(40) null , `state` VARCHAR(40) null ) CHARSET utf8", $silent);
		setupIndexes('students', array('instituteNumber'));
		setupTable('subjects', "create table if not exists `subjects` (   `subjectid` INT(11) not null auto_increment , primary key (`subjectid`), `subjectName` INT(11) null ) CHARSET utf8", $silent);
		setupTable('teachers', "create table if not exists `teachers` (   `id` INT(11) not null auto_increment , primary key (`id`), `firstname` VARCHAR(40) not null , `middlename` VARCHAR(20) null , `lastname` VARCHAR(40) null , `instituteNumber` INT(4) unsigned zerofill null , `phone` BIGINT(20) null , `email` VARCHAR(100) null , `pincode` INT(7) null , `city` VARCHAR(40) null , `state` VARCHAR(40) null , `subjects` INT(11) null ) CHARSET utf8", $silent);
		setupIndexes('teachers', array('instituteNumber','subjects'));


		// save MD5
		if($fp=@fopen(dirname(__FILE__).'/setup.md5', 'w')){
			fwrite($fp, $thisMD5);
			fclose($fp);
		}
	}


	function setupIndexes($tableName, $arrFields){
		if(!is_array($arrFields)){
			return false;
		}

		foreach($arrFields as $fieldName){
			if(!$res=@db_query("SHOW COLUMNS FROM `$tableName` like '$fieldName'")){
				continue;
			}
			if(!$row=@db_fetch_assoc($res)){
				continue;
			}
			if($row['Key']==''){
				@db_query("ALTER TABLE `$tableName` ADD INDEX `$fieldName` (`$fieldName`)");
			}
		}
	}


	function setupTable($tableName, $createSQL='', $silent=true, $arrAlter=''){
		global $Translation;
		ob_start();

		echo '<div style="padding: 5px; border-bottom:solid 1px silver; font-family: verdana, arial; font-size: 10px;">';

		// is there a table rename query?
		if(is_array($arrAlter)){
			$matches=array();
			if(preg_match("/ALTER TABLE `(.*)` RENAME `$tableName`/", $arrAlter[0], $matches)){
				$oldTableName=$matches[1];
			}
		}

		if($res=@db_query("select count(1) from `$tableName`")){ // table already exists
			if($row = @db_fetch_array($res)){
				echo str_replace("<TableName>", $tableName, str_replace("<NumRecords>", $row[0],$Translation["table exists"]));
				if(is_array($arrAlter)){
					echo '<br>';
					foreach($arrAlter as $alter){
						if($alter!=''){
							echo "$alter ... ";
							if(!@db_query($alter)){
								echo '<span class="label label-danger">' . $Translation['failed'] . '</span>';
								echo '<div class="text-danger">' . $Translation['mysql said'] . ' ' . db_error(db_link()) . '</div>';
							}else{
								echo '<span class="label label-success">' . $Translation['ok'] . '</span>';
							}
						}
					}
				}else{
					echo $Translation["table uptodate"];
				}
			}else{
				echo str_replace("<TableName>", $tableName, $Translation["couldnt count"]);
			}
		}else{ // given tableName doesn't exist

			if($oldTableName!=''){ // if we have a table rename query
				if($ro=@db_query("select count(1) from `$oldTableName`")){ // if old table exists, rename it.
					$renameQuery=array_shift($arrAlter); // get and remove rename query

					echo "$renameQuery ... ";
					if(!@db_query($renameQuery)){
						echo '<span class="label label-danger">' . $Translation['failed'] . '</span>';
						echo '<div class="text-danger">' . $Translation['mysql said'] . ' ' . db_error(db_link()) . '</div>';
					}else{
						echo '<span class="label label-success">' . $Translation['ok'] . '</span>';
					}

					if(is_array($arrAlter)) setupTable($tableName, $createSQL, false, $arrAlter); // execute Alter queries on renamed table ...
				}else{ // if old tableName doesn't exist (nor the new one since we're here), then just create the table.
					setupTable($tableName, $createSQL, false); // no Alter queries passed ...
				}
			}else{ // tableName doesn't exist and no rename, so just create the table
				echo str_replace("<TableName>", $tableName, $Translation["creating table"]);
				if(!@db_query($createSQL)){
					echo '<span class="label label-danger">' . $Translation['failed'] . '</span>';
					echo '<div class="text-danger">' . $Translation['mysql said'] . db_error(db_link()) . '</div>';
				}else{
					echo '<span class="label label-success">' . $Translation['ok'] . '</span>';
				}
			}
		}

		echo "</div>";

		$out=ob_get_contents();
		ob_end_clean();
		if(!$silent){
			echo $out;
		}
	}
?>
