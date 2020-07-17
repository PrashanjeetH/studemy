var FiltersEnabled = 0; // if your not going to use transitions or filters in any of the tips set this to 0
var spacer="&nbsp; &nbsp; &nbsp; ";

// email notifications to admin
notifyAdminNewMembers0Tip=["", spacer+"No email notifications to admin."];
notifyAdminNewMembers1Tip=["", spacer+"Notify admin only when a new member is waiting for approval."];
notifyAdminNewMembers2Tip=["", spacer+"Notify admin for all new sign-ups."];

// visitorSignup
visitorSignup0Tip=["", spacer+"If this option is selected, visitors will not be able to join this group unless the admin manually moves them to this group from the admin area."];
visitorSignup1Tip=["", spacer+"If this option is selected, visitors can join this group but will not be able to sign in unless the admin approves them from the admin area."];
visitorSignup2Tip=["", spacer+"If this option is selected, visitors can join this group and will be able to sign in instantly with no need for admin approval."];

// assessments table
assessments_addTip=["",spacer+"This option allows all members of the group to add records to the 'Assessments' table. A member who adds a record to the table becomes the 'owner' of that record."];

assessments_view0Tip=["",spacer+"This option prohibits all members of the group from viewing any record in the 'Assessments' table."];
assessments_view1Tip=["",spacer+"This option allows each member of the group to view only his own records in the 'Assessments' table."];
assessments_view2Tip=["",spacer+"This option allows each member of the group to view any record owned by any member of the group in the 'Assessments' table."];
assessments_view3Tip=["",spacer+"This option allows each member of the group to view all records in the 'Assessments' table."];

assessments_edit0Tip=["",spacer+"This option prohibits all members of the group from modifying any record in the 'Assessments' table."];
assessments_edit1Tip=["",spacer+"This option allows each member of the group to edit only his own records in the 'Assessments' table."];
assessments_edit2Tip=["",spacer+"This option allows each member of the group to edit any record owned by any member of the group in the 'Assessments' table."];
assessments_edit3Tip=["",spacer+"This option allows each member of the group to edit any records in the 'Assessments' table, regardless of their owner."];

assessments_delete0Tip=["",spacer+"This option prohibits all members of the group from deleting any record in the 'Assessments' table."];
assessments_delete1Tip=["",spacer+"This option allows each member of the group to delete only his own records in the 'Assessments' table."];
assessments_delete2Tip=["",spacer+"This option allows each member of the group to delete any record owned by any member of the group in the 'Assessments' table."];
assessments_delete3Tip=["",spacer+"This option allows each member of the group to delete any records in the 'Assessments' table."];

// courses table
courses_addTip=["",spacer+"This option allows all members of the group to add records to the 'Courses' table. A member who adds a record to the table becomes the 'owner' of that record."];

courses_view0Tip=["",spacer+"This option prohibits all members of the group from viewing any record in the 'Courses' table."];
courses_view1Tip=["",spacer+"This option allows each member of the group to view only his own records in the 'Courses' table."];
courses_view2Tip=["",spacer+"This option allows each member of the group to view any record owned by any member of the group in the 'Courses' table."];
courses_view3Tip=["",spacer+"This option allows each member of the group to view all records in the 'Courses' table."];

courses_edit0Tip=["",spacer+"This option prohibits all members of the group from modifying any record in the 'Courses' table."];
courses_edit1Tip=["",spacer+"This option allows each member of the group to edit only his own records in the 'Courses' table."];
courses_edit2Tip=["",spacer+"This option allows each member of the group to edit any record owned by any member of the group in the 'Courses' table."];
courses_edit3Tip=["",spacer+"This option allows each member of the group to edit any records in the 'Courses' table, regardless of their owner."];

courses_delete0Tip=["",spacer+"This option prohibits all members of the group from deleting any record in the 'Courses' table."];
courses_delete1Tip=["",spacer+"This option allows each member of the group to delete only his own records in the 'Courses' table."];
courses_delete2Tip=["",spacer+"This option allows each member of the group to delete any record owned by any member of the group in the 'Courses' table."];
courses_delete3Tip=["",spacer+"This option allows each member of the group to delete any records in the 'Courses' table."];

// institutes table
institutes_addTip=["",spacer+"This option allows all members of the group to add records to the 'Institutes' table. A member who adds a record to the table becomes the 'owner' of that record."];

institutes_view0Tip=["",spacer+"This option prohibits all members of the group from viewing any record in the 'Institutes' table."];
institutes_view1Tip=["",spacer+"This option allows each member of the group to view only his own records in the 'Institutes' table."];
institutes_view2Tip=["",spacer+"This option allows each member of the group to view any record owned by any member of the group in the 'Institutes' table."];
institutes_view3Tip=["",spacer+"This option allows each member of the group to view all records in the 'Institutes' table."];

