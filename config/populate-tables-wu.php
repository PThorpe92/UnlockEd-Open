<?php
namespace unlockedlabs\unlocked;
?>
<!DOCTYPE html>
<html lang="en">
<head>
        <title>Setting up database</title>
</head>

<body>
    <h3>Populating tables ...</h3>

<?php
// include database and core files
require_once dirname(__FILE__) . '/../config/database.php';

// instantiate database object
$database = new Database();
$db = $database->getConnection();


function truncateTable($db, $tableName)
{

    $load = "SET FOREIGN_KEY_CHECKS=0; TRUNCATE " . $tableName . "; SET FOREIGN_KEY_CHECKS=1";
    $stmt = $db->prepare($load);

    try
    {
        $stmt->execute();        
    }
    catch(\PDOException $e)
    {
        echo $e->getMessage();
        
    }
}

function insertIntoCategories($db)
{

    //we must keep Unassigned Topics and Courses as id 1, see delete_category.php for more information 6-17-20201
    $load = "INSERT INTO categories (id, category_name, access_id, admin_id) VALUES
    ('0c0b7ed8-d54e-47b9-a32c-fb28c857ac1b','Washington University', '1', '1'),
    ('0fed849c-cb14-4121-bbd5-a4d458bd6a5f','Reference Materials', '1', '1'),
    ('9419d847-d80f-42b4-aeda-675b100bd0b9','Additional Courses', '1', '1'),
    ('94876a68-f185-4967-b5fb-f90859ffd5a8','Unassigned Topics and Courses', '1', '1');";

    $stmt = $db->prepare($load);
    try
    {
        $stmt->execute();
    }
    catch(\PDOException $e)
    {
        echo $e->getMessage();
        
    }
}

function insertIntoCategoryTopics($db)
{

    //we must keep Unassigned Courses as id 1, see delete_category.php for more information 6-17-20201
    $load = "INSERT INTO topics (id, topic_name, category_id, iframe, access_id, admin_id)
    VALUES
    ('05c2ef38-9caa-477e-8c8f-dd2b8e4630cb','Wash. U. Courses', '0c0b7ed8-d54e-47b9-a32c-fb28c857ac1b', 'http://localhost:3000', '1', '1'),
    ('03a13e51-4569-4e98-9761-2d77e823a5f3','JSTOR', '0fed849c-cb14-4121-bbd5-a4d458bd6a5f', '', '1', '1'),
    ('0cf08ac4-6bab-41d8-8cc6-4bbbb4a14e99','Wikipedia for Schools', '0fed849c-cb14-4121-bbd5-a4d458bd6a5f', 'http://thelearningcenter.doc.mo.gov:8080/en-wikipedia_for_schools-static/index.html', '1', '1'),
    ('20efa169-e00e-48e9-9798-2bd7fd1646a1','Gutenberg', '0fed849c-cb14-4121-bbd5-a4d458bd6a5f', '', '1', '1'),
    ('438323df-c4d7-4a84-b5ca-d0078aedd7a7','Khan Academy', '9419d847-d80f-42b4-aeda-675b100bd0b9', '', '1', '1'),
    ('f5bd24fb-81a7-4a95-9cdf-5b09183bcada','Unassigned Courses', '94876a68-f185-4967-b5fb-f90859ffd5a8', '', '1', '1');";

    $stmt = $db->prepare($load);
    try
    {
        $stmt->execute();
    }
    catch(\PDOException $e)
    {
        echo $e->getMessage();
        
    }
}

function insertIntoUsers($db)
{

    //pwd hashed is $2y$10\$GdW0ms3ezuL4.Y5aA1eFKub59XEZNsgtiR0DOh76AghxmZzJ0L5La
    
    $load = "INSERT INTO users (id, username, password, email, oid, access_id, admin_id) VALUES
    ('e214a147-0d06-4322-9380-6bdd8c2367c0','admin', '$2y$10\$GdW0ms3ezuL4.Y5aA1eFKub59XEZNsgtiR0DOh76AghxmZzJ0L5La', 'admin@gmail.com', null, '1', '5');";
    

    $stmt = $db->prepare($load);
    try
    {
        $stmt->execute();
    }
    catch(\PDOException $e)
    {
        echo $e->getMessage();
        
    }
}

