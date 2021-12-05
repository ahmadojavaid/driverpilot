<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<div class="content-wrap" style="display: block;Margin:0 auto;margin:0 auto;background-color: #323232;width:600px;">
    <div class="content" style="padding: 10px; background-color: #ffffff;text-align: center;font-size: 18px;color: #323232;padding: 20px 30px;">
        <div class="logo" style="margin:50px auto;Margin:50px auto;">
            <?php if(isset($user->name)): ?>
                <h1>Hello <?php echo e($user->name); ?></h1>
            <?php elseif(isset($user->first_name)): ?>
                <h1>Hello <?php echo e($user->first_name); ?></h1>
            <?php endif; ?>
        </div>
        <div class="text-div" style="margin: 50px auto;Margin:50px auto;">
            <h1><span style="color:#0099cb;text-transform: uppercase;Margin-left:10px;margin-left:10px;"><?php echo e(''); ?></span></h1>
            <h3 style="margin: 30px auto;Margin:30px auto;">Hit verify to complete registration and get ready to get on-board with us</h3>
        </div>
        <a href="<?php echo e(URL::to(config('APP_URL')."/api/user/confirm/$user->id/$user->verification_token")); ?>" style="background-color: #66cc9a;border:3px solid #63c694;display: inline-block;padding: 10px 40px;box-shadow: 1px 3px 5px #63c694;text-decoration: none;cursor: pointer;color: white;margin: 30px auto;Margin:30px auto;">Click here to Verify your account</a>
    </div>
    <div class="footer" style="color:white;padding: 20px 30px;text-align: center;">
        <p></p>
    </div>
</div>
</body>
</html>
<?php /**PATH /home/jobeskdev/public_html/dp.jobesk.com/resources/views/emails/verify.blade.php ENDPATH**/ ?>