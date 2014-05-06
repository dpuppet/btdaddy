<?php include('header.tpl')?>
<div class="container-fluid-full" xmlns="http://www.w3.org/1999/html">
		<div class="row-fluid">
				
<?php include('menu.tpl');?>
						
			<!-- start: Content -->
			<div id="content" class="span10">
			

			<div class="row-fluid">
				<div class="box span7">
					<div class="box-header">
						<h2><i class="icon-money"></i>人民币提现</h2>
					</div>
					<div class="box-content">
						<form class="form-horizontal" />
							<fieldset>
                                <div class="control-group">
                                    <label class="control-label" for="appendedInput">可用余额</label>
                                    <div class="controls">
                                        <div class="input-append">
                                            <button id="balance" class="btn btn-info" type="button">￥<?php echo $cny; ?></button>
                                        </div>
                                        <span class="help-inline alert-success">每日最高额度50万元</span>
                                    </div>
                                </div>

							  <div class="control-group">
								<label class="control-label" for="appendedInput">提现金额</label>
								<div class="controls">
								  <div class="input-append">
                                    <span class="add-on">￥</span>
									<input id="amount" class="input-medium number" maxlength="10" type="text" value="100"/>
								  </div>
                                    <span class="help-inline alert-success">最小金额100元，手续费0.5%+5元每笔提现</span>
								</div>
							  </div>


                                <div class="control-group">
                                    <label class="control-label" for="appendedInput">账户银行</label>
                                    <div class="controls">
                                        <select id="method">
                                            <option value="中国银行">中国银行</option>
                                            <option value="工商银行">工商银行</option>
                                            <option value="农业银行">农业银行</option>
                                            <option value="交通银行">交通银行</option>
                                            <option value="建设银行">建设银行</option>
                                            <option value="招商银行">招商银行</option>
                                            <option value="中国民生银行">中国民生银行</option>
                                            <option value="中信银行">中信银行</option>
                                            <option value="华夏银行">华夏银行</option>
                                            <option value="中国光大银行">中国光大银行</option>
                                            <option value="北京银行">北京银行</option>
                                            <option value="上海银行">上海银行</option>
                                            <option value="天津银行">天津银行</option>
                                            <option value="大连银行">大连银行</option>
                                            <option value="杭州银行">杭州银行</option>
                                            <option value="宁波银行">宁波银行</option>
                                            <option value="厦门银行">厦门银行</option>
                                            <option value="广州银行">广州银行</option>
                                            <option value="平安银行">平安银行</option>
                                            <option value="浙商银行">浙商银行</option>
                                            <option value="上海农村商业银行">上海农村商业银行</option>
                                            <option value="重庆银行">重庆银行</option>
                                            <option value="中国邮政储蓄银行">中国邮政储蓄银行</option>
                                            <option value="江苏银行">江苏银行</option>
                                            <option value="兴业银行">兴业银行</option>
                                            <option value="广东发展银行">广东发展银行</option>
                                            <option value="上海浦东发展银行">上海浦东发展银行</option>
                                            <option value="浙江泰隆商业银行">浙江泰隆商业银行</option>
                                            <option value="广东发展银行">广东发展银行</option>
                                            <option value="深圳发展银行">深圳发展银行</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">卡号</label>
                                    <div class="controls">
                                        <input id="card_no" class="input-large number" type="text"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">开户人姓名</label>
                                    <div class="controls">
                                            <input id="card_name" class="input-large"  type="text"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">开户行详细名称</label>
                                    <div class="controls">
                                        <input id="card_addr" class="input-large"  type="text"/>
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
                            <button type="submit" id="withdraw_btn" class="btn btn-success btn-block btn-large">提交提现申请</button>
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
                            <dd>每日最高提现额度为50万元，如有更大提现额度需求，系统会分为若干天完成提现操作</dd>
                            <dt>拒绝网络洗钱</dt>
                            <dd>BtDaddy是中国最大的合法比特币交易平台，严禁使用BtDaddy平台进行任何洗钱行为，请大家自觉遵守国家相关法律法规</dd>
                        </dl>
                    </div>
                </div>

			</div><!--/row-->
            <div class="row-fluid">
                <div class="box span12">
                    <div class="box-header">
                        <h2><i class="icon-align-justify"></i><span class="break"></span>提现记录</h2>
                        <div class="box-icon">
                            <a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
                        </div>
                    </div>
                    <div class="box-content">
                        <table class="table table-bordered table-striped table-condensed">
                            <thead>
                            <tr>
                                <th>流水号</th>
                                <th>提现账号</th>
                                <th>提现方式</th>
                                <th>提现金额</th>
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

    <script src="/static/js/withdraw_cny.js"></script>

    <div class="modal fade" id="confirm_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">提现信息确认</h4>
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