<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:58:"D:\pyg\public/../application/home\view\per_center\aaa.html";i:1570602732;s:40:"D:\pyg\application\home\view\layout.html";i:1570526978;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />


    <link rel="stylesheet" type="text/css" href="/static/home/css/all.css" />


    <script type="text/javascript" src="/static/home/js/all.js"></script>

</head>

<body>
<!-- 头部栏位 -->
<!--页面顶部-->
<div id="nav-bottom">
    <!--顶部-->
    <div class="nav-top">
        <div class="top">
            <div class="py-container">
                <div class="shortcut">
                    <ul class="fl">
                        <li class="f-item">品优购欢迎您！</li>
                        <?php if((\think\Session::get('user_info')==null)): ?>
                        <li class="f-item">请<a href="<?php echo url('home/login/index'); ?>" target="_blank">登录</a>　<span><a href="<?php echo url('home/login/register'); ?>" target="_blank">免费注册</a></span></li>
                        <?php else: ?>
                        <li class="f-item">Hi:<a href="javacript::" target="_blank"><?php echo \think\Session::get('user_info.nickname'); ?></a>　<span><a id="logout" href="" target="_blank">退出</a> &nbsp</span><span><a href="<?php echo url('home/PerCenter/message'); ?>" target="_blank">个人中心</a></span></li>
                        <?php endif; ?>
                    </ul>
                    <ul class="fr">
                        <li class="f-item">我的订单</li>
                        <li class="f-item space"></li>
                        <li class="f-item"><a href="home.html" target="_blank">我的品优购</a></li>
                        <li class="f-item space"></li>
                        <li class="f-item">品优购会员</li>
                        <li class="f-item space"></li>
                        <li class="f-item">企业采购</li>
                        <li class="f-item space"></li>
                        <li class="f-item">关注品优购</li>
                        <li class="f-item space"></li>
                        <li class="f-item" id="service">
                            <span>客户服务</span>
                            <ul class="service">
                                <li><a href="cooperation.html" target="_blank">合作招商</a></li>
                                <li><a href="shoplogin.html" target="_blank">商家后台</a></li>
                            </ul>
                        </li>
                        <li class="f-item space"></li>
                        <li class="f-item">网站导航</li>
                    </ul>
                </div>
            </div>
        </div>

        <!--头部-->
        <div class="header">
            <div class="py-container">
                <div class="yui3-g Logo">
                    <div class="yui3-u Left logoArea">
                        <a class="logo-bd" title="品优购" href="JD-index.html" target="_blank"></a>
                    </div>
                    <div class="yui3-u Center searchArea">
                        <div class="search">
