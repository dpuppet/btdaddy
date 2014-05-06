<?php include('header.tpl')?>
<div class="container-fluid-full" xmlns="http://www.w3.org/1999/html">
		<div class="row-fluid">
				
<?php include('menu.tpl');?>
						
			<!-- start: Content -->
			<div id="content" class="span10">
			

			<div class="row-fluid">
				<div class="box span5">
					<div class="box-header">
						<h2><i class="icon-money"></i>人民币充值</h2>
					</div>
					<div class="box-content">
						<form class="form-horizontal" />
							<fieldset>


							  <div class="control-group">
								<label class="control-label" for="appendedInput">充值金额</label>
								<div class="controls">
								  <div class="input-append">
                                    <span class="add-on">￥</span>
									<input id="amount" class="input-medium money" maxlength="10" type="text" value="100"/>
								  </div>
                                    <span class="help-inline">最小金额100元</span>
								</div>
							  </div>


                                <div class="control-group">
                                    <label class="control-label" for="appendedInput">支付方式</label>
                                    <div class="controls">
                                        <select>
                                            <option>网银支付</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">

                                    <div id="success-msg" class=" alert alert-success"><i class="icon-thumbs-up"></i> 推荐使用网银付款，及时到账，0手续费</div>
                                    <div id="error-msg" class=" alert alert-warning">目前暂停支付宝付款方式</div>
                                </div>

							</fieldset>
						</form>
                        <div class="form-actions">
                            <button type="submit" id="recharge_btn" class="btn btn-success btn-block btn-large">立 即 充 值</button>
                        </div>
					</div>
				</div><!--/span-->
                <div class="box span7">
                    <div class="box-header">
                        <h2>支持银行</h2>
                    </div>
                     <img style="height:120%;margin-top:40px;"src="/static/img/banks.png">
                </div>

			</div><!--/row-->
            <div class="row-fluid">
                <div class="box span12">
                    <div class="box-header">
                        <h2><i class="icon-align-justify"></i><span class="break"></span>充值成功记录</h2>
                        <div class="box-icon">
                            <a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
                        </div>
                    </div>
                    <div class="box-content">
                        <table class="table table-bordered table-striped table-condensed">
                            <thead>
                            <tr>
                                <th>充值流水单号</th>
                                <th>充值时间</th>
                                <th>充值金额</th>
                                <th>状态</th>
                                <th>备注</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php echo $recharge_list ?>
                            </tbody>
                        </table>
                    </div>
                </div><!--/span-->
            </div>
					
			</div>
			<!-- end: Content -->
				
				</div><!--/fluid-row-->



<?php include('footer.tpl')?>
    <script src="/static/js/recharge_cny.js"></script>

    <div class="modal fade" id="confirm_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">充值确认</h4>
                </div>
                <div class="modal-body">
                    <p id="confirm_text"></p>
                </div>
                <div class="modal-footer">

                    <form action="https://pay.ecpss.cn/sslpayment" method="post" id="pay_form" name="E_FORM">

                    </form>

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->