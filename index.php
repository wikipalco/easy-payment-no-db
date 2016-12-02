<?php
include 'includes/class.php';
global $wpal;
include 'config.php';
?>
<!DOCTYPE html>
<html lang="fa-IR" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>ویکی پال - <?php echo $wpal->param('site_name') ?></title>
	<meta name="description" content="<?php echo $wpal->param('site_description') ?>">
	<meta property="og:locale" content=fa_IR/>
	<meta property="og:title" content="ویکی پال - <?php echo $wpal->param('site_name') ?>">
	<meta property="og:description" content="<?php echo $wpal->param('site_description') ?>">
	<meta property="og:url" content="<?php echo $wpal->return_url() ?>"/>
    <link href="assets/css/style.css" rel="stylesheet">
    <script type="text/javascript" src="assets/js/jquery-3.1.0.min.js"></script>
    <script type="text/javascript" src="assets/js/script.js"></script>
    <link href="assets/img/favicon.png" rel="shortcut icon">
</head>

<body>
<div class="container">

    <article id="big-content">
        <div class="big-real-content">
            <div class="widget">
                <div class="widget-center">
					<center>
						<div class="person-img"><img src="assets/img/logo.png" alt="<?php echo $wpal->param('site_name') ?>" title="<?php echo $wpal->param('site_name') ?>"></div>
					</center>
                    <div class="person-meta">
                        <span class="pay-to">پرداخت به :</span>
                        <h3 class="person-name"><span
                                class="icon-left-arrow"></span><?php echo $wpal->param('site_name') ?></h3>

                        <?php if ($wpal->param('site_description')) : ?>
                            <div class="person-de">
                                <p>
                                    <?php echo $wpal->param('site_description') ?>
									
									<div style="direction:ltr;">
										<?php if ($wpal->param('site_phone')) : ?>
											<?php echo $wpal->param('site_phone') ?>
										<?php endif ?>

										<?php if ($wpal->param('site_email')) : ?>
											<br><?php echo $wpal->param('site_email') ?>
										<?php endif ?>
									</div>

                                </p>
                            </div>
                        <?php endif; ?>

                        <div class="easy-payments-value">
                            <strong>
                                <span id="total-price"><?php echo is_numeric($wpal->fields['price']) ? $wpal->fields['price'] : $wpal->post('price','0') ?></span>  تومان
                            </strong>
                        </div>
                    </div>

                </div>
            </div>
            <?php
            $wpal->steps();
            ?>
        </div>
    </article>
	<center>
		<span style="color:#ffffff"><a href="https://wikipal.co/" target="_blank" style="color:#ffffff; font-size:11px; font-family: tahoma, arial;">پرداخت امن و سریع از طریق درگاه پرداخت الکترونیک ویکی پال</span></a>
	</center><br>
</div>
</body>
</html>
