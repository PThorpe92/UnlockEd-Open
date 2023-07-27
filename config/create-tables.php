<?php
namespace unlockedlabs\unlocked;
?>
<!DOCTYPE html>
<html lang="en">
<head>
        <title>Setting up database</title>
</head>

<body>
    <h3>Creating tables ...</h3>

<?php

// include database and core files
require_once dirname(__FILE__).'/../config/database.php';

//generate table if not exist
function createTable($name, $query)
{

    // instantiate database object
    $database = new Database();
    $db = $database->getConnection();

    $createTable = "CREATE TABLE IF NOT EXISTS $name($query)";
    $stmt = $db->prepare($createTable);
    try
    {
        $stmt->execute();
    }
    catch(\PDOException $e)
    {
        echo $e->getMessage();
        
    }
}

/*
Be sure to create the admin_levels and access_levels tables first
as the other tables reference them with FOREIGN KEYS
*/

createTable(
    'admin_levels',
    'id INT(11) AUTO_INCREMENT PRIMARY KEY,
    admin_num INT,
    admin_name TEXT,
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP'
);

createTable(
    'access_levels',
    'id INT(11) AUTO_INCREMENT PRIMARY KEY,
    access_num INT,
    access_name TEXT,
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP'
);

createTable(
    'categories',
    'id VARCHAR(64) PRIMARY KEY,
    category_name TEXT,
    access_id INT(11),
    admin_id INT(11),
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (access_id) REFERENCES access_levels(id),
    FOREIGN KEY (admin_id) REFERENCES admin_levels(id)'
);

createTable(
    'topics',
    'id VARCHAR(64) PRIMARY KEY,
    topic_name TEXT,
    category_id VARCHAR(64),
    iframe TEXT DEFAULT NULL,
    access_id INT(11),
    admin_id INT(11),
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (access_id) REFERENCES access_levels(id),
    FOREIGN KEY (admin_id) REFERENCES admin_levels(id)'
);

createTable(
    'courses',
    'id VARCHAR(64) PRIMARY KEY,
    course_name TEXT,
    course_desc TEXT,
    topic_id VARCHAR(64),
    course_img TEXT DEFAULT NULL,
    iframe TEXT DEFAULT NULL,
    access_id INT(11),
    admin_id INT(11),
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (access_id) REFERENCES access_levels(id),
    FOREIGN KEY (admin_id) REFERENCES admin_levels(id)'
);

createTable(
    'lessons',
    'id VARCHAR(64) PRIMARY KEY,
    lesson_name TEXT,
    course_id VARCHAR(64),
    editor_html TEXT,
    media_dir TEXT,
    access_id INT(11),
    admin_id INT(11),
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (access_id) REFERENCES access_levels(id),
    FOREIGN KEY (admin_id) REFERENCES admin_levels(id)'
);

createTable(
    'media',
    'id VARCHAR(64) PRIMARY KEY,
    course_id VARCHAR(64),
    lesson_id VARCHAR(64),
    parent_dir TEXT,
    src_path TEXT,
    order_pos INT,
    icon TEXT,
    display_name TEXT,
    access_id INT(11),
    admin_id INT(11),
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (access_id) REFERENCES access_levels(id),
    FOREIGN KEY (admin_id) REFERENCES admin_levels(id),
    required TINYINT DEFAULT 0'
);

createTable(
    'media_progress',
    'id VARCHAR(64) PRIMARY KEY,
    course_id VARCHAR(64),
    lesson_id VARCHAR(64),
    student_id TEXT,
    media_id VARCHAR(64),
    file_location TEXT,
    file_type TEXT,
    file_name TEXT,
    duration INT,
    current_pos INT,
    completed TINYINT,
    reflection TEXT,
    deleted TINYINT,
    required TINYINT,
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
);

createTable(
    'users',
    'id VARCHAR(64) PRIMARY KEY,
    username TEXT,
    password TEXT,
    email VARCHAR(128),
    oid VARCHAR(128),
    access_id INT(11),
    admin_id INT(11),
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (access_id) REFERENCES access_levels(id),
    FOREIGN KEY (admin_id) REFERENCES admin_levels(id),
    logged_in TIMESTAMP DEFAULT "1970-01-01 00:00:01"'
);