<!--                            <form action="" class="sui-form form-inline">-->
<!--                                &lt;!&ndash;searchAutoComplete&ndash;&gt;-->
<!--                                <div class="input-append">-->
<!--                                    <input type="text" id="autocomplete" type="text" class="input-error input-xxlarge" />-->
<!--                                    <button class="sui-btn btn-xlarge btn-danger" type="button">搜索</button>-->
<!--                                </div>-->
<!--                            </form>-->
                            <form action="<?php echo url('home/goods/index'); ?>" method="get" class="sui-form form-inline">
                                <!--searchAutoComplete-->
                                <div class="input-append">
                                    <input type="text" id="autocomplete" class="input-error input-xxlarge" name="keywords" value="<?php echo \think\Request::instance()->param('keywords'); ?>" />
                                    <button class="sui-btn btn-xlarge btn-danger" type="submit">搜索</button>
                                </div>
                            </form>
                        </div>
                        <div class="hotwords">
                            <ul>
                                <li class="f-item">品优购首发</li>
                                <li class="f-item">亿元优惠</li>
                                <li class="f-item">9.9元团购</li>
                                <li class="f-item">每满99减30</li>
                                <li class="f-item">亿元优惠</li>
                                <li class="f-item">9.9元团购</li>
                                <li class="f-item">办公用品</li>

                            </ul>
                        </div>
                    </div>
                    <div class="yui3-u Right shopArea">
                        <div class="fr shopcar">
                            <div class="show-shopcar" id="shopcar">
                                <span class="car"></span>
                                <a class="sui-btn btn-default btn-xlarge" href="cart.html" target="_blank">
                                    <span>我的购物车</span>
                                    <i class="shopnum">0</i>
                                </a>
                                <div class="clearfix shopcarlist" id="shopcarlist" style="display:none">
                                    <p>"啊哦，你的购物车还没有商品哦！"</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="yui3-g NavList">
                    <div class="all-sorts-list">
                        <div class="yui3-u Left all-sort">
                            <h4>全部商品分类</h4>
                        </div>
                        <div class="sort">
                            <div class="all-sort-list2">
                                <?php foreach($category as $v): ?>
                                <div class="item">
                                    <h3><a href="#"><?php echo $v['cate_name']; ?></a></h3>
                                    <div class="item-list clearfix">
                                        <div class="subitem">
                                            <?php foreach($v['son'] as $value): ?>
                                            <dl class="fore1">
                                                <dt><a href="#"><?php echo $value['cate_name']; ?></a></dt>
                                                <dd>
                                                    <?php foreach($value['son'] as $vv): ?>
                                                    <em><a href="<?php echo url('home/goods/index',['id'=>$vv['id']]); ?>"><?php echo $vv['cate_name']; ?></a></em>
                                                    <?php endforeach; ?>
                                                </dd>
                                            </dl>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="yui3-u Center navArea">
                        <ul class="nav">
                            <li class="f-item">服装城</li>
                            <li class="f-item">美妆馆</li>
                            <li class="f-item">品优超市</li>
                            <li class="f-item">全球购</li>
                            <li class="f-item">闪购</li>
                            <li class="f-item">团购</li>
                            <li class="f-item">有趣</li>
                            <li class="f-item"><a href="seckill-index.html" target="_blank">秒杀</a></li>
                        </ul>
                    </div>
                    <div class="yui3-u Right"></div>
                </div>

            </div>
        </div>
    </div>
</div>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<style type="text/css">
    .card {
        position: relative;
        margin-bottom: 24px;
        background-color: #fff;
        -webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
    }
    .step-progress {
        padding: 16px;
        background-color: #fafafa;
        margin: 0 auto;
    }
    .step-progress .step-slider {
        width: 100%;
    }
    .step-progress .step-slider .step-slider-item {
        width: 25%;
        height: 1px;
        position: relative;
        float: left;
        background-color: #e0e0e0;
    }
    .step-progress .step-slider .step-slider-item:after {
        content: "";
        width: 10px;
        height: 10px;
        position: absolute;
        top: -6px;
        right: 0;
        background-color: #fafafa;
        border: 1px solid #e0e0e0;
        border-radius: 50%;
        z-index: 2;
        transition: all 0.3s ease-out 0.5s;
        -webkit-transition: all 0.3s ease-out 0.5s;
    }
    .step-progress .step-slider .step-slider-item:before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 0%;
        height: 1px;
        background-color: #b0bec5;
        z-index: 1;
        -webkit-transition: all 0.5s ease-out;
    }
    .step-progress .step-slider .step-slider-item.active:before {
        width: 100%;
        background-color: #ff8f00;
    }
    .step-progress .step-slider .step-slider-item.active:after {
        border-color: #ff8f00;
    }
    .step-content {
        padding: 16px 0;
    }
    .step-content .step-content-foot {
        text-align: right;
    }
    .step-content .step-content-foot button {
        border: 0;
        padding: 8px 16px;
        background-color: #cfd8dc;
        font-size: 14px;
        border-radius: 3px;
        outline: 0;
    }
    .step-content .step-content-foot button:active {
        background-color: rgba(255, 255, 255, 0.2);
    }
    .step-content .step-content-foot button.out {
        display: none;
    }
    .step-content .step-content-foot button.disable {
        background-color: #eceff1;
    }
    .step-content .step-content-foot button.active {
        background-color: #00acc1;
        color: white;
    }
    .step-content .step-content-body {
        padding: 16px 0;
        text-align: center;
        font-size: 18px;
    }
    .step-content .step-content-body.out {
        display: none;
    }
