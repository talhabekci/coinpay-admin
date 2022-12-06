<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/x-icon" href="http://localhost/coinpay-admin/assets/img/cp-favicon.png">
    <link rel="stylesheet" href="http://localhost/coinpay-admin/assets/css/signup/signup.css">
    <link rel="stylesheet" href="http://localhost/coinpay-admin/assets/fontawesome.com/css/all.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,400;0,700;1,300&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="http://localhost/coinpay-admin/assets/js/signup/signup.js"></script>
    <title>Signup</title>
</head>

<body>
    <div class="container">
        <img src="http://localhost/coinpay-admin/assets/img/coinpay-svg.svg" width="80" height="80" alt="Logo" class="coinpay-logo">
        <div class="modal">
            <div class="header">
                <div class="header-title">Welcome to CoinPay</div>
                <div class="header-text">CoinPay provides an easy way to pay with and get paid in crypto. Create a business or personal account below to get started.</div>
                <hr>
            </div>
            <div class="error-message signup">
                <i class="fa-regular fa-circle-exclamation"></i>
                <span class="error-message-text"></span>
            </div>
            <div class="success-message signup">
                <i class="fa-regular fa-circle-check"></i>
                <span class="success-message-text"></span>
            </div>
            <div class="signup">
                <form class="signup-form" method="post">
                    <div class="name-surname">
                        <div class="fname">
                            <label for="fname">First Name</label>
                            <input type="text" name="fname" id="fname" class="input fname">
                            <div class="error-message fname">First Name is required.</div>
                        </div>
                        <div class="lname">
                            <label for="lname">Last Name</label>
                            <input type="text" name="lname" id="lname" class="input lname">
                            <div class="error-message lname"> Last Name is required.</div>
                        </div>
                    </div>
                    <div class="email">
                        <label for="email">Email Address</label>
                        <input type="text" name="email" id="email" class="input email">
                        <div class="error-message email">Email Address is required.</div>
                    </div>
                    <div class="password">
                        <label for="password">Password</label>
                        <i class="fa-regular fa-eye-slash"></i>
                        <input type="password" name="password" id="password" class="input password">
                        <div class="error-message password">Password must be longer than 8 characters.</div>
                    </div>
                    <div class="terms">
                        <input type="checkbox" name="terms" class="input terms">
                        <div class="terms-text">
                            I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.
                            <div class="error-message terms-privacy" style="opacity:1;">You should accept the terms.</div>
                        </div>
                    </div>
                    <div class="submit-button">
                        <button type="submit" name="submit" class="submit-signup">Submit</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="login-link">Already have an account? <a href="http://localhost/coinpay-admin/login/">Login</a> </div>
    </div>
</body>

</html>