function insertIntoQuestionBank($db)
{
    // to get time-stamp for 'created' field
    $timestamp = date('Y-m-d H:i:s');
    $load = <<<QUERY
    INSERT INTO question_bank (
        id, bank_name, created) VALUES
        ('c5312268-5404-4eeb-afc2-5b9c2f63d9bd', 'Unfiled Questions', :created);
QUERY;

    // sanitize time-stamp
    $timestamp=htmlspecialchars(strip_tags($timestamp));

    $stmt = $db->prepare($load);
    $stmt->bindParam(":created", $timestamp);

    try
    {
        $stmt->execute();
    }
    catch(\PDOException $e)
    {
        echo $e->getMessage();
        
    }
}

function insertIntoAccessLevels($db)
{
    
    $load = "INSERT INTO access_levels (access_num, access_name) VALUES
    ('1', 'Open Enrollment'),
    ('2', 'Category Enrollment Required'),
    ('3', 'Course Enrollment Required'),	
    ('4', 'Cohort Enrollment Required'),
    ('5', 'Group Enrollment Required');";

    $stmt = $db->prepare($load);
    try
    {
        $stmt->execute();
    }
    catch(\PDOException $e)
    {
        echo $e->getMessage();
        
    }
}

function insertIntoAdminLevels($db)
{
    
    $load = "INSERT INTO admin_levels (admin_num, admin_name) VALUES
    ('1', 'Student (Non-Admin)'),
    ('2', 'Facilitator (Cohort Admin)'),
    ('3', 'Instructor (Course Admin)'),	
    ('4', 'School Admin (Category Admin)'),
    ('5', 'Site Admin');";

    $stmt = $db->prepare($load);
    try
    {
        $stmt->execute();
    }
    catch(\PDOException $e)
    {
        echo $e->getMessage();
        
    }
}

function insertIntoUserPreferences($db)
{

    $load = "INSERT INTO user_preferences (id, username, banner, night_mode, user_color, dashboard_color, sidebar_toggle) VALUES
    ('e214a147-0d06-4322-9380-6bdd8c2367c0','admin', '1', 'light', '#8BC34A', '#37474F', '1');";

    $stmt = $db->prepare($load);
    try
    {
        $stmt->execute();
    }
    catch(\PDOException $e)
    {
        echo $e->getMessage();
        
    }
}

function insertIntoTaskList($db)
{

    $load = "INSERT INTO user_task_list (id, task_id, task, checked) VALUES
    ('e214a147-0d06-4322-9380-6bdd8c2367c0', '1', 'My first task', '0');";
    
    $stmt = $db->prepare($load);
    try
    {
        $stmt->execute();
    }
    catch(\PDOException $e)
    {
        echo $e->getMessage();
        
    }
}

function insertIntoGamification($db)
{

    $load = "INSERT INTO user_gamification (id, username, coins, coin_balance, user_level, user_status, logins) VALUES
    ('e214a147-0d06-4322-9380-6bdd8c2367c0','admin', '0', '0', '1', 'NEW USER', '0');";

    $stmt = $db->prepare($load);
    try
    {
        $stmt->execute();
    }
    catch(\PDOException $e)
    {
        echo $e->getMessage();
        
    }
}

function insertIntoSiteSettings($db)
{

    $load = "INSERT INTO site_settings (id, setting, value, read_only) VALUES
    ('bc1853ad-66e0-4bbb-a5fe-d79632d07b1d', 'site_url', 'http://unlockedlabs.com/demo', '1'),
    ('0b8e4a00-6b38-4b00-854f-446d0b9e1beb', 'gamification_enabled', 'true', '0'),
    ('0b5b0233-1db4-4828-b6f2-4cbf405be5db', 'email_enabled', 'false', '0'),
    ('b6db0c46-fb0a-42c3-9889-bd418e712553', 'timezone_setting', 'America/Chicago', '0')";

    $stmt = $db->prepare($load);
    try
    {
        $stmt->execute();
    }
    catch(\PDOException $e)
    {
        echo $e->getMessage();
        
    }

}