</style>
<body>
<div class="card step-progress">
    <div class="step-slider">
        <div data-id="step1" class="step-slider-item"></div>
        <div data-id="step2" class="step-slider-item"></div>
        <div data-id="step3" class="step-slider-item"></div>
        <div data-id="step4" class="step-slider-item"></div>
    </div>
    <div class="step-content">
        <div id="step1" class="step-content-body">Step 1</div>
        <div id="step2" class="step-content-body out">Step 2</div>
        <div id="step3" class="step-content-body out">Step 3</div>
        <div id="step4" class="step-content-body out">Step 4</div>
        <div id="stepLast" class="step-content-body out">Completed</div>
        <div class="step-content-foot">
            <button type="button" class="active" name="prev">Prev</button>
            <button type="button" class="active" name="next">Next</button>
            <button type="button" class="active out" name="finish">Finish</button>
        </div>
    </div>
</div>
</body>
<script type="text/javascript" src="/static/home/js/pages/main.js"></script>
<script>
    $(function() {
        var step = 0;
        var stepItem = $('.step-progress .step-slider .step-slider-item');

        // Step Next
        $('.step-content .step-content-foot button[name="next"]').on('click', function() {
            var instance = $(this);
            if (stepItem.length - 1 < step) {
                return;
            }
            if (step == (stepItem.length - 2)) {
                instance.addClass('out');
                instance.siblings('button[name="finish"]').removeClass('out');
            }
            $(stepItem[step]).addClass('active');
            $('.step-content-body').addClass('out');
            $('#' + stepItem[step + 1].dataset.id).removeClass('out');
            step++;
        });

        // Step Last
        $('.step-content .step-content-foot button[name="finish"]').on('click', function() {
            if (step == stepItem.length) {
                return;
            }
            $(stepItem[stepItem.length - 1]).addClass('active');
            $('.step-content-body').addClass('out');
            $('#stepLast').removeClass('out');
        });

        // Step Previous
        $('.step-content .step-content-foot button[name="prev"]').on('click', function() {
            var instance = $(this);
            $(stepItem[step]).removeClass('active');
            if (step == (stepItem.length - 1)) {
                instance.siblings('button[name="next"]').removeClass('out');
                instance.siblings('button[name="finish"]').addClass('out');
            }
            $('.step-content-body').addClass('out');
            $('#' + stepItem[step].dataset.id).removeClass('out');
            if (step <= 0) {
                return;
            }
            step--;
        });
    });
</script>
</html>


<!-- 底部栏位 -->
<!--页面底部-->
<div class="clearfix footer">
    <div class="py-container">
        <div class="footlink">
            <div class="Mod-service">
                <ul class="Mod-Service-list">
                    <li class="grid-service-item intro  intro1">

                        <i class="serivce-item fl"></i>
                        <div class="service-text">
                            <h4>正品保障</h4>
                            <p>正品保障，提供发票</p>
                        </div>

                    </li>
                    <li class="grid-service-item  intro intro2">

                        <i class="serivce-item fl"></i>
                        <div class="service-text">
                            <h4>正品保障</h4>
                            <p>正品保障，提供发票</p>
                        </div>

                    </li>
                    <li class="grid-service-item intro  intro3">

                        <i class="serivce-item fl"></i>
                        <div class="service-text">
                            <h4>正品保障</h4>
                            <p>正品保障，提供发票</p>
                        </div>

                    </li>
                    <li class="grid-service-item  intro intro4">

                        <i class="serivce-item fl"></i>
                        <div class="service-text">
                            <h4>正品保障</h4>
                            <p>正品保障，提供发票</p>
                        </div>

                    </li>
                    <li class="grid-service-item intro intro5">

                        <i class="serivce-item fl"></i>
                        <div class="service-text">
                            <h4>正品保障</h4>
                            <p>正品保障，提供发票</p>
                        </div>

                    </li>
                </ul>
            </div>
            <div class="clearfix Mod-list">
                <div class="yui3-g">
                    <div class="yui3-u-1-6">
                        <h4>购物指南</h4>
                        <ul class="unstyled">
                            <li>购物流程</li>
                            <li>会员介绍</li>
                            <li>生活旅行/团购</li>
                            <li>常见问题</li>
                            <li>购物指南</li>
                        </ul>

                    </div>
                    <div class="yui3-u-1-6">
                        <h4>配送方式</h4>
                        <ul class="unstyled">
                            <li>上门自提</li>
                            <li>211限时达</li>
                            <li>配送服务查询</li>
                            <li>配送费收取标准</li>
                            <li>海外配送</li>
                        </ul>
                    </div>
                    <div class="yui3-u-1-6">
                        <h4>支付方式</h4>
                        <ul class="unstyled">
                            <li>货到付款</li>
                            <li>在线支付</li>
                            <li>分期付款</li>
                            <li>邮局汇款</li>
                            <li>公司转账</li>
                        </ul>
                    </div>
                    <div class="yui3-u-1-6">
                        <h4>售后服务</h4>
                        <ul class="unstyled">
                            <li>售后政策</li>
                            <li>价格保护</li>
                            <li>退款说明</li>
                            <li>返修/退换货</li>
                            <li>取消订单</li>
                        </ul>
                    </div>
                    <div class="yui3-u-1-6">
                        <h4>特色服务</h4>
                        <ul class="unstyled">
                            <li>夺宝岛</li>
                            <li>DIY装机</li>
                            <li>延保服务</li>
                            <li>品优购E卡</li>
                            <li>品优购通信</li>
                        </ul>
                    </div>
                    <div class="yui3-u-1-6">
                        <h4>帮助中心</h4>
                        <img src="/static/home/img/wx_cz.jpg">
                    </div>
                </div>
            </div>
            <div class="Mod-copyright">
                <ul class="helpLink">
                    <li>关于我们<span class="space"></span></li>
                    <li>联系我们<span class="space"></span></li>
                    <li>关于我们<span class="space"></span></li>
                    <li>商家入驻<span class="space"></span></li>
                    <li>营销中心<span class="space"></span></li>
                    <li>友情链接<span class="space"></span></li>
                    <li>关于我们<span class="space"></span></li>
                    <li>营销中心<span class="space"></span></li>
                    <li>友情链接<span class="space"></span></li>
                    <li>关于我们</li>
                </ul>
                <p>地址：北京市昌平区建材城西路金燕龙办公楼一层 邮编：100096 电话：400-618-4000 传真：010-82935100</p>
                <p>京ICP备08001421号京公网安备110108007702</p>
            </div>
        </div>
    </div>
