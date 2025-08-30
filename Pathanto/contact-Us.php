<?php include "header.php"; ?>

<li class="navItem more-topic">
    <button class="dropbtn ">More+</button>
</li>
</ul>
</nav>
</section>
<?php include "more-topic.php";?>
<?php include "togaleMenu.php";?>

<div class="container">
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <!-- top-add -->
    <ins class="adsbygoogle top-ads" style="display:block" data-ad-client="ca-pub-2890698937074897"
        data-ad-slot="9088704451" data-full-width-responsive="true"></ins>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
    <div class="row">
        <div class="col-lg-6">
            <h4 class="sc-chapter-name">Send Us a Massage</h4>
            <p style="text-align:center;">We are here to help you</p>
            <div style="padding:10px;">
                <form method="post" onsubmit="return validateForm()">
                    <div class="form-group">
                        Name: <input type="text" class="form-control" name="name" placeholder="Name" required>
                    </div>
                    <div class="form-group">
                        Email:<input type="email" class="form-control" name="email" placeholder="Email" required>
                    </div>
                    <div class="form-group green-border-focus">
                        <label for="exampleFormControlTextarea5">Message</label>
                        <textarea class="form-control" name="massage" id="exampleFormControlTextarea5" rows="3"
                            required></textarea>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
        <div class="col-lg-6">
            <center>
                <h2> Contact Us</h2>
                <p>Email Us: info@pathanto.com</p>
            </center>
        </div>
    </div>
</div>

<?php include "footer.php";?>

<?php
if(isset($_POST['submit']))
{
    $servername = "localhost";
    $database = "u285245875_CommentData";
    $username = "u285245875_pathanto_soni";
    $password = "Arman23@31";;

    $conn = mysqli_connect($servername, $username, $password, $database);

    $Name = mysqli_real_escape_string($conn, htmlspecialchars($_POST['name']));
    $Email = mysqli_real_escape_string($conn, htmlspecialchars($_POST['email']));
    $Massage = mysqli_real_escape_string($conn, htmlspecialchars($_POST['massage']));

    // Additional validation (you can customize this according to your requirements)
    if (empty($Name) || empty($Email) || empty($Massage)) {
        // Handle empty fields
        // You can redirect back to the form with an error message
        header("Location: /Pathanto/contact-Us?error=emptyfields");
        exit();
    }

    // Your SQL query
    $sqlInsert = "INSERT INTO Studentlogin VALUES ('$Name', '$Massage', '$Email')";

    if (mysqli_query($conn, $sqlInsert)) {
        header("Location: /Pathanto/contact-Us");
        exit;
    } else {
        // Handle database error
        header("Location: /Pathanto/contact-Us?error=dberror");
        exit();
    }
}
?>

<script>
    function validateForm() {
        var name = document.forms["yourForm"]["name"].value;
        var email = document.forms["yourForm"]["email"].value;
        var massage = document.forms["yourForm"]["massage"].value;

        if (name == "" || email == "" || massage == "") {
            alert("All fields must be filled out");
            return false;
        }

        // Additional validation if needed...

        return true;
    }
</script>
