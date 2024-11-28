<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($emailContent['subject']); ?></title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #F1F3F8;">
    <table role="presentation" align="center" cellspacing="0" cellpadding="0" width="100%" style="border-collapse: collapse; max-width: 600px; margin: auto; background-color: #F1F3F8;">
        <tr>
            <td style="padding: 40px; margin-top:40px;">
                <div style="text-align: center;">
                    <a href="" target="_blank" style="font-size: 28px; font-weight: bold">
                        GiveNow
                    </a>
                </div>
                <div style="border-radius: 5px; color: #000000; line-height: 1.6; background-color: #FFFFFF; border: 1px solid #f1f1f1; padding: 20px; box-shadow: 1px 1px 5px 1px #f1f1f1; margin-top: 20px; text-align: left; width: 100%;">
                    <p style="color: #000000;"><?php echo e($emailContent['body']); ?></p>
                    <p style="text-align: center;">
                        <a style="display: inline-block; cursor: pointer; padding: 7px 12px; background-color: #002559; color: #FFFFFF; font-size: 14px; font-weight: 500; text-decoration: none; border-radius: 3px; margin-top: 15px;" href="<?php echo e($actionURL); ?>" target="_blank"><?php echo e($emailContent['button_title']); ?></a>
                    </p>
                </div>
                <p style="color: #b7b7b7; font-size: 12px; margin-top: 20px; text-align: center;">©️ 2024 GiveNow | All rights reserved.</p>
            </td>
        </tr>
    </table>
</body>
</html>


<?php /**PATH E:\PHP_CNM\DAPM_PHP_T2\backend\resources\views/mail/verify-email.blade.php ENDPATH**/ ?>