institutes_edit0Tip=["",spacer+"This option prohibits all members of the group from modifying any record in the 'Institutes' table."];
institutes_edit1Tip=["",spacer+"This option allows each member of the group to edit only his own records in the 'Institutes' table."];
institutes_edit2Tip=["",spacer+"This option allows each member of the group to edit any record owned by any member of the group in the 'Institutes' table."];
institutes_edit3Tip=["",spacer+"This option allows each member of the group to edit any records in the 'Institutes' table, regardless of their owner."];

institutes_delete0Tip=["",spacer+"This option prohibits all members of the group from deleting any record in the 'Institutes' table."];
institutes_delete1Tip=["",spacer+"This option allows each member of the group to delete only his own records in the 'Institutes' table."];
institutes_delete2Tip=["",spacer+"This option allows each member of the group to delete any record owned by any member of the group in the 'Institutes' table."];
institutes_delete3Tip=["",spacer+"This option allows each member of the group to delete any records in the 'Institutes' table."];

// modules table
modules_addTip=["",spacer+"This option allows all members of the group to add records to the 'Modules' table. A member who adds a record to the table becomes the 'owner' of that record."];

modules_view0Tip=["",spacer+"This option prohibits all members of the group from viewing any record in the 'Modules' table."];
modules_view1Tip=["",spacer+"This option allows each member of the group to view only his own records in the 'Modules' table."];
modules_view2Tip=["",spacer+"This option allows each member of the group to view any record owned by any member of the group in the 'Modules' table."];
modules_view3Tip=["",spacer+"This option allows each member of the group to view all records in the 'Modules' table."];

modules_edit0Tip=["",spacer+"This option prohibits all members of the group from modifying any record in the 'Modules' table."];
modules_edit1Tip=["",spacer+"This option allows each member of the group to edit only his own records in the 'Modules' table."];
modules_edit2Tip=["",spacer+"This option allows each member of the group to edit any record owned by any member of the group in the 'Modules' table."];
modules_edit3Tip=["",spacer+"This option allows each member of the group to edit any records in the 'Modules' table, regardless of their owner."];

modules_delete0Tip=["",spacer+"This option prohibits all members of the group from deleting any record in the 'Modules' table."];
modules_delete1Tip=["",spacer+"This option allows each member of the group to delete only his own records in the 'Modules' table."];
modules_delete2Tip=["",spacer+"This option allows each member of the group to delete any record owned by any member of the group in the 'Modules' table."];
modules_delete3Tip=["",spacer+"This option allows each member of the group to delete any records in the 'Modules' table."];

// questions table
questions_addTip=["",spacer+"This option allows all members of the group to add records to the 'Questions' table. A member who adds a record to the table becomes the 'owner' of that record."];

questions_view0Tip=["",spacer+"This option prohibits all members of the group from viewing any record in the 'Questions' table."];
questions_view1Tip=["",spacer+"This option allows each member of the group to view only his own records in the 'Questions' table."];
questions_view2Tip=["",spacer+"This option allows each member of the group to view any record owned by any member of the group in the 'Questions' table."];
questions_view3Tip=["",spacer+"This option allows each member of the group to view all records in the 'Questions' table."];

questions_edit0Tip=["",spacer+"This option prohibits all members of the group from modifying any record in the 'Questions' table."];
questions_edit1Tip=["",spacer+"This option allows each member of the group to edit only his own records in the 'Questions' table."];
questions_edit2Tip=["",spacer+"This option allows each member of the group to edit any record owned by any member of the group in the 'Questions' table."];
questions_edit3Tip=["",spacer+"This option allows each member of the group to edit any records in the 'Questions' table, regardless of their owner."];

questions_delete0Tip=["",spacer+"This option prohibits all members of the group from deleting any record in the 'Questions' table."];
questions_delete1Tip=["",spacer+"This option allows each member of the group to delete only his own records in the 'Questions' table."];
questions_delete2Tip=["",spacer+"This option allows each member of the group to delete any record owned by any member of the group in the 'Questions' table."];
questions_delete3Tip=["",spacer+"This option allows each member of the group to delete any records in the 'Questions' table."];

// students table
students_addTip=["",spacer+"This option allows all members of the group to add records to the 'Students' table. A member who adds a record to the table becomes the 'owner' of that record."];

students_view0Tip=["",spacer+"This option prohibits all members of the group from viewing any record in the 'Students' table."];
students_view1Tip=["",spacer+"This option allows each member of the group to view only his own records in the 'Students' table."];
students_view2Tip=["",spacer+"This option allows each member of the group to view any record owned by any member of the group in the 'Students' table."];
students_view3Tip=["",spacer+"This option allows each member of the group to view all records in the 'Students' table."];