// NOTE: This is an intermediary table to represent the many-to-many relationship btwn categories and students.
createTable(
    'category_enrollments',
    'category_id VARCHAR(64),
    student_id VARCHAR(64),
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE');

createTable(
    'quizzes',
    'id VARCHAR(64) PRIMARY KEY,
    quiz_name TEXT,
    quiz_desc TEXT,
    /* allowed_attempts INT, */ /* -1 equals unlimited */
    lesson_id VARCHAR(64),
    admin_id INT(11),
    created DATETIME NOT NULL,
    modified TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (admin_id) REFERENCES admin_levels(id) ON DELETE SET NULL ON UPDATE CASCADE'
);

createTable(
    'question_bank',
    'id VARCHAR(64) PRIMARY KEY,
    bank_name VARCHAR(64),
    created DATETIME NOT NULL,
    modified TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(bank_name)'
);

createTable(
    'questions',
    'id VARCHAR(64) PRIMARY KEY,
    question_text TEXT,
    bank_id VARCHAR(64) DEFAULT 1,
    admin_id INT(11),
    created DATETIME NOT NULL,
    modified TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (bank_id) REFERENCES question_bank(id) ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (admin_id) REFERENCES admin_levels(id) ON DELETE SET NULL ON UPDATE CASCADE'
);

// NOTE: This is an intermediary table to represent the many-to-many relationship btwn quizzes and questions.
createTable(
    'quiz_questions',
    'quiz_id VARCHAR(64),
    question_id VARCHAR(64),
    points INT,
    question_position INT,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE ON UPDATE CASCADE'
);

createTable(
    'answers',
    'id VARCHAR(64) PRIMARY KEY,
    answer_text VARCHAR(255),
    question_id VARCHAR(64),
    correct ENUM("yes", "no") NOT NULL,
    answer_position INT,
    created DATETIME NOT NULL,
    modified TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE ON UPDATE CASCADE'
);

createTable(
    'submissions',
    'id VARCHAR(64) PRIMARY KEY,
    assignment_id VARCHAR(64),
    student_id VARCHAR(64),
    type VARCHAR(64),
    attempt INT,
    /* extra_attempts INT, */
    score INT,
    /* kept_score INT, */
    grade VARCHAR(32), /* grade types: pass_fail, percent, letter_grade, gpa_scale, points */
    questions TEXT,
    submitted_answers TEXT,
    comments TEXT,
    submitted TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE');

createTable(
    'cohorts',
    'id VARCHAR(64) PRIMARY KEY,
    cohort_name VARCHAR(64),
    facilitator_id VARCHAR(64), /* user id of facilitator (only one facilitator per cohort) */
    course_id VARCHAR(64), /* course id */
    created DATETIME NOT NULL,
    FOREIGN KEY (facilitator_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE ON UPDATE CASCADE');

// NOTE: This is an intermediary table to represent the many-to-many relationship btwn cohorts and students.
createTable(
    'cohort_enrollments',
    'cohort_id VARCHAR(64),
    student_id VARCHAR(64),
    FOREIGN KEY (cohort_id) REFERENCES cohorts(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE');

// NOTE: This is an intermediary table to represent the many-to-many relationship btwn categories and administrators.
createTable(
    'category_administrators',
    'category_id VARCHAR(64),
    administrator_id VARCHAR(64),
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (administrator_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE');

// NOTE: This is an intermediary table to represent the many-to-many relationship btwn courses and administrators.
createTable(
    'course_administrators',
    'course_id VARCHAR(64),
    administrator_id VARCHAR(64),
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (administrator_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE');


createTable(
    'user_gamification',
    'id VARCHAR(64) PRIMARY KEY,
    username TEXT,
    coins INT,
    coin_balance INT,
    user_level INT,
    user_status VARCHAR(16),
    logins INT,
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP'
);

createTable(
    'user_preferences',
    'id VARCHAR(64) PRIMARY KEY,
    username TEXT,
    banner INT,
    night_mode VARCHAR(16),
    user_color VARCHAR(16),
    dashboard_color VARCHAR(16),
    sidebar_toggle BOOL NOT NULL'
);

createTable(
    'email',
    'id INT(11) AUTO_INCREMENT PRIMARY KEY,
    message_id VARCHAR(64) NOT NULL,
    recipient_ids varchar(1024) NOT NULL,
    recipient_names varchar(1024) NOT NULL,
    recipient_colors varchar(1024) NOT NULL,
    sender_ids varchar(1024) NOT NULL,
    sender_names varchar(1024) NOT NULL,
    sender_colors varchar(1024) NOT NULL,
    date_time datetime DEFAULT NULL,
    read_unread tinyint(1) NOT NULL,
    subject varchar(256) NOT NULL,
    message longtext NOT NULL,
    sender_folder varchar(16) NOT NULL,
    recipient_folder varchar(16) NOT NULL,
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');

createTable(
    'site_settings',
    'id VARCHAR(64) PRIMARY KEY,
    setting VARCHAR(256) NOT NULL,
    value VARCHAR(256) NOT NULL,
    read_only BOOL NOT NULL,
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');

createTable(
    'user_task_list',
    'id VARCHAR(64) NOT NULL,
    task_master_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    task_id INT(11),
    task VARCHAR(64) NOT NULL,
    checked BOOL NOT NULL,
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
    
createTable(
    'admin_privileges',
    'id VARCHAR(64) PRIMARY KEY,
    name VARCHAR(256) NOT NULL,
    friendly_name VARCHAR(256) NOT NULL,
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
        
// Total of 26 tables
?>

<br>...done.

</body>
</html>