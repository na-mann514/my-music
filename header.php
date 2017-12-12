<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if (isset($_SESSION)) {
    session_start();
    $username = $_SESSION['username'];
}
?>

<div id='section1' align='left'>
    <form name='review' action='searchbox.php' onsubmit='return validateForm()' method='post'>
        <a href='user-dashboard.php'><img src='background.png' alt='HTML5 Icon'></a>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
        <input placeholder='Search' type='text' name='keyword' id='keyword' />
        <input type='submit' name='submit' value='Search' />&emsp;&emsp;&emsp;&emsp;&emsp;
            <a href='userprofile.php?uname=<?php echo $username; ?>'> Profile</a>&emsp;&emsp;
            <a href='logout.php'>Logout</a></form></div>


