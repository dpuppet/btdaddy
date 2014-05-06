<?php include('header.tpl')?>
		<div class="container-fluid-full">
		<div class="row-fluid">
				
<?php include('menu.tpl');?>
						
			<!-- start: Content -->
			<div id="content" class="span10">

            <div class="row-fluid">
                <div class="box span12">
                    <div class="box-header">
                        <h2><i class="icon-align-justify"></i><span class="break"></span>买入委托记录</h2>
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
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php echo $bid_list ?>
                            </tbody>
                        </table>
                    </div>
                </div><!--/span-->
            </div>
            <div class="row-fluid">
                    <div class="box span12">
                        <div class="box-header">
                            <h2><i class="icon-align-justify"></i><span class="break"></span>卖出委托记录</h2>
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
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php echo $ask_list ?>
                                </tbody>
                            </table>
                        </div>
                    </div><!--/span-->
                </div>
			</div>
			<!-- end: Content -->
				
				</div><!--/fluid-row-->



<?php include('footer.tpl')?>
    <script src="/static/js/trade_manage.js"></script>

    <div class="modal fade" id="confirm_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">操作确认</h4>
                </div>
                <div class="modal-body">
                    <p>你确认取消此委托单么？</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary" id="confirm_btn">确认</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
