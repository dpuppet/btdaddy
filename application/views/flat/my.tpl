<?php include('header.tpl')?>
<div class="container-fluid-full" xmlns="http://www.w3.org/1999/html">
    <div class="row-fluid">

        <?php include('menu.tpl');?>

        <!-- start: Content -->
        <div id="content" class="span10">
            <div class="page-header">
                <h1>欢迎来到BtDaddy比特币交易中心</h1>
            </div>

            <div class="row-fluid">

                <div class="span3 smallstat box mobileHalf noMargin" ontablet="span6" ondesktop="span3">
                    <i class="icon-download-alt green"></i>
                    <span class="title">人民币余额</span>
                    <span class="value">￥<?php echo $cny ?></span>
                </div>

                <div class="span3 smallstat mobileHalf box" ontablet="span6" ondesktop="span3">
                    <i class="icon-money yellow"></i>
                    <span class="title">比特币余额</span>
                    <span class="value"><?php echo $btc ?></span>
                </div>

                <div class="span3 smallstat box mobileHalf" ontablet="span6" ondesktop="span3">
                    <div class="boxchart-overlay blue">
                        <div class="boxchart">5,6,7,2,0,4,2,4,8,2,3,3,2</div>
                    </div>
                    <span class="title">已成交挂单</span>
                    <span class="value"><?php echo $transaction_num ?></span>
                </div>

                <div class="span3 smallstat box mobileHalf" ontablet="span6" ondesktop="span3">
                    <div class="boxchart-overlay red">
                        <div class="boxchart">1,2,6,4,0,8,2,4,5,3,1,7,5</div>
                    </div>
                    <span class="title">进行中挂单</span>
                    <span class="value"><?php echo $available_num ?></span>
                </div>
            </div><!--/row-->

            <div class="row-fluid">
                <div class="box span12">
                    <div class="box-header">
                        <h2><i class="icon-hand-up"></i>快捷入口</h2>
                    </div>
                    <div class="box-content">

                        <a class="quick-button span2" href="/market">
                            <i class="icon-bar-chart"></i>
                            <p>行情中心</p>

                        </a>
                        <a class="quick-button span2" href="/trade/bid">
                            <i class="icon-shopping-cart"></i>
                            <p>买入比特币</p>
                        </a>
                        <a class="quick-button span2" href="/trade/ask">
                            <i class="icon-external-link"></i>
                            <p>卖出比特币</p>
                        </a>
                        <a class="quick-button span2" href="/recharge/cny_method">
                            <i class="icon-credit-card"></i>
                            <p>人民币充值</p>
                            <span class="notification red">0手续费</span>
                        </a>
                        <a class="quick-button span2" href="/recharge/btc_method">
                            <i class="icon-money"></i>
                            <p>比特币充值</p>
                        </a>
                        <a class="quick-button span2" href="/safe/cash_password">
                            <i class="icon-lock"></i>
                            <p>资金密码</p>

                        </a>
                        <div class="clearfix"></div>
                    </div>
                </div><!--/span-->

            </div><!--/row-->
        </div>
        <!-- end: Content -->

    </div><!--/fluid-row-->



    <?php include('footer.tpl')?>

    <script src="/static/js/login_password.js"></script>
