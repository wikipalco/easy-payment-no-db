<style type="text/css">
    .person-meta {
        display: none;
    }
</style>
<div class="big-content-data">
    <strong class="big-content-data-title">پرداخت با ویکی پال</strong>
    <span class="big-content-data-details">پرداخت امن و آسان با استفاده از درگاه ویکی پال.</span>
    <div class="big-content-form">

        <?php if (!empty($alert['error'])) : ?>

            <div class="alert alert-danger">
                <?php printf('<p>پرداخت با خطا مواجه شد.<br>علت خطا : %s</p>', $alert['error']); ?>
            </div>

        <?php else : ?>

            <?php foreach ($fields as $key => $value) {

                $param = str_replace('-', '_', $key);

                if (!in_array($this->post($key), array('', '...')))
                    $this->{$param} = $this->post($key);

                if ($param == 'price' && is_numeric($value))
                    $this->{$param} = intval($value);
            }

            if ($this->param('phone') && !preg_match('/^09[0-9]{9}/i', $this->param('phone')))
                $this->description = $this->param('description') . ' - ' . $this->remove_star($fields['phone']) . ' : ' . $this->param('phone');

            $payment = $this->payment();

            if (!empty($payment['Payment_URL'])) { ?>

                <div class="alert alert-success" style="text-align: center">
                    <p><img src="assets/img/loading.gif"
                            alt="farapardakht"><br>در حال انتقال به درگاه پرداخت ...
                    </p>
                </div>

                <?php
                if (!headers_sent()) {
                    header("Refresh:2; url={$payment['Payment_URL']}", true, 303);
                    exit;
                } else {
                    echo '<script type="text/javascript">window.location="' . $payment['Payment_URL'] . '";</script>';
                    exit;
                }
            }

            if (!empty($payment['Message'])) { ?>
                <div class="alert alert-danger">
                    <?php printf('<p>پرداخت با خطا مواجه شد.<br>علت خطا : %s</p>', $payment['Message']); ?>
                </div>
            <?php } ?>

        <?php endif; ?>

    </div>
</div>