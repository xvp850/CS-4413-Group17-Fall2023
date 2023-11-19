<?php
//create_cat.php 
session_start();

include 'connect.php';
include 'header.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
echo '<h2>Create a topic</h2>';
if($_SESSION['signed_in'] == false)
{
    //the user is not signed in 
    echo 'Sorry, you have to be <a href="/sign_in.php">signed in</a> to create a topic.';
}
else
{
	//the user is signed in 
	if($_SERVER['REQUEST_METHOD'] != 'POST')
	{	
		//the form hasn't been posted yet, display it 
		//retrieve the categories from the database for use in the dropdown 
		$sql = "SELECT 
cat_id, 
cat_name, 
cat_description 
FROM 
categories";
        $result = mysqli_query($db_connection, $sql);
		if(!$result)
		{
			//the query failed, uh-oh :-( 
			echo 'Error while selecting from database. Please try again later.';
		}
		else
		{
			if(mysqli_num_rows($result) == 0)
			{
				//there are no categories, so a topic can't be posted 
				if($_SESSION['user_level'] == 1)
				{
					echo 'You have not created categories yet.';
				}
				else
				{
					echo 'Before you can post a topic, you must wait for an admin to create some categories.';
				}
			}
			else
			{
				echo '<form method="post" action=""> 
Subject: <input type="text" name="topic_subject" /> 
Category:'; 
				echo '<select name="topic_cat">';
					while($row = mysqli_fetch_assoc($result))
					{
						echo '<option value="' . $row['cat_id'] . '">' . $row['cat_name'] . '</option>';
					}
				echo '</select>';	
				echo 'Message: <textarea name="post_content" /></textarea> 
<input type="submit" value="Create topic" /> 
</form>';
			}
		}
	}
	else
	{
        mysqli_begin_transaction($db_connection);
		//start the transaction 
		$query = "BEGIN WORK;";
        $result = mysqli_query($db_connection, $query);
		if(!$result)
		{
			//Damn! the query failed, quit 
			echo 'An error occured while creating your topic. Please try again later.';
		}
		else
		{
            $topic_subject = mysqli_real_escape_string($db_connection, $_POST['topic_subject']);
            $topic_cat = mysqli_real_escape_string($db_connection, $_POST['topic_cat']);
            $topic_by = $_SESSION['user_id'];
			//the form has been posted, so save it 
			//insert the topic into the topics table first, then we'll save the post into the posts table 
			$sql = "INSERT INTO 
topics(topic_subject, 
topic_date, 
topic_cat, 
topic_by) 
VALUES('$topic_subject', 
NOW(), 
'$topic_cat', 
'$topic_by' 
)";
            $result = mysqli_query($db_connection, $sql);
			if(!$result)
			{
				//something went wrong, display the error 
				echo 'An error occurred while inserting your data. Please try again later.' . mysqli_error($db_connection);
                $sql = "ROLLBACK;";
                $result = mysqli_query($db_connection, $sql);
			}
			else
			{
				//the first query worked, now start the second, posts query 
				//retrieve the id of the freshly created topic for usage in the posts query 
				$topicid = mysqli_insert_id($db_connection);
                $post_content = mysqli_real_escape_string($db_connection, $_POST['post_content']);
                $post_topic = $topicid;
                $post_by = $_SESSION['user_id'];
				$sql = "INSERT INTO 
posts(post_content, 
post_date, 
post_topic, 
post_by) 
VALUES 
('$post_content', 
NOW(), 
'$post_topic', 
'$post_by' 
)";
                $result = mysqli_query($db_connection, $sql);
				if(!$result)
				{
					//something went wrong, display the error 
					echo 'An error occured while inserting your post. Please try again later.' . mysql_error();
					$sql = "ROLLBACK;";
                    $result = mysqli_query($db_connection, $sql);
				}
				else
				{
					$sql = "COMMIT;";
                    $result = mysqli_query($db_connection, $sql);
					//after a lot of work, the query succeeded! 
					echo 'You have successfully created <a href="topic.php?id='. $topicid . '">your new topic</a>.';
				}
			}
		}
	}
}
include 'footer.php';
?>
