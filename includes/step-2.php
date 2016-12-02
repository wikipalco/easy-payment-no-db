<style type="text/css">
    .big-real-content{
        min-height:550px;
    }
</style>
<div class="big-content-data">
    <strong class="big-content-data-title">پرداخت با ویکی پال</strong>
    <span class="big-content-data-details">لطفا قبل از پرداخت یکبار دیگر فیلدهای ارسالی را بررسی نمایید.</span>
    <div class="big-content-form">

        <form method="post">

            <?php foreach ($fields as $key => $value) {

                if ($key == 'price' && is_numeric($value)) {
                    continue;
                }

                if (!in_array($this->post($key), array('', '...'))) : ?>
                    <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $this->post($key) ?>">
                <?php endif; ?>

                <span class="person-name">
						<span class="icon-left-arrow"></span>
                    <?php echo $this->remove_star($value) ?>
			        </span>

                <span class="pay-to">
				        <?php echo $this->post($key) ?>
                    </span>


            <?php } ?>

            <button name="step-3" type="submit" class="content-form-submit pay-submit">نهایی کردن پرداخت</button>

            <button name="step-1" type="submit" class="content-form-submit register-submit guest-pau-submit">ویرایش و
                اصلاح
            </button>

        </form>

    </div>
</div>