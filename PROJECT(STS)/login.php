<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMART TRIP SHARE - Sign In & Sign Up</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    background: url("properties/bg-img.png") no-repeat center center fixed;
    background-size: cover;
    margin: 0;
    padding: 0;
    width: 100vw;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden; /* Prevent scrolling */
    position: relative;
}


        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: -1;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 350px;
            position: relative;
        }

        .back-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            font-weight: bolder;
            text-decoration: none;
            padding: 5px;
            border-radius: 25%;
            background-color: #ffcc00;
            color: red;
            transition: transform 0.3s ease-in-out, background 0.3s;
        }

        .back-btn:hover {
            color: black;
            transform: scale(1.2);
            background: rgba(255, 0, 0, 0.5);
        }

        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .logo img {
            width: 40px;
            margin-right: 10px;
        }

        .logo h2 {
            font-size: 20px;
            font-weight: bold;
        }

        h3 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .options {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            margin-bottom: 15px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #ffcc00;
            color: black;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        button:hover {
            background-color: #e6b800;
        }

        .form-container {
            display: none;
        }

        .sign-in {
            display: flex;
            flex-direction: column;
        }
        .sign-up{
            display:none;
            flex-direction: column;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="logo">
            <img src="properties/logo.png" alt="Return Loads Logo">
            <h2>SMART TRIP SHARE</h2>
        </div>

        <!-- SIGN IN PAGE -->
        <div class="form-container sign-in">
            <a class="back-btn" href="home1.php">x</a>
            <h3>Sign In to your account</h3>
            <form method="post" action="auth.php">
                <input type="email" name="email" placeholder="E-Mail" required>
                <input type="password" name="password" placeholder="Password" required>
                <div class="options">
                    <a href="#" id="show-signup">Sign Up</a>
                </div>
                <button type="submit" name="signIn">Sign In</button>
            </form>
        </div>

        <!-- SIGN UP PAGE -->
        <div class="form-container sign-up">
            <a class="back-btn" href="#" id="back-to-signin">←</a>
            <h3>Create an account</h3>
            <form method="post" action="auth.php">
                <input type="text" name="fullname" placeholder="Full Name" required>
                <input type="text" name="number" pattern="[0-9]{10}" maxlength="10" placeholder="Phone Number" required 
                    oninput="this.value = this.value.replace(/\D/g, '')">
                <input type="email" name="email" placeholder="E-Mail" required>
                <input type="password" name="password" placeholder="Password" required
                    pattern="(?=.*[A-Z])(?=.*[\W]).{8,}" 
                    title="Password must be at least 8 characters, include 1 uppercase letter, and 1 special character.">
                <div class="options">
                    <a href="#" id="show-signin">Already have an account? Sign In</a>
                </div>
                <button type="submit" name="signUp">Sign Up</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById("show-signup").addEventListener("click", function () {
            document.querySelector(".sign-in").style.display = "none";
            document.querySelector(".sign-up").style.display = "flex";
        });

        document.getElementById("show-signin").addEventListener("click", function () {
            document.querySelector(".sign-up").style.display = "none";
            document.querySelector(".sign-in").style.display = "flex";
        });

        document.getElementById("back-to-signin").addEventListener("click", function () {
            document.querySelector(".sign-up").style.display = "none";
            document.querySelector(".sign-in").style.display = "flex";
        });
    </script> 
</body>

</html>
