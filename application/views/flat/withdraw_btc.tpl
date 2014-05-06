<?php include('header.tpl')?>
<div class="container-fluid-full" xmlns="http://www.w3.org/1999/html">
		<div class="row-fluid">
				
<?php include('menu.tpl');?>
						
			<!-- start: Content -->
			<div id="content" class="span10">
			

			<div class="row-fluid">
				<div class="box span7">
					<div class="box-header">
						<h2><i class="icon-money"></i>比特币提取</h2>
					</div>
					<div class="box-content">
						<form class="form-horizontal" />
							<fieldset>
                                <div class="control-group">
                                    <label class="control-label" for="appendedInput">可用余额</label>
                                    <div class="controls">
                                        <div class="input-append">
                                            <button id="balance" class="btn btn-info" type="button">฿ <?php echo $btc; ?></button>
                                        </div>

                                    </div>
                                </div>

							  <div class="control-group">
								<label class="control-label" for="appendedInput">提现数量</label>
								<div class="controls">
								  <div class="input-append">
                                    <span class="add-on">฿</span>
									<input id="amount" class="input-medium number" maxlength="10" type="text" value="0.01"/>
								  </div>
                                    <span class="help-inline alert-success">最小金额0.01，手续费0.0005比特币</span>
								</div>
							  </div>


                                <div class="control-group">
                                    <label class="control-label">钱包地址</label>
                                    <div class="controls">
                                        <input id="btc_address" class="input-large" type="text"/>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">现金密码</label>
                                    <div class="controls">
                                        <input id="cash_password" class="input-large"  type="password"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                <label class="control-label">验证码</label>
                                <div class="controls">
                                    <input autocomplete="off" id="code" type="text" />
                                    <div><a href="javascript:refresh_code()"><img id='validate_code' src="/validate_code"/></a></div>
                                </div>
                                <div class="control-group">

                                    <div id="error-msg" class=" alert alert-warning hide"></div>
                                </div>

							</fieldset>
						</form>
                        <div class="form-actions">
                            <button type="submit" id="withdraw_btn" class="btn btn-success btn-block btn-large">提交提取申请</button>
                        </div>
					</div>
				</div><!--/span-->
                <div class="box span5">
                    <div class="box-header">
                        <h2><i class="icon-rss"></i>重要提示</h2>
                    </div>
                    <div class="box-content">
                        <dl>
                            <dt>请妥善保管现金密码</dt>
                            <dd>现金密码是提取现金和比特币的重要凭证，<span class="inline-helper alert-danger">BtDaddy不会在任何情况下向你索取现金密码</span>，请不要向任何人透露你的现金密码。</dd>
                            <dt>提现限额</dt>
                            <dd>每日最高提现额度为100比特币，如有更大提现额度需求，系统会分为若干天完成提现操作</dd>
                            <dt>拒绝网络洗钱</dt>
                            <dd>BtDaddy是中国最大的合法比特币交易平台，严禁使用BtDaddy平台进行任何洗钱行为，请大家自觉遵守国家相关法律法规</dd>
                        </dl>
                    </div>
                </div>

			</div><!--/row-->
            <div class="row-fluid">
                <div class="box span12">
                    <div class="box-header">
                        <h2><i class="icon-align-justify"></i><span class="break"></span>提取记录</h2>
                        <div class="box-icon">
                            <a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
                        </div>
                    </div>
                    <div class="box-content">
                        <table class="table table-bordered table-striped table-condensed">
                            <thead>
                            <tr>
                                <th>流水号</th>
                                <th>提取地址</th>
                                <th>提现数量</th>
                                <th>手续费</th>
                                <th>状态</th>
                                <th>备注</th>
                                <th>时间</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php echo $withdraw_list ?>
                            </tbody>
                        </table>
                    </div>
                </div><!--/span-->
            </div>
					
			</div>
			<!-- end: Content -->
				
				</div><!--/fluid-row-->



<?php include('footer.tpl')?>

    <script src="/static/js/withdraw_btc.js"></script>

    <div class="modal fade" id="confirm_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">提现比特币信息确认</h4>
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