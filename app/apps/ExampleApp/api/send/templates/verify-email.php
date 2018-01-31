<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    </head>
    <body style="font-family: sans-serif;">
        <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
            <tr>
                <td align="center" valign="top">
                    <table border="0" cellpadding="20" cellspacing="0" width="600">
                        <tr>
                            <td align="left" valign="top">
                                <h1>
                                    Image Tracker - Email Verify
                                </h1>

                                <hr/>

                                <p>
                                    Hi, <?= $data->name_first; ?>, verify your email by following the link!
                                </p>

                                <p>
                                    <a href="<?= $data->data->verifyLink; ?>"><?= $data->data->verifyDisplay; ?></a>
                                </p>

                                <?php if (!empty($data->data->details)) : ?>

                                <hr/>

                                <table>
                                    <tbody>
                                        <?php foreach ($data->data->details as $key => $value) : ?>
                                        <tr>
                                            <td>
                                                <strong>
                                                    <?= $key; ?>
                                                </strong>
                                            </td>
                                            <td>
                                                <?= $key == 'URL' ? sprintf('<a href="%s">%s</a>', $value, $value) : $value; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>

                                <?php endif; ?>

                                <hr/>

                                <p>
                                    This mail is sent via sign up on <a href="<?= $data->siteUrl; ?>"><?= $data->siteName; ?></a><br/>
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
