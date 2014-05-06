<?php include('header.tpl')?>
<div class="container-fluid-full">
    <div class="row-fluid">

        <?php include('menu.tpl');?>

        <!-- start: Content -->
        <div id="content" class="span10">
            <div class="row-fluid">
                <div class="box span9">
                    <div class="box-header">
                        <h2><i class="icon-th"></i>比特币行情</h2>
                    </div>
                    <div class="box-content">
                        <ul class="nav tab-menu nav-tabs" id="myTab">
                            <li ><a href="#k-line-container">24小时</a></li>
                            <li ><a href="#day-container" onclick="dataKLine(2,'#day-container');">日线</a></li>


                        </ul>

                        <div id="myTabContent" class="tab-content">
                            <div class="tab-pane " id="k-line-container" >
                            </div>
                            <div class="tab-pane" id="day-container">
                            </div>
                        </div>
                    </div>
                </div>


                <!--最新价格模板-->
                <div class="span3 multi-stat-box box">
                    <div class="header row-fluid">
                        <div class="left">
                            <h2>实时价格</h2>
                            <a class="icon-chevron-down"></a>
                        </div>
                    </div>
                    <div class="content row-fluid">
                        <div class="left">
                            <ul>
                                <li class="active">
                                    <span class="date">最新成交</span>
                                    <span class="value" id="newest_deal_price">加载中</span>
                                </li>
                                <li>
                                    <span class="date">买一</span>
                                    <span class="value" id="bid1">加载中</span>
                                </li>
                                <li>
                                    <span class="date">卖一</span>
                                    <span class="value" id="ask1">加载中</span>
                                </li>
                                <li>
                                    <span class="date">最高价</span>
                                    <span class="value" id="highest_price">加载中</span>
                                </li>
                                <li>
                                    <span class="date">最低价</span>
                                    <span class="value" id="lowest_price">加载中</span>
                                </li>
                                <li>
                                    <span class="date">24小时成交量</span>
                                    <span class="value" id="today_amount">加载中</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row-fluid">
                <div class="box span4">
                    <div class="box-header">
                        <h2><i class="icon-shopping-cart"></i><span class="break"></span>最新买入委托单</h2>
                    </div>
                    <div class="box-content">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>价格</th>
                                <th>数量</th>
                                <th>类型</th>
                            </tr>
                            </thead>
                            <tbody id = "new_bid_orders">
                            <tr>
                                <td>加载中...</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div><!--/span-->

                <!--卖出委托单-->
                <div class="box span4">
                    <div class="box-header">
                        <h2><i class="icon-shopping-cart"></i><span class="break"></span>最新卖出委托单</h2>
                    </div>
                    <div class="box-content">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>价格</th>
                                <th>数量</th>
                                <th>类型</th>
                            </tr>
                            </thead>
                            <tbody id = "new_ask_orders">
                            <tr>
                                <td>加载中...</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div><!--/span-->

                <!--最新成交记录-->
                <div class="box span4">
                    <div class="box-header">
                        <h2><i class="icon-shopping-cart"></i><span class="break"></span>最新成交记录</h2>
                    </div>
                    <div class="box-content">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>成交价格</th>
                                <th>数量</th>
                                <th>类型</th>
                                <th>时间</th>
                            </tr>
                            </thead>
                            <tbody id="new_deals">
                            <tr>
                                <td>加载中...</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div><!--/span-->
            </div>
        </div>
        <!-- end: Content -->

    </div><!--/fluid-row-->



    <?php include('footer.tpl')?>
    <script src="/static/js/highstock.js"></script>
    <script src="/static/js/dashboard_theme.js"></script>
    <script src="/static/js/dashboard.js"></script>