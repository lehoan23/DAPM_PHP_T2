<body>
    <h1>Forgot Password</h1>
    <?php if(session('success')): ?>
        <p style="color: green;"><?php echo e(session('success')); ?></p>
    <?php endif; ?>
    <form action="http://127.0.0.1:8000/testemail" method="POST">
        <?php echo csrf_field(); ?>
        <label for="email">Enter your email:</label><br>
        <input type="email" id="email" name="email" required>
        <button type="submit">Send</button>
    </form>
</body>
<?php /**PATH E:\PHP_CNM\DAPM_PHP_T2\backend\resources\views/mail/forgot-password.blade.php ENDPATH**/ ?>