function insertIntoAdminPrivileges($db)
{

    $load = "INSERT INTO admin_privileges (id, name, friendly_name) VALUES
        ('17d2a983-463b-4c23-ab52-a423660a9b43', 'create_user', 'Create User'),
        ('8b063d9e-daba-41ac-b32c-28f0515a02e1', 'modify_user', 'Modify User'),
        ('02d92974-6d2d-47cd-afa5-dcc166b8cc09', 'delete_user', 'Delete User'),
        ('5a95afdf-4f44-46e2-9483-134b40470196', 'create_category', 'Create Category'),
        ('42cd2d29-e5f8-4dc0-ac6d-db4a246ff9d5', 'modify_category', 'Modify Category'),
        ('74bd030e-b9bc-4738-85b0-b87134a35c62', 'delete_category', 'Delete Category'),
        ('6627a2d4-9c00-4418-b289-3ee96e2a9edf', 'create_topic', 'Create Topic'),
        ('2fedc1fe-25de-45d6-aaf5-64b9b7d66b39', 'modify_topic', 'Modify Topic'),
        ('7769944e-8708-486b-a0cf-c012d0c29a7a', 'delete_topic', 'Delete Topic'),
        ('f8f9408a-94c9-4c31-ac6e-b01b3e3eee7f', 'create_course', 'Create Course'),
        ('dcdb24be-7a7a-4554-bfc4-8ef268280d3a', 'modify_course', 'Modify Course'),
        ('a5f3db50-1941-4a25-b9bf-7264f4c53c5b', 'delete_course', 'Delete Course'),
        ('459842a9-c35d-4482-8c9a-0dd1933c9390', 'create_lesson', 'Create Lesson'),
        ('5a32083b-54c5-4624-9fef-8959737326b1', 'modify_lesson', 'Modify Lesson'),
        ('e1088c04-09e2-4559-b9a1-365040ba8fda', 'delete_lesson', 'Delete Lesson');";

    $stmt = $db->prepare($load);
    try
    {
        $stmt->execute();
    }
    catch(\PDOException $e)
    {
        echo $e->getMessage();
    }

}

truncateTable($db, 'category_administrators');
truncateTable($db, 'course_administrators');
truncateTable($db, 'category_enrollments');
truncateTable($db, 'cohort_enrollments');
truncateTable($db, 'cohorts');
truncateTable($db, 'submissions');
truncateTable($db, 'topics');
truncateTable($db, 'categories');
truncateTable($db, 'courses');
truncateTable($db, 'lessons');
truncateTable($db, 'users');
truncateTable($db, 'media');
truncateTable($db, 'media_progress');
truncateTable($db, 'answers');
truncateTable($db, 'quiz_questions');
truncateTable($db, 'questions');
truncateTable($db, 'quizzes');
truncateTable($db, 'question_bank');
//empty access_levels and admin_levels last as they have FOREIGN KEY references to them
truncateTable($db, 'access_levels');
truncateTable($db, 'admin_levels');
truncateTable($db, 'user_gamification');
truncateTable($db, 'user_preferences');
truncateTable($db, 'user_task_list');
truncateTable($db, 'email');
truncateTable($db, 'site_settings');
truncateTable($db, 'admin_privileges');

//populate access and admin levels first as the other tables reference them with FOREIGN KEYs
insertIntoAccessLevels($db);
insertIntoAdminLevels($db);
insertIntoCategoryTopics($db);
insertIntoCategories($db);
insertIntoUsers($db);
insertIntoQuestionBank($db);
insertIntoGamification($db);
insertIntoUserPreferences($db);
insertIntoTaskList($db);

//NOTE: there is no insertInto command for the email table
insertIntoSiteSettings($db);
insertIntoAdminPrivileges($db)
?>

<br>...done.

</body>
</html>