</div>
<!--页面底部END-->
<!--侧栏面板开始-->
<div class="J-global-toolbar">
    <div class="toolbar-wrap J-wrap">
        <div class="toolbar">
            <div class="toolbar-panels J-panel">

                <!-- 购物车 -->
                <div style="visibility: hidden;" class="J-content toolbar-panel tbar-panel-cart toolbar-animate-out">
                    <h3 class="tbar-panel-header J-panel-header">
                        <a href="" class="title"><i></i><em class="title">购物车</em></a>
                        <span class="close-panel J-close" onclick="cartPanelView.tbar_panel_close('cart');" ></span>
                    </h3>
                    <div class="tbar-panel-main">
                        <div class="tbar-panel-content J-panel-content">
                            <div id="J-cart-tips" class="tbar-tipbox hide">
                                <div class="tip-inner">
                                    <span class="tip-text">还没有登录，登录后商品将被保存</span>
                                    <a href="#none" class="tip-btn J-login">登录</a>
                                </div>
                            </div>
                            <div id="J-cart-render">
                                <!-- 列表 -->
                                <div id="cart-list" class="tbar-cart-list">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- 小计 -->
                    <div id="cart-footer" class="tbar-panel-footer J-panel-footer">
                        <div class="tbar-checkout">
                            <div class="jtc-number"> <strong class="J-count" id="cart-number">0</strong>件商品 </div>
                            <div class="jtc-sum"> 共计：<strong class="J-total" id="cart-sum">¥0</strong> </div>
                            <a class="jtc-btn J-btn" href="#none" target="_blank">去购物车结算</a>
                        </div>
                    </div>
                </div>

                <!-- 我的关注 -->
                <div style="visibility: hidden;" data-name="follow" class="J-content toolbar-panel tbar-panel-follow">
                    <h3 class="tbar-panel-header J-panel-header">
                        <a href="#" target="_blank" class="title"> <i></i> <em class="title">我的关注</em> </a>
                        <span class="close-panel J-close" onclick="cartPanelView.tbar_panel_close('follow');"></span>
                    </h3>
                    <div class="tbar-panel-main">
                        <div class="tbar-panel-content J-panel-content">
                            <div class="tbar-tipbox2">
                                <div class="tip-inner"> <i class="i-loading"></i> </div>
                            </div>
                        </div>
                    </div>
                    <div class="tbar-panel-footer J-panel-footer"></div>
                </div>

                <!-- 我的足迹 -->
                <div style="visibility: hidden;" class="J-content toolbar-panel tbar-panel-history toolbar-animate-in">
                    <h3 class="tbar-panel-header J-panel-header">
                        <a href="#" target="_blank" class="title"> <i></i> <em class="title">我的足迹</em> </a>
                        <span class="close-panel J-close" onclick="cartPanelView.tbar_panel_close('history');"></span>
                    </h3>
                    <div class="tbar-panel-main">
                        <div class="tbar-panel-content J-panel-content">
                            <div class="jt-history-wrap">
                                <ul>
                                    <!--<li class="jth-item">
                                        <a href="#" class="img-wrap"> <img src="/static/home//static/home//static/home./portal/img/like_03.png" height="100" width="100" /> </a>
                                        <a class="add-cart-button" href="#" target="_blank">加入购物车</a>
                                        <a href="#" target="_blank" class="price">￥498.00</a>
                                    </li>
                                    <li class="jth-item">
                                        <a href="#" class="img-wrap"> <img src="/static/home//static/home//static/home/portal/img/like_02.png" height="100" width="100" /></a>
                                        <a class="add-cart-button" href="#" target="_blank">加入购物车</a>
                                        <a href="#" target="_blank" class="price">￥498.00</a>
                                    </li>-->
                                </ul>
                                <a href="#" class="history-bottom-more" target="_blank">查看更多足迹商品 &gt;&gt;</a>
                            </div>
                        </div>
                    </div>
                    <div class="tbar-panel-footer J-panel-footer"></div>
                </div>

            </div>

            <div class="toolbar-header"></div>

            <!-- 侧栏按钮 -->
            <div class="toolbar-tabs J-tab">
                <div onclick="cartPanelView.tabItemClick('cart')" class="toolbar-tab tbar-tab-cart" data="购物车" tag="cart" >
                    <i class="tab-ico"></i>
                    <em class="tab-text"></em>
                    <span class="tab-sub J-count " id="tab-sub-cart-count">0</span>
                </div>
                <div onclick="cartPanelView.tabItemClick('follow')" class="toolbar-tab tbar-tab-follow" data="我的关注" tag="follow" >
                    <i class="tab-ico"></i>
                    <em class="tab-text"></em>
                    <span class="tab-sub J-count hide">0</span>
                </div>
                <div onclick="cartPanelView.tabItemClick('history')" class="toolbar-tab tbar-tab-history" data="我的足迹" tag="history" >
                    <i class="tab-ico"></i>
                    <em class="tab-text"></em>
                    <span class="tab-sub J-count hide">0</span>
                </div>
            </div>

            <div class="toolbar-footer">
                <div class="toolbar-tab tbar-tab-top" > <a href="#"> <i class="tab-ico  "></i> <em class="footer-tab-text">顶部</em> </a> </div>
                <div class="toolbar-tab tbar-tab-feedback" > <a href="#" target="_blank"> <i class="tab-ico"></i> <em class="footer-tab-text ">反馈</em> </a> </div>
            </div>

            <div class="toolbar-mini"></div>

        </div>

        <div id="J-toolbar-load-hook"></div>

    </div>
</div>
<!--购物车单元格 模板-->
<script type="text/template" id="tbar-cart-item-template">
    <div class="tbar-cart-item" >
        <div class="jtc-item-promo">
            <em class="promo-tag promo-mz">满赠<i class="arrow"></i></em>
            <div class="promo-text">已购满600元，您可领赠品</div>
        </div>
        <div class="jtc-item-goods">
            <span class="p-img"><a href="#" target="_blank"><img src="{2}" alt="{1}" height="50" width="50" /></a></span>
            <div class="p-name">
                <a href="#">{1}</a>
            </div>
            <div class="p-price"><strong>¥{3}</strong>×{4} </div>
            <a href="#none" class="p-del J-del">删除</a>
        </div>
    </div>
</script>
<!--侧栏面板结束-->

</body>
<script>
    $(function (){
        $('#logout').click(function (){
            if(confirm('是否确定退出')){
                $('#logout').attr('href','<?php echo url('home/login/logout'); ?>')
            }else{
                return false;
            }
        })
    })
</script>
</html>