students_edit0Tip=["",spacer+"This option prohibits all members of the group from modifying any record in the 'Students' table."];
students_edit1Tip=["",spacer+"This option allows each member of the group to edit only his own records in the 'Students' table."];
students_edit2Tip=["",spacer+"This option allows each member of the group to edit any record owned by any member of the group in the 'Students' table."];
students_edit3Tip=["",spacer+"This option allows each member of the group to edit any records in the 'Students' table, regardless of their owner."];

students_delete0Tip=["",spacer+"This option prohibits all members of the group from deleting any record in the 'Students' table."];
students_delete1Tip=["",spacer+"This option allows each member of the group to delete only his own records in the 'Students' table."];
students_delete2Tip=["",spacer+"This option allows each member of the group to delete any record owned by any member of the group in the 'Students' table."];
students_delete3Tip=["",spacer+"This option allows each member of the group to delete any records in the 'Students' table."];

// subjects table
subjects_addTip=["",spacer+"This option allows all members of the group to add records to the 'Subject' table. A member who adds a record to the table becomes the 'owner' of that record."];

subjects_view0Tip=["",spacer+"This option prohibits all members of the group from viewing any record in the 'Subject' table."];
subjects_view1Tip=["",spacer+"This option allows each member of the group to view only his own records in the 'Subject' table."];
subjects_view2Tip=["",spacer+"This option allows each member of the group to view any record owned by any member of the group in the 'Subject' table."];
subjects_view3Tip=["",spacer+"This option allows each member of the group to view all records in the 'Subject' table."];

subjects_edit0Tip=["",spacer+"This option prohibits all members of the group from modifying any record in the 'Subject' table."];
subjects_edit1Tip=["",spacer+"This option allows each member of the group to edit only his own records in the 'Subject' table."];
subjects_edit2Tip=["",spacer+"This option allows each member of the group to edit any record owned by any member of the group in the 'Subject' table."];
subjects_edit3Tip=["",spacer+"This option allows each member of the group to edit any records in the 'Subject' table, regardless of their owner."];

subjects_delete0Tip=["",spacer+"This option prohibits all members of the group from deleting any record in the 'Subject' table."];
subjects_delete1Tip=["",spacer+"This option allows each member of the group to delete only his own records in the 'Subject' table."];
subjects_delete2Tip=["",spacer+"This option allows each member of the group to delete any record owned by any member of the group in the 'Subject' table."];
subjects_delete3Tip=["",spacer+"This option allows each member of the group to delete any records in the 'Subject' table."];

// teachers table
teachers_addTip=["",spacer+"This option allows all members of the group to add records to the 'Teachers' table. A member who adds a record to the table becomes the 'owner' of that record."];

teachers_view0Tip=["",spacer+"This option prohibits all members of the group from viewing any record in the 'Teachers' table."];
teachers_view1Tip=["",spacer+"This option allows each member of the group to view only his own records in the 'Teachers' table."];
teachers_view2Tip=["",spacer+"This option allows each member of the group to view any record owned by any member of the group in the 'Teachers' table."];
teachers_view3Tip=["",spacer+"This option allows each member of the group to view all records in the 'Teachers' table."];

teachers_edit0Tip=["",spacer+"This option prohibits all members of the group from modifying any record in the 'Teachers' table."];
teachers_edit1Tip=["",spacer+"This option allows each member of the group to edit only his own records in the 'Teachers' table."];
teachers_edit2Tip=["",spacer+"This option allows each member of the group to edit any record owned by any member of the group in the 'Teachers' table."];
teachers_edit3Tip=["",spacer+"This option allows each member of the group to edit any records in the 'Teachers' table, regardless of their owner."];

teachers_delete0Tip=["",spacer+"This option prohibits all members of the group from deleting any record in the 'Teachers' table."];
teachers_delete1Tip=["",spacer+"This option allows each member of the group to delete only his own records in the 'Teachers' table."];
teachers_delete2Tip=["",spacer+"This option allows each member of the group to delete any record owned by any member of the group in the 'Teachers' table."];
teachers_delete3Tip=["",spacer+"This option allows each member of the group to delete any records in the 'Teachers' table."];

/*
	Style syntax:
	-------------
	[TitleColor,TextColor,TitleBgColor,TextBgColor,TitleBgImag,TextBgImag,TitleTextAlign,
	TextTextAlign,TitleFontFace,TextFontFace, TipPosition, StickyStyle, TitleFontSize,
	TextFontSize, Width, Height, BorderSize, PadTextArea, CoordinateX , CoordinateY,
	TransitionNumber, TransitionDuration, TransparencyLevel ,ShadowType, ShadowColor]

*/

toolTipStyle=["white","#00008B","#000099","#E6E6FA","","images/helpBg.gif","","","","\"Trebuchet MS\", sans-serif","","","","3",400,"",1,2,10,10,51,1,0,"",""];

applyCssFilter();
