<?php
//


	/* Configuration */
	/*************************************/

		$pcConfig = array(
			'assessments' => array(
				'courseId' => array(
					'parent-table' => 'courses',
					'parent-primary-key' => 'courseId',
					'child-primary-key' => 'assessmentId',
					'child-primary-key-index' => 0,
					'tab-label' => 'Assessments <span class="hidden child-label-assessments child-field-caption">(Course)</span>',
					'auto-close' => true,
					'table-icon' => 'table.gif',
					'display-refresh' => true,
					'display-add-new' => true,
					'forced-where' => '',
					'display-fields' => array(1 => 'AssessmentName', 2 => 'Institute', 3 => 'Course'),
					'display-field-names' => array(1 => 'assessmentName', 2 => 'instituteNumber', 3 => 'courseId'),
					'sortable-fields' => array(0 => '`assessments`.`assessmentId`', 1 => 2, 2 => 3, 3 => 4),
					'records-per-page' => 10,
					'default-sort-by' => false,
					'default-sort-direction' => 'asc',
					'open-detail-view-on-click' => true,
					'display-page-selector' => true,
					'show-page-progress' => true,
					'template' => 'children-assessments',
					'template-printable' => 'children-assessments-printable',
					'query' => "SELECT `assessments`.`assessmentId` as 'assessmentId', `assessments`.`assessmentName` as 'assessmentName', IF(    CHAR_LENGTH(`institutes1`.`instituteCode`) || CHAR_LENGTH(`institutes1`.`instituteName`), CONCAT_WS('',   `institutes1`.`instituteCode`, '-', `institutes1`.`instituteName`), '') as 'instituteNumber', IF(    CHAR_LENGTH(`courses1`.`courseCode`) || CHAR_LENGTH(`courses1`.`courseName`), CONCAT_WS('',   `courses1`.`courseCode`, '-', `courses1`.`courseName`), '') as 'courseId' FROM `assessments` LEFT JOIN `institutes` as institutes1 ON `institutes1`.`instituteNumber`=`assessments`.`instituteNumber` LEFT JOIN `courses` as courses1 ON `courses1`.`courseId`=`assessments`.`courseId` "
				)
			),
			'courses' => array(
				'instituteNumber' => array(
					'parent-table' => 'institutes',
					'parent-primary-key' => 'instituteNumber',
					'child-primary-key' => 'courseId',
					'child-primary-key-index' => 0,
					'tab-label' => 'Courses <span class="hidden child-label-courses child-field-caption">(Institute)</span>',
					'auto-close' => true,
					'table-icon' => 'table.gif',
					'display-refresh' => true,
					'display-add-new' => true,
					'forced-where' => '',
					'display-fields' => array(1 => 'Institute', 2 => 'Name', 3 => 'Course Code', 4 => 'Link', 5 => 'Teacher', 6 => 'Subject', 7 => 'Description', 8 => 'File', 9 => 'Amount', 10 => 'DateUpload'),
					'display-field-names' => array(1 => 'instituteNumber', 2 => 'courseName', 3 => 'courseCode', 4 => 'link', 5 => 'teacher', 6 => 'subjects', 7 => 'description', 8 => 'file', 9 => 'amount', 10 => 'dateUploaded'),
					'sortable-fields' => array(0 => '`courses`.`courseId`', 1 => 2, 2 => 3, 3 => 4, 4 => 5, 5 => '`teachers1`.`firstname`', 6 => '`subjects1`.`subjectName`', 7 => 8, 8 => 9, 9 => '`courses`.`amount`', 10 => '`courses`.`dateUploaded`', 11 => '`courses`.`isActivate`', 12 => '`courses`.`isApproved`'),
					'records-per-page' => 10,
					'default-sort-by' => false,
					'default-sort-direction' => 'asc',
					'open-detail-view-on-click' => true,
					'display-page-selector' => true,
					'show-page-progress' => true,
					'template' => 'children-courses',
					'template-printable' => 'children-courses-printable',
					'query' => "SELECT `courses`.`courseId` as 'courseId', IF(    CHAR_LENGTH(`institutes1`.`instituteCode`) || CHAR_LENGTH(`institutes1`.`instituteName`), CONCAT_WS('',   `institutes1`.`instituteCode`, '-', `institutes1`.`instituteName`), '') as 'instituteNumber', `courses`.`courseName` as 'courseName', `courses`.`courseCode` as 'courseCode', if(CHAR_LENGTH(`courses`.`link`)>15, concat(left(`courses`.`link`,15),' ...'), `courses`.`link`) as 'link', IF(    CHAR_LENGTH(`teachers1`.`firstname`), CONCAT_WS('',   `teachers1`.`firstname`), '') as 'teacher', IF(    CHAR_LENGTH(`subjects1`.`subjectName`), CONCAT_WS('',   `subjects1`.`subjectName`), '') as 'subjects', if(CHAR_LENGTH(`courses`.`description`)>50, concat(left(`courses`.`description`,50),' ...'), `courses`.`description`) as 'description', `courses`.`file` as 'file', FORMAT(`courses`.`amount`, 0) as 'amount', if(`courses`.`dateUploaded`,date_format(`courses`.`dateUploaded`,'%d/%m/%Y'),'') as 'dateUploaded', `courses`.`isActivate` as 'isActivate', `courses`.`isApproved` as 'isApproved' FROM `courses` LEFT JOIN `institutes` as institutes1 ON `institutes1`.`instituteNumber`=`courses`.`instituteNumber` LEFT JOIN `teachers` as teachers1 ON `teachers1`.`id`=`courses`.`teacher` LEFT JOIN `subjects` as subjects1 ON `subjects1`.`subjectid`=`courses`.`subjects` "
				),
				'teacher' => array(
					'parent-table' => 'teachers',
					'parent-primary-key' => 'id',
					'child-primary-key' => 'courseId',
					'child-primary-key-index' => 0,
					'tab-label' => 'Courses <span class="hidden child-label-courses child-field-caption">(Teacher)</span>',
					'auto-close' => true,
					'table-icon' => 'table.gif',
					'display-refresh' => true,
					'display-add-new' => true,
					'forced-where' => '',
					'display-fields' => array(1 => 'Institute', 2 => 'Name', 3 => 'Course Code', 4 => 'Link', 5 => 'Teacher', 6 => 'Subject', 7 => 'Description', 8 => 'File', 9 => 'Amount', 10 => 'DateUpload'),
					'display-field-names' => array(1 => 'instituteNumber', 2 => 'courseName', 3 => 'courseCode', 4 => 'link', 5 => 'teacher', 6 => 'subjects', 7 => 'description', 8 => 'file', 9 => 'amount', 10 => 'dateUploaded'),
					'sortable-fields' => array(0 => '`courses`.`courseId`', 1 => 2, 2 => 3, 3 => 4, 4 => 5, 5 => '`teachers1`.`firstname`', 6 => '`subjects1`.`subjectName`', 7 => 8, 8 => 9, 9 => '`courses`.`amount`', 10 => '`courses`.`dateUploaded`', 11 => '`courses`.`isActivate`', 12 => '`courses`.`isApproved`'),
					'records-per-page' => 10,
					'default-sort-by' => false,
					'default-sort-direction' => 'asc',
					'open-detail-view-on-click' => true,
					'display-page-selector' => true,
					'show-page-progress' => true,
					'template' => 'children-courses',
					'template-printable' => 'children-courses-printable',
					'query' => "SELECT `courses`.`courseId` as 'courseId', IF(    CHAR_LENGTH(`institutes1`.`instituteCode`) || CHAR_LENGTH(`institutes1`.`instituteName`), CONCAT_WS('',   `institutes1`.`instituteCode`, '-', `institutes1`.`instituteName`), '') as 'instituteNumber', `courses`.`courseName` as 'courseName', `courses`.`courseCode` as 'courseCode', if(CHAR_LENGTH(`courses`.`link`)>15, concat(left(`courses`.`link`,15),' ...'), `courses`.`link`) as 'link', IF(    CHAR_LENGTH(`teachers1`.`firstname`), CONCAT_WS('',   `teachers1`.`firstname`), '') as 'teacher', IF(    CHAR_LENGTH(`subjects1`.`subjectName`), CONCAT_WS('',   `subjects1`.`subjectName`), '') as 'subjects', if(CHAR_LENGTH(`courses`.`description`)>50, concat(left(`courses`.`description`,50),' ...'), `courses`.`description`) as 'description', `courses`.`file` as 'file', FORMAT(`courses`.`amount`, 0) as 'amount', if(`courses`.`dateUploaded`,date_format(`courses`.`dateUploaded`,'%d/%m/%Y'),'') as 'dateUploaded', `courses`.`isActivate` as 'isActivate', `courses`.`isApproved` as 'isApproved' FROM `courses` LEFT JOIN `institutes` as institutes1 ON `institutes1`.`instituteNumber`=`courses`.`instituteNumber` LEFT JOIN `teachers` as teachers1 ON `teachers1`.`id`=`courses`.`teacher` LEFT JOIN `subjects` as subjects1 ON `subjects1`.`subjectid`=`courses`.`subjects` "
				),
				'subjects' => array(
					'parent-table' => 'subjects',
					'parent-primary-key' => 'subjectid',
					'child-primary-key' => 'courseId',
					'child-primary-key-index' => 0,
					'tab-label' => 'Courses <span class="hidden child-label-courses child-field-caption">(Subject)</span>',
					'auto-close' => true,
					'table-icon' => 'table.gif',
					'display-refresh' => true,
					'display-add-new' => true,
					'forced-where' => '',
					'display-fields' => array(1 => 'Institute', 2 => 'Name', 3 => 'Course Code', 4 => 'Link', 5 => 'Teacher', 6 => 'Subject', 7 => 'Description', 8 => 'File', 9 => 'Amount', 10 => 'DateUpload'),
					'display-field-names' => array(1 => 'instituteNumber', 2 => 'courseName', 3 => 'courseCode', 4 => 'link', 5 => 'teacher', 6 => 'subjects', 7 => 'description', 8 => 'file', 9 => 'amount', 10 => 'dateUploaded'),
					'sortable-fields' => array(0 => '`courses`.`courseId`', 1 => 2, 2 => 3, 3 => 4, 4 => 5, 5 => '`teachers1`.`firstname`', 6 => '`subjects1`.`subjectName`', 7 => 8, 8 => 9, 9 => '`courses`.`amount`', 10 => '`courses`.`dateUploaded`', 11 => '`courses`.`isActivate`', 12 => '`courses`.`isApproved`'),
					'records-per-page' => 10,
					'default-sort-by' => false,
					'default-sort-direction' => 'asc',
					'open-detail-view-on-click' => true,
					'display-page-selector' => true,
					'show-page-progress' => true,
					'template' => 'children-courses',
					'template-printable' => 'children-courses-printable',
					'query' => "SELECT `courses`.`courseId` as 'courseId', IF(    CHAR_LENGTH(`institutes1`.`instituteCode`) || CHAR_LENGTH(`institutes1`.`instituteName`), CONCAT_WS('',   `institutes1`.`instituteCode`, '-', `institutes1`.`instituteName`), '') as 'instituteNumber', `courses`.`courseName` as 'courseName', `courses`.`courseCode` as 'courseCode', if(CHAR_LENGTH(`courses`.`link`)>15, concat(left(`courses`.`link`,15),' ...'), `courses`.`link`) as 'link', IF(    CHAR_LENGTH(`teachers1`.`firstname`), CONCAT_WS('',   `teachers1`.`firstname`), '') as 'teacher', IF(    CHAR_LENGTH(`subjects1`.`subjectName`), CONCAT_WS('',   `subjects1`.`subjectName`), '') as 'subjects', if(CHAR_LENGTH(`courses`.`description`)>50, concat(left(`courses`.`description`,50),' ...'), `courses`.`description`) as 'description', `courses`.`file` as 'file', FORMAT(`courses`.`amount`, 0) as 'amount', if(`courses`.`dateUploaded`,date_format(`courses`.`dateUploaded`,'%d/%m/%Y'),'') as 'dateUploaded', `courses`.`isActivate` as 'isActivate', `courses`.`isApproved` as 'isApproved' FROM `courses` LEFT JOIN `institutes` as institutes1 ON `institutes1`.`instituteNumber`=`courses`.`instituteNumber` LEFT JOIN `teachers` as teachers1 ON `teachers1`.`id`=`courses`.`teacher` LEFT JOIN `subjects` as subjects1 ON `subjects1`.`subjectid`=`courses`.`subjects` "
				)
			),
			'institutes' => array(
			),
			'modules' => array(
				'courseId' => array(
					'parent-table' => 'courses',
					'parent-primary-key' => 'courseId',
					'child-primary-key' => 'moduleId',
					'child-primary-key-index' => 0,
					'tab-label' => 'Modules <span class="hidden child-label-modules child-field-caption">(Course)</span>',
					'auto-close' => true,
					'table-icon' => 'table.gif',
					'display-refresh' => true,
					'display-add-new' => true,
					'forced-where' => '',
					'display-fields' => array(1 => 'Institute', 2 => 'Course', 3 => 'Assessment', 4 => 'Name', 5 => 'Link', 6 => 'Description', 7 => 'File'),
					'display-field-names' => array(1 => 'instituteNumber', 2 => 'courseId', 3 => 'assessmentId', 4 => 'moduleName', 5 => 'link', 6 => 'description', 7 => 'file'),
					'sortable-fields' => array(0 => '`modules`.`moduleId`', 1 => 2, 2 => 3, 3 => '`assessments1`.`assessmentName`', 4 => 5, 5 => 6, 6 => 7, 7 => 8),
					'records-per-page' => 10,
					'default-sort-by' => false,
					'default-sort-direction' => 'asc',
					'open-detail-view-on-click' => true,
					'display-page-selector' => true,
					'show-page-progress' => true,
					'template' => 'children-modules',
					'template-printable' => 'children-modules-printable',
					'query' => "SELECT `modules`.`moduleId` as 'moduleId', IF(    CHAR_LENGTH(`institutes1`.`instituteCode`) || CHAR_LENGTH(`institutes1`.`instituteName`), CONCAT_WS('',   `institutes1`.`instituteCode`, '-', `institutes1`.`instituteName`), '') as 'instituteNumber', IF(    CHAR_LENGTH(`courses1`.`courseCode`) || CHAR_LENGTH(`courses1`.`courseName`), CONCAT_WS('',   `courses1`.`courseCode`, '-', `courses1`.`courseName`), '') as 'courseId', IF(    CHAR_LENGTH(`assessments1`.`assessmentName`), CONCAT_WS('',   `assessments1`.`assessmentName`), '') as 'assessmentId', `modules`.`moduleName` as 'moduleName', `modules`.`link` as 'link', if(CHAR_LENGTH(`modules`.`description`)>50, concat(left(`modules`.`description`,50),' ...'), `modules`.`description`) as 'description', `modules`.`file` as 'file' FROM `modules` LEFT JOIN `institutes` as institutes1 ON `institutes1`.`instituteNumber`=`modules`.`instituteNumber` LEFT JOIN `courses` as courses1 ON `courses1`.`courseId`=`modules`.`courseId` LEFT JOIN `assessments` as assessments1 ON `assessments1`.`assessmentId`=`modules`.`assessmentId` "
				),
				'assessmentId' => array(
					'parent-table' => 'assessments',
					'parent-primary-key' => 'assessmentId',
					'child-primary-key' => 'moduleId',
					'child-primary-key-index' => 0,
					'tab-label' => 'Modules <span class="hidden child-label-modules child-field-caption">(Assessment)</span>',
					'auto-close' => true,
					'table-icon' => 'table.gif',
					'display-refresh' => true,
					'display-add-new' => true,
					'forced-where' => '',
					'display-fields' => array(1 => 'Institute', 2 => 'Course', 3 => 'Assessment', 4 => 'Name', 5 => 'Link', 6 => 'Description', 7 => 'File'),
					'display-field-names' => array(1 => 'instituteNumber', 2 => 'courseId', 3 => 'assessmentId', 4 => 'moduleName', 5 => 'link', 6 => 'description', 7 => 'file'),
					'sortable-fields' => array(0 => '`modules`.`moduleId`', 1 => 2, 2 => 3, 3 => '`assessments1`.`assessmentName`', 4 => 5, 5 => 6, 6 => 7, 7 => 8),
					'records-per-page' => 10,
					'default-sort-by' => false,
					'default-sort-direction' => 'asc',
					'open-detail-view-on-click' => true,
					'display-page-selector' => true,
					'show-page-progress' => true,
					'template' => 'children-modules',
					'template-printable' => 'children-modules-printable',
					'query' => "SELECT `modules`.`moduleId` as 'moduleId', IF(    CHAR_LENGTH(`institutes1`.`instituteCode`) || CHAR_LENGTH(`institutes1`.`instituteName`), CONCAT_WS('',   `institutes1`.`instituteCode`, '-', `institutes1`.`instituteName`), '') as 'instituteNumber', IF(    CHAR_LENGTH(`courses1`.`courseCode`) || CHAR_LENGTH(`courses1`.`courseName`), CONCAT_WS('',   `courses1`.`courseCode`, '-', `courses1`.`courseName`), '') as 'courseId', IF(    CHAR_LENGTH(`assessments1`.`assessmentName`), CONCAT_WS('',   `assessments1`.`assessmentName`), '') as 'assessmentId', `modules`.`moduleName` as 'moduleName', `modules`.`link` as 'link', if(CHAR_LENGTH(`modules`.`description`)>50, concat(left(`modules`.`description`,50),' ...'), `modules`.`description`) as 'description', `modules`.`file` as 'file' FROM `modules` LEFT JOIN `institutes` as institutes1 ON `institutes1`.`instituteNumber`=`modules`.`instituteNumber` LEFT JOIN `courses` as courses1 ON `courses1`.`courseId`=`modules`.`courseId` LEFT JOIN `assessments` as assessments1 ON `assessments1`.`assessmentId`=`modules`.`assessmentId` "
				)
			),
			'questions' => array(
				'moduleId' => array(
					'parent-table' => 'modules',
					'parent-primary-key' => 'moduleId',
					'child-primary-key' => 'id',
					'child-primary-key-index' => 0,
					'tab-label' => 'Questions <span class="hidden child-label-questions child-field-caption">(Module)</span>',
					'auto-close' => true,
					'table-icon' => 'table.gif',
					'display-refresh' => true,
					'display-add-new' => true,
					'forced-where' => '',
					'display-fields' => array(1 => 'Course', 2 => 'Module', 3 => 'Assessment', 4 => 'Question', 5 => 'Option1', 6 => 'Option2', 7 => 'Option3', 8 => 'Option4', 9 => 'Answer'),
					'display-field-names' => array(1 => 'courseId', 2 => 'moduleId', 3 => 'assessmentId', 4 => 'question', 5 => 'option1', 6 => 'option2', 7 => 'option3', 8 => 'option4', 9 => 'answer'),
					'sortable-fields' => array(0 => '`questions`.`id`', 1 => 2, 2 => '`modules1`.`moduleName`', 3 => '`assessments1`.`assessmentName`', 4 => 5, 5 => 6, 6 => 7, 7 => 8, 8 => 9, 9 => 10),
					'records-per-page' => 10,
					'default-sort-by' => false,
					'default-sort-direction' => 'asc',
					'open-detail-view-on-click' => true,
					'display-page-selector' => true,
					'show-page-progress' => true,
					'template' => 'children-questions',
					'template-printable' => 'children-questions-printable',
					'query' => "SELECT `questions`.`id` as 'id', IF(    CHAR_LENGTH(`courses1`.`courseCode`) || CHAR_LENGTH(`courses1`.`courseName`), CONCAT_WS('',   `courses1`.`courseCode`, '-', `courses1`.`courseName`), '') as 'courseId', IF(    CHAR_LENGTH(`modules1`.`moduleName`), CONCAT_WS('',   `modules1`.`moduleName`), '') as 'moduleId', IF(    CHAR_LENGTH(`assessments1`.`assessmentName`), CONCAT_WS('',   `assessments1`.`assessmentName`), '') as 'assessmentId', `questions`.`question` as 'question', `questions`.`option1` as 'option1', `questions`.`option2` as 'option2', `questions`.`option3` as 'option3', `questions`.`option4` as 'option4', `questions`.`answer` as 'answer', FROM `questions` LEFT JOIN `courses` as courses1 ON `courses1`.`courseId`=`questions`.`courseId` LEFT JOIN `modules` as modules1 ON `modules1`.`moduleId`=`questions`.`moduleId` LEFT JOIN `assessments` as assessments1 ON `assessments1`.`assessmentId`=`questions`.`assessmentId` "
				)
			),
			'students' => array(
				'instituteNumber' => array(
					'parent-table' => 'institutes',
					'parent-primary-key' => 'instituteNumber',
					'child-primary-key' => 'id',
					'child-primary-key-index' => 0,
					'tab-label' => 'Students <span class="hidden child-label-students child-field-caption">(Institute)</span>',
					'auto-close' => true,
					'table-icon' => 'table.gif',
					'display-refresh' => true,
					'display-add-new' => true,
					'forced-where' => '',
					'display-fields' => array(1 => 'Institute', 2 => 'Username', 3 => 'Password', 4 => 'Firstname', 5 => 'Middlename', 6 => 'Lastname', 7 => 'Gender', 8 => 'Email', 9 => 'Phone', 10 => 'Dob', 11 => 'SignupDate', 12 => 'City', 13 => 'State'),
					'display-field-names' => array(1 => 'instituteNumber', 2 => 'username', 3 => 'password', 4 => 'firstname', 5 => 'middlename', 6 => 'lastname', 7 => 'gender', 8 => 'email', 9 => 'phone', 10 => 'dob', 11 => 'signupDate', 12 => 'city', 13 => 'state'),
					'sortable-fields' => array(0 => '`students`.`id`', 1 => 2, 2 => 3, 3 => 4, 4 => 5, 5 => 6, 6 => 7, 7 => 8, 8 => 9, 9 => '`students`.`phone`', 10 => '`students`.`dob`', 11 => '`students`.`signupDate`', 12 => 13, 13 => 14),
					'records-per-page' => 10,
					'default-sort-by' => false,
					'default-sort-direction' => 'asc',
					'open-detail-view-on-click' => true,
					'display-page-selector' => true,
					'show-page-progress' => true,
					'template' => 'children-students',
					'template-printable' => 'children-students-printable',
					'query' => "SELECT `students`.`id` as 'id', IF(    CHAR_LENGTH(`institutes1`.`instituteCode`) || CHAR_LENGTH(`institutes1`.`instituteName`), CONCAT_WS('',   `institutes1`.`instituteCode`, '-', `institutes1`.`instituteName`), '') as 'instituteNumber', `students`.`username` as 'username', `students`.`password` as 'password', `students`.`firstname` as 'firstname', `students`.`middlename` as 'middlename', `students`.`lastname` as 'lastname', `students`.`gender` as 'gender', `students`.`email` as 'email', CONCAT_WS('-', LEFT(`students`.`phone`,3), MID(`students`.`phone`,4,3), RIGHT(`students`.`phone`,4)) as 'phone', if(`students`.`dob`,date_format(`students`.`dob`,'%d/%m/%Y'),'') as 'dob', if(`students`.`signupDate`,date_format(`students`.`signupDate`,'%d/%m/%Y %h:%i %p'),'') as 'signupDate', `students`.`city` as 'city', `students`.`state` as 'state' FROM `students` LEFT JOIN `institutes` as institutes1 ON `institutes1`.`instituteNumber`=`students`.`instituteNumber` "
				)
			),
			'subjects' => array(
			),
			'teachers' => array(
				'instituteNumber' => array(
					'parent-table' => 'institutes',
					'parent-primary-key' => 'instituteNumber',
					'child-primary-key' => 'id',
					'child-primary-key-index' => 0,
					'tab-label' => 'Teachers <span class="hidden child-label-teachers child-field-caption">(Institute)</span>',
					'auto-close' => true,
					'table-icon' => 'table.gif',
					'display-refresh' => true,
					'display-add-new' => true,
					'forced-where' => '',
					'display-fields' => array(1 => 'Firstname', 2 => 'Middlename', 3 => 'Lastname', 4 => 'Institute', 5 => 'Phone', 6 => 'Email', 7 => 'Pincode', 8 => 'City', 9 => 'State', 10 => 'Subjects'),
					'display-field-names' => array(1 => 'firstname', 2 => 'middlename', 3 => 'lastname', 4 => 'instituteNumber', 5 => 'phone', 6 => 'email', 7 => 'pincode', 8 => 'city', 9 => 'state', 10 => 'subjects'),
					'sortable-fields' => array(0 => '`teachers`.`id`', 1 => 2, 2 => 3, 3 => 4, 4 => 5, 5 => '`teachers`.`phone`', 6 => 7, 7 => '`teachers`.`pincode`', 8 => 9, 9 => 10, 10 => '`subjects1`.`subjectName`'),
					'records-per-page' => 10,
					'default-sort-by' => false,
					'default-sort-direction' => 'asc',
					'open-detail-view-on-click' => true,
					'display-page-selector' => true,
					'show-page-progress' => true,
					'template' => 'children-teachers',
					'template-printable' => 'children-teachers-printable',
					'query' => "SELECT `teachers`.`id` as 'id', `teachers`.`firstname` as 'firstname', `teachers`.`middlename` as 'middlename', `teachers`.`lastname` as 'lastname', IF(    CHAR_LENGTH(`institutes1`.`instituteCode`) || CHAR_LENGTH(`institutes1`.`instituteName`), CONCAT_WS('',   `institutes1`.`instituteCode`, '-', `institutes1`.`instituteName`), '') as 'instituteNumber', CONCAT_WS('-', LEFT(`teachers`.`phone`,3), MID(`teachers`.`phone`,4,3), RIGHT(`teachers`.`phone`,4)) as 'phone', `teachers`.`email` as 'email', `teachers`.`pincode` as 'pincode', `teachers`.`city` as 'city', `teachers`.`state` as 'state', IF(    CHAR_LENGTH(`subjects1`.`subjectName`), CONCAT_WS('',   `subjects1`.`subjectName`), '') as 'subjects' FROM `teachers` LEFT JOIN `institutes` as institutes1 ON `institutes1`.`instituteNumber`=`teachers`.`instituteNumber` LEFT JOIN `subjects` as subjects1 ON `subjects1`.`subjectid`=`teachers`.`subjects` "
				),
				'subjects' => array(
					'parent-table' => 'subjects',
					'parent-primary-key' => 'subjectid',
					'child-primary-key' => 'id',
					'child-primary-key-index' => 0,
					'tab-label' => 'Teachers <span class="hidden child-label-teachers child-field-caption">(Subjects)</span>',
					'auto-close' => true,
					'table-icon' => 'table.gif',
					'display-refresh' => true,
					'display-add-new' => true,
					'forced-where' => '',
					'display-fields' => array(1 => 'Firstname', 2 => 'Middlename', 3 => 'Lastname', 4 => 'Institute', 5 => 'Phone', 6 => 'Email', 7 => 'Pincode', 8 => 'City', 9 => 'State', 10 => 'Subjects'),
					'display-field-names' => array(1 => 'firstname', 2 => 'middlename', 3 => 'lastname', 4 => 'instituteNumber', 5 => 'phone', 6 => 'email', 7 => 'pincode', 8 => 'city', 9 => 'state', 10 => 'subjects'),
					'sortable-fields' => array(0 => '`teachers`.`id`', 1 => 2, 2 => 3, 3 => 4, 4 => 5, 5 => '`teachers`.`phone`', 6 => 7, 7 => '`teachers`.`pincode`', 8 => 9, 9 => 10, 10 => '`subjects1`.`subjectName`'),
					'records-per-page' => 10,
					'default-sort-by' => false,
					'default-sort-direction' => 'asc',
					'open-detail-view-on-click' => true,
					'display-page-selector' => true,
					'show-page-progress' => true,
					'template' => 'children-teachers',
					'template-printable' => 'children-teachers-printable',
					'query' => "SELECT `teachers`.`id` as 'id', `teachers`.`firstname` as 'firstname', `teachers`.`middlename` as 'middlename', `teachers`.`lastname` as 'lastname', IF(    CHAR_LENGTH(`institutes1`.`instituteCode`) || CHAR_LENGTH(`institutes1`.`instituteName`), CONCAT_WS('',   `institutes1`.`instituteCode`, '-', `institutes1`.`instituteName`), '') as 'instituteNumber', CONCAT_WS('-', LEFT(`teachers`.`phone`,3), MID(`teachers`.`phone`,4,3), RIGHT(`teachers`.`phone`,4)) as 'phone', `teachers`.`email` as 'email', `teachers`.`pincode` as 'pincode', `teachers`.`city` as 'city', `teachers`.`state` as 'state', IF(    CHAR_LENGTH(`subjects1`.`subjectName`), CONCAT_WS('',   `subjects1`.`subjectName`), '') as 'subjects' FROM `teachers` LEFT JOIN `institutes` as institutes1 ON `institutes1`.`instituteNumber`=`teachers`.`instituteNumber` LEFT JOIN `subjects` as subjects1 ON `subjects1`.`subjectid`=`teachers`.`subjects` "
				)
			)
		);

	/*************************************/
	/* End of configuration */


	$currDir = dirname(__FILE__);
	include("{$currDir}/defaultLang.php");
	include("{$currDir}/language.php");
	include("{$currDir}/lib.php");
	@header('Content-Type: text/html; charset=' . datalist_db_encoding);

	handle_maintenance();

	/**
	* dynamic configuration based on current user's permissions
	* $userPCConfig array is populated only with parent tables where the user has access to
	* at least one child table
	*/
	$userPCConfig = array();
	foreach($pcConfig as $pcChildTable => $ChildrenLookups){
		$permChild = getTablePermissions($pcChildTable);
		if($permChild[2]){ // user can view records of the child table, so proceed to check children lookups
			foreach($ChildrenLookups as $ChildLookupField => $ChildConfig){
				$permParent = getTablePermissions($ChildConfig['parent-table']);
				if($permParent[2]){ // user can view records of parent table
					$userPCConfig[$pcChildTable][$ChildLookupField] = $pcConfig[$pcChildTable][$ChildLookupField];
					// show add new only if configured above AND the user has insert permission
					if($permChild[1] && $pcConfig[$pcChildTable][$ChildLookupField]['display-add-new']){
						$userPCConfig[$pcChildTable][$ChildLookupField]['display-add-new'] = true;
					}else{
						$userPCConfig[$pcChildTable][$ChildLookupField]['display-add-new'] = false;
					}
				}
			}
		}
	}

	/* Receive, UTF-convert, and validate parameters */
	$ParentTable = $_REQUEST['ParentTable']; // needed only with operation=show-children, will be validated in the processing code
	$ChildTable = $_REQUEST['ChildTable'];
		if(!in_array($ChildTable, array_keys($userPCConfig))){
			/* defaults to first child table in config array if not provided */
			$ChildTable = current(array_keys($userPCConfig));
		}
		if(!$ChildTable){ die('<!-- No tables accessible to current user -->'); }
	$SelectedID = strip_tags($_REQUEST['SelectedID']);
	$ChildLookupField = $_REQUEST['ChildLookupField'];
		if(!in_array($ChildLookupField, array_keys($userPCConfig[$ChildTable]))){
			/* defaults to first lookup in current child config array if not provided */
			$ChildLookupField = current(array_keys($userPCConfig[$ChildTable]));
		}
	$Page = intval($_REQUEST['Page']);
		if($Page < 1){
			$Page = 1;
		}
	$SortBy = ($_REQUEST['SortBy'] != '' ? abs(intval($_REQUEST['SortBy'])) : false);
		if(!in_array($SortBy, array_keys($userPCConfig[$ChildTable][$ChildLookupField]['sortable-fields']), true)){
			$SortBy = $userPCConfig[$ChildTable][$ChildLookupField]['default-sort-by'];
		}
	$SortDirection = strtolower($_REQUEST['SortDirection']);
		if(!in_array($SortDirection, array('asc', 'desc'))){
			$SortDirection = $userPCConfig[$ChildTable][$ChildLookupField]['default-sort-direction'];
		}
	$Operation = strtolower($_REQUEST['Operation']);
		if(!in_array($Operation, array('get-records', 'show-children', 'get-records-printable', 'show-children-printable'))){
			$Operation = 'get-records';
		}

	/* process requested operation */
	switch($Operation){
		/************************************************/
		case 'show-children':
			/* populate HTML and JS content with children tabs */
			$tabLabels = $tabPanels = $tabLoaders = '';
			foreach($userPCConfig as $ChildTable => $childLookups){
				foreach($childLookups as $ChildLookupField => $childConfig){
					if($childConfig['parent-table'] == $ParentTable){
						$TableIcon = ($childConfig['table-icon'] ? "<img src=\"{$childConfig['table-icon']}\" border=\"0\" />" : '');
						$tabLabels .= sprintf('<li%s><a href="#panel_%s-%s" id="tab_%s-%s" data-toggle="tab">%s%s</a></li>' . "\n\t\t\t\t\t",($tabLabels ? '' : ' class="active"'), $ChildTable, $ChildLookupField, $ChildTable, $ChildLookupField, $TableIcon, $childConfig['tab-label']);
						$tabPanels .= sprintf('<div id="panel_%s-%s" class="tab-pane%s"><img src="loading.gif" align="top" />%s</div>' . "\n\t\t\t\t", $ChildTable, $ChildLookupField, ($tabPanels ? '' : ' active'), $Translation['Loading ...']);
						$tabLoaders .= sprintf('post("parent-children.php", { ChildTable: "%s", ChildLookupField: "%s", SelectedID: "%s", Page: 1, SortBy: "", SortDirection: "", Operation: "get-records" }, "panel_%s-%s");' . "\n\t\t\t\t", $ChildTable, $ChildLookupField, addslashes($SelectedID), $ChildTable, $ChildLookupField);
					}
				}
			}

			if(!$tabLabels){ die('<!-- no children of current parent table are accessible to current user -->'); }
			?>
			<div id="children-tabs">
				<ul class="nav nav-tabs">
					<?php echo $tabLabels; ?>
				</ul>
				<span id="pc-loading"></span>
			</div>
			<div class="tab-content"><?php echo $tabPanels; ?></div>

			<script>
				$j(function(){
					/* for iOS, avoid loading child tabs in modals */
					var iOS = /(iPad|iPhone|iPod)/g.test(navigator.userAgent);
					var embedded = ($j('.navbar').length == 0);
					if(iOS && embedded){
						$j('#children-tabs').next('.tab-content').remove();
						$j('#children-tabs').remove();
						return;
					}

					/* ajax loading of each tab's contents */
					<?php echo $tabLoaders; ?>

					/* show child field caption on tab title in case the same child table appears more than once */
					$j('.child-field-caption').each(function() {
						var clss = $j(this).attr('class').split(/\s+/).reduce(function(rc, cc) {
							return (cc.match(/child-label-.*/) ? '.' + cc : rc);
						}, '');

						// if class occurs more than once, remove .hidden
						if($j(clss).length > 1) $j(clss).removeClass('hidden');
					})
				})
			</script>
			<?php
			break;

		/************************************************/
		case 'show-children-printable':
			/* populate HTML and JS content with children buttons */
			$tabLabels = $tabPanels = $tabLoaders = '';
			foreach($userPCConfig as $ChildTable => $childLookups){
				foreach($childLookups as $ChildLookupField => $childConfig){
					if($childConfig['parent-table'] == $ParentTable){
						$TableIcon = ($childConfig['table-icon'] ? "<img src=\"{$childConfig['table-icon']}\" border=\"0\" />" : '');
						$tabLabels .= sprintf('<button type="button" class="btn btn-default" data-target="#panel_%s-%s" id="tab_%s-%s" data-toggle="collapse">%s %s</button>' . "\n\t\t\t\t\t", $ChildTable, $ChildLookupField, $ChildTable, $ChildLookupField, $TableIcon, $childConfig['tab-label']);
						$tabPanels .= sprintf('<div id="panel_%s-%s" class="collapse"><img src="loading.gif" align="top" />%s</div>' . "\n\t\t\t\t", $ChildTable, $ChildLookupField, $Translation['Loading ...']);
						$tabLoaders .= sprintf('post("parent-children.php", { ChildTable: "%s", ChildLookupField: "%s", SelectedID: "%s", Page: 1, SortBy: "", SortDirection: "", Operation: "get-records-printable" }, "panel_%s-%s");' . "\n\t\t\t\t", $ChildTable, $ChildLookupField, addslashes($SelectedID), $ChildTable, $ChildLookupField);
					}
				}
			}

			if(!$tabLabels){ die('<!-- no children of current parent table are accessible to current user -->'); }
			?>
			<div id="children-tabs" class="hidden-print">
				<div class="btn-group btn-group-lg">
					<?php echo $tabLabels; ?>
				</div>
				<span id="pc-loading"></span>
			</div>
			<div class="vspacer-lg"><?php echo $tabPanels; ?></div>

			<script>
				$j(function(){
					/* for iOS, avoid loading child tabs in modals */
					var iOS = /(iPad|iPhone|iPod)/g.test(navigator.userAgent);
					var embedded = ($j('.navbar').length == 0);
					if(iOS && embedded){
						$j('#children-tabs').next('.tab-content').remove();
						$j('#children-tabs').remove();
						return;
					}

					/* ajax loading of each tab's contents */
					<?php echo $tabLoaders; ?>
				})
			</script>
			<?php
			break;

		/************************************************/
		case 'get-records-printable':
		default: /* default is 'get-records' */

			if($Operation == 'get-records-printable'){
				$userPCConfig[$ChildTable][$ChildLookupField]['records-per-page'] = 2000;
			}

			// build the user permissions limiter
			$permissionsWhere = $permissionsJoin = '';
			$permChild = getTablePermissions($ChildTable);
			if($permChild[2] == 1){ // user can view only his own records
				$permissionsWhere = "`$ChildTable`.`{$userPCConfig[$ChildTable][$ChildLookupField]['child-primary-key']}`=`membership_userrecords`.`pkValue` AND `membership_userrecords`.`tableName`='$ChildTable' AND LCASE(`membership_userrecords`.`memberID`)='".getLoggedMemberID()."'";
			}elseif($permChild[2] == 2){ // user can view only his group's records
				$permissionsWhere = "`$ChildTable`.`{$userPCConfig[$ChildTable][$ChildLookupField]['child-primary-key']}`=`membership_userrecords`.`pkValue` AND `membership_userrecords`.`tableName`='$ChildTable' AND `membership_userrecords`.`groupID`='".getLoggedGroupID()."'";
			}elseif($permChild[2] == 3){ // user can view all records
				/* that's the only case remaining ... no need to modify the query in this case */
			}
			$permissionsJoin = ($permissionsWhere ? ", `membership_userrecords`" : '');

			// build the count query
			$forcedWhere = $userPCConfig[$ChildTable][$ChildLookupField]['forced-where'];
			$query =
				preg_replace('/^select .* from /i', 'SELECT count(1) FROM ', $userPCConfig[$ChildTable][$ChildLookupField]['query']) .
				$permissionsJoin . " WHERE " .
				($permissionsWhere ? "( $permissionsWhere )" : "( 1=1 )") . " AND " .
				($forcedWhere ? "( $forcedWhere )" : "( 2=2 )") . " AND " .
				"`$ChildTable`.`$ChildLookupField`='" . makeSafe($SelectedID) . "'";
			$totalMatches = sqlValue($query);

			// make sure $Page is <= max pages
			$maxPage = ceil($totalMatches / $userPCConfig[$ChildTable][$ChildLookupField]['records-per-page']);
			if($Page > $maxPage){ $Page = $maxPage; }

			// initiate output data array
			$data = array(
				'config' => $userPCConfig[$ChildTable][$ChildLookupField],
				'parameters' => array(
					'ChildTable' => $ChildTable,
					'ChildLookupField' => $ChildLookupField,
					'SelectedID' => $SelectedID,
					'Page' => $Page,
					'SortBy' => $SortBy,
					'SortDirection' => $SortDirection,
					'Operation' => $Operation
				),
				'records' => array(),
				'totalMatches' => $totalMatches
			);

			// build the data query
			if($totalMatches){ // if we have at least one record, proceed with fetching data
				$startRecord = $userPCConfig[$ChildTable][$ChildLookupField]['records-per-page'] * ($Page - 1);
				$data['query'] =
					$userPCConfig[$ChildTable][$ChildLookupField]['query'] .
					$permissionsJoin . " WHERE " .
					($permissionsWhere ? "( $permissionsWhere )" : "( 1=1 )") . " AND " .
					($forcedWhere ? "( $forcedWhere )" : "( 2=2 )") . " AND " .
					"`$ChildTable`.`$ChildLookupField`='" . makeSafe($SelectedID) . "'" .
					($SortBy !== false && $userPCConfig[$ChildTable][$ChildLookupField]['sortable-fields'][$SortBy] ? " ORDER BY {$userPCConfig[$ChildTable][$ChildLookupField]['sortable-fields'][$SortBy]} $SortDirection" : '') .
					" LIMIT $startRecord, {$userPCConfig[$ChildTable][$ChildLookupField]['records-per-page']}";
				$res = sql($data['query'], $eo);
				while($row = db_fetch_row($res)){
					$data['records'][$row[$userPCConfig[$ChildTable][$ChildLookupField]['child-primary-key-index']]] = $row;
				}
			}else{ // if no matching records
				$startRecord = 0;
			}

			if($Operation == 'get-records-printable'){
				$response = loadView($userPCConfig[$ChildTable][$ChildLookupField]['template-printable'], $data);
			}else{
				$response = loadView($userPCConfig[$ChildTable][$ChildLookupField]['template'], $data);
			}

			// change name space to ensure uniqueness
			$uniqueNameSpace = $ChildTable.ucfirst($ChildLookupField).'GetRecords';
			echo str_replace("{$ChildTable}GetChildrenRecordsList", $uniqueNameSpace, $response);
		/************************************************/
	}
