<head>
    <meta charset="utf-8">
    <title>Register</title>
    <link rel="stylesheet" href="style.css" media="screen" type="text/css" />
</head>
<header>
    <left>
        <content>
            <input type="image" id="burger-button" class="burger-menu" src="https://cdn-icons-png.flaticon.com/512/482/482609.png">
        </content>
    </left>
    <middle>
        <content>
            <a href="/php_template/menu.php">
                <img src="https://cdn-icons-png.flaticon.com/512/4685/4685215.png">
            </a>
        </content>
    </middle>
    <right>
        <content>
            <?php if (isset($_SESSION["username"])) {
                $image = "../Avatars/" . $_SESSION["avatar"];
                echo "<user>
                <a href=\"/function/logout.php\">
                <img src=\"https://cdn-icons-png.flaticon.com/512/992/992680.png\">
                </a>
                <a href=\"editingProfile.php\">
                <img src=\"$image\">
                </a>
                </user>";
            } else {
                echo "
                <a href=\"/php_template/registerhtml.php\">
                <input type=\"button\" value=\"Sign up\">
                </a>
                <a href=\"/php_template/loginhtml.php\">
                <input type=\"button\" value=\"Login\">
                </a>";
            } ?>
        </content>
    </right>
</header>
<burger-menu id="burger-menu">
    <a href="/php_template/home.php">
        <div id="burger-link">
            <p>Home</p>
        </div>
    </a>
    <a href="/php_template/tasklist.php">
        <div id="burger-link">
            <p>Tasks</p>
        </div>
    </a>
    <a href="/php_template/grouphtml.php">
        <div id="burger-link">
            <p>Mon groupe</p>
        </div>
    </a>
</burger-menu>

<script>
    const button = document.getElementById("burger-button");
    let isClicked = false;
    button.addEventListener("click", showForm);

    function showForm() {
        if (!isClicked) {
            isClicked = true;
            document.getElementById("burger-menu").style.display = "block";
        } else {
            isClicked = false;
            document.getElementById("burger-menu").style.display = "none";
        }
    }
</script>