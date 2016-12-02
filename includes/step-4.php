<style type="text/css">
    .person-meta {
        display: none;
    }
</style>
<div class="big-content-data">
    <strong class="big-content-data-title">پرداخت با ویکی پال</strong>
    <span
        class="big-content-data-details">وضعیت پرداخت در زیر مشخص شده است. لطفا موارد مورد نیاز را یادداشت نمایید.</span>
    <div class="big-content-form">

        <?php

        $verify = $this->verify();

        if ($verify['Status'] == 'completed') { ?>

            <div class="alert alert-success" style="text-align: center">
                <p> پرداخت با موفقیت انجام شد</p>
            </div>

        <?php } else { ?>

            <div class="alert alert-danger">
                <?php printf('<p>پرداخت با خطا مواجه شد.<br>علت خطا : %s</p>', $verify['Message']); ?>
            </div>

        <?php } ?>

        <?php if (!empty($verify['Token'])) { ?>
            <span class="person-name">
						<span class="icon-left-arrow"></span>
                <p>کد پیگیری</p>
			        </span>
            <span class="pay-to center">
				        <?php echo $verify['Token'] ?>
                    </span>

        <?php } ?>

    </div>
</div>