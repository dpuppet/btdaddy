<?php include('header.tpl')?>
<div class="container-fluid-full">
    <div class="row-fluid">

        <?php include('menu.tpl');?>

        <!-- start: Content -->
        <div id="content" class="span10">


            <div class="row-fluid">
                <div class="box span5">
                    <div class="box-header">
                        <h2><i class="icon-shopping-cart"></i>卖出委托</h2>
                    </div>
                    <div class="box-content">
                        <form class="form-horizontal" />
                        <fieldset>

                            <div class="control-group">
                                <label class="col-sm-5 control-label" >最佳卖价</label>
                                <div class="controls">
                                    <div class="input-prepend">
                                        <button id="best_bid_price" class="btn btn-success btn-medium" type="button"><?php echo $best_price;?></button>
                                    </div>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="appendedInput">比特币余额</label>
                                <div class="controls">
                                    <div class="input-append">
                                        <button id="balance" class="btn btn-info" type="button"><?php echo $btc; ?></button>
                                        <a href="/recharge/btc_method"><label class="btn">充值</label></a>
                                    </div>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="appendedInput">卖出价格</label>
                                <div class="controls">
                                    <div class="input-append">
                                        <span class="add-on">￥</span>
                                        <input id="bid_price" class="input-medium money" maxlength="10" type="text" value="<?php echo $best_price;?>"/>
                                    </div>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">卖出数量</label>
                                <div class="controls">
                                    <input id="bid_amount" class="input-medium" maxlength="10" type="text" value="0" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="appendedInput">卖出金额</label>
                                <div class="controls">
                                    <div class="input-append">
                                        <span class="add-on">￥</span>
                                        <input id="bid_cost" class="input-medium money" maxlength="10" type="text" value="0"/>
                                    </div>
                                </div>
                            </div>
                            <div class="control-group">
                                <div id="error-msg" class=" alert alert-danger hide"></div>
                                <div id="success-msg" class=" alert alert-success hide"></div>
                            </div>

                        </fieldset>
                        </form>
                        <div class="form-actions">
                            <button type="submit" id="bid_btn" class="btn btn-danger btn-block btn-large">卖出(BTC->CNY)</button>
                        </div>
                    </div>
                </div><!--/span-->

                <!--市场深度-->
                <div class="box span4">
                    <div class="box-header">
                        <h2><i class="icon-shopping-cart"></i><span class="break"></span>市场深度</h2>
                    </div>
                    <div class="box-content">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>数量</th>
                                <th>价格</th>
                                <th>类型</th>
                            </tr>
                            </thead>
                            <tbody id = "market_depth">
                            <?php echo $depth; ?>
                            </tbody>
                        </table>
                    </div>
                </div><!--/市场深度-->

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


            </div><!--/row-->
            <div class="row-fluid">
                <div class="box span12">
                    <div class="box-header">
                        <h2><i class="icon-align-justify"></i><span class="break"></span>卖出委托记录[最近10条]</h2>
                        <div class="box-icon">
                            <a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
                        </div>
                    </div>
                    <div class="box-content">
                        <table class="table table-bordered table-striped table-condensed">
                            <thead>
                            <tr>
                                <th>委托时间</th>
                                <th>价格</th>
                                <th>数量</th>
                                <th>已成交数量</th>
                                <th>状态</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php echo $order_list ?>
                            </tbody>
                        </table>
                    </div>
                </div><!--/span-->
            </div>

        </div>
        <!-- end: Content -->

    </div><!--/fluid-row-->



    <?php include('footer.tpl')?>
    <script src="/static/js/trade_ask.js"></script>
    <div class="modal fade" id="confirm_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">卖出确认</h4>
                </div>
                <div class="modal-body">
                    <p id="confirm_text"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary" id="confirm_btn">确认</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->