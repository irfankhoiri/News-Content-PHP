<?php
session_start();

if (isset($_SESSION['user'])) {
    header("Location: profile.php");
}
// if( isset($_SESSION['user'])){
//     header("Location: profile.php");
// }
include('connect.php');



if (isset($_POST["submit"])) {
    function valid($data)
    {
        $data = trim(stripslashes(htmlspecialchars($data)));
        return $data;
    }

    $username = valid($_POST["username"]);
    $password = valid($_POST["password"]);
    $name = valid($_POST["name"]);
    $email = valid($_POST["email"]);
    // Additional validation for other fields can be added here

    include("connect.php");

    // Check if the username is already taken
    $checkQuery = "SELECT id FROM users WHERE username='$username'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        echo "<script>window.alert('Username already taken. Please choose another username.');</script>";
    } else {
        // Insert the new user into the database
        $insertQuery = "INSERT INTO users (username, password, name, email) VALUES ('$username', '$password', '$name', '$email')";
        $insertResult = mysqli_query($conn, $insertQuery);

        if ($insertResult) {
            echo "<script>window.alert('Account created successfully. You can now log in.');</script>";
        } else {
            echo "<script>window.alert('Error creating account. Please try again.');</script>";
        }
    }

    mysqli_close($conn);
}

?>

<!DOCTYPE html>
<html>

<head>
    <title> Signup </title>
    <link type="text/css" rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="css/material.css">
    <link type="text/css" rel="stylesheet" href="fonts/font.css">
    <link rel="icon" href="images/icon1.png">
</head>

<body id="_6">
    <!-- navigation bar -->
    <a href="index.php">
        <div id="log">
            <!-- <div id="i">G</div>
            <div id="cir">G</div> -->
            <div id="ntro">USIM News Content</div>
        </div>
    </a>
    <ul id="nav-bar">
        <!-- <a href="index.php"><li>Home</li></a> -->
        <a href="index.php">
            <li>News</li>
        </a>
        <a href="contacts.php">
            <li>Contact</li>
        </a>
        <a href="ask.php">
            <li>Ask Question</li>
        </a>
        <?php
        if (!isset($_SESSION['user'])) {
        ?>
            <a href="login.php">
                <li>Log In</li>
            </a>
        <?php
        } else {
        ?>
            <a href="profile.php">
                <li>Hi, <?php echo $_SESSION["user"]; ?></li>
            </a>
            <a href="logout.php">
                <li>Log Out</li>
            </a>
        <?php
        }
        ?>
    </ul>

    <!-- content -->
    <div id="content">
        <div id="sf">
            <center>
                <div class="heading">
                    <h1 class="logo">
                        <div id="i">i</div>
                        <div id="cir">i</div>
                        <div id="ntro">USIM News Content</div>
                    </h1>
                    <p id="tag-line">Where the USIM community discusses the latest news and trends</p>
                </div>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                    <input name="username" id="user" type="text" title="This will be your parmanent Id." placeholder="Create a Unique Username" required>
                    <input name="password" id="key" type="password" title="Password must contain at least 8 characters including alphabets,numbers, and symbols." placeholder="Create a Strong Password" required>
                    <i class="material-icons" id="lock">lock</i>
                    <i class="material-icons" id="person">person</i>
                    <input name="name" id="name" type="text" title="Although, you will be called by your name only" placeholder="Enter your Full Name" required>
                    <input name="email" id="mailbox" type="email" title="Your Email id is in safe hands." placeholder="Enter your Email Id" required>
                    <i class="material-icons" id="email">mail</i>
                    <i class="material-icons" id="iden">perm_identity</i>

                    <div id="button-block">
                        <center>
                            <div class="buttons"><input name="submit" type="submit" value="Create An Account" class="up-in"></div>
                            <div class="buttons" id="new"><input type="button" value="Already a member : Log In" class="up-in" id="tolog"></div>
                        </center>
                    </div>
                </form>
            </center>
        </div>

        <div id="ta">
            <h1>Successfully registered!
            </h1>
        </div>

    </div>

   

<!-- Rest of the HTML code remains unchanged -->


    <!-- Footer -->
    <?php include_once('footer.php'); ?>

    <!-- Sripts -->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script>
        window.jQuery || document.write('<script type="text/javascript" src="js/jquery-3.2.1.min.js"><\/script>')
    </script>
    <script type="text/javascript" src="js/script.js"></script>

</body>

</html>