<style type="text/css">
    .big-real-content{
        min-height:550px;
    }
</style>
<div class="big-content-data">
    <strong class="big-content-data-title">پرداخت با ویکی پال</strong>
    <span class="big-content-data-details">لطفا برای تکمیل خرید خود موارد زیر را به دقت پر نمایید.</span>
    <div class="big-content-form">

        <?php if (!empty($alert)) { ?>
            <div class="alert alert-danger">
                <?php echo implode('<br>', array_unique($alert)) ?>
            </div>
        <?php } ?>

        <form method="post">

            <?php foreach ($fields as $key => $value) { ?>

                <?php
                $onkeyup = '';
                if ($key == 'price') {
                    if (is_numeric($value))
                        continue;
                    else
                        $onkeyup = 'onkeyup="UpdatePrice(this);"';
                }
                ?>

                <input name="<?php echo $key ?>"
                       class="big-content-personal-username<?php echo isset($error[$key]['message']) ? ' has-error' : '' ?>"
                       type="text"
                       value="<?php echo !empty($_POST[$key]) ? $this->post($key) : '' ?>"
                       placeholder="<?php echo $this->remove_star($value) ?>" <?php echo $onkeyup ?>>
            <?php } ?>

            <button name="step-2" type="submit" class="content-form-submit pay-submit">پرداخت</button>
        </form>
    </div>
</div>
