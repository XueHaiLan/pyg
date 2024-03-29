<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:54:"D:\pyg\public/../application/home\view\room\index.html";i:1570585000;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
    <title>直播</title>

    <link rel="stylesheet" type="text/css" href="/static/home/css/all.css" />

    <script type="text/javascript" src="/static/home/js/all.js"></script>


    <link rel="stylesheet" type="text/css" href="/static/home/css/pages-list.css" />
    <style>
        .main{
            margin:0;
        }
        .title{
            background-color: #b1191a;
            text-align: left;
            height: 60px;
            line-height: 60px;
            font-size: 30px;
            color:white;
        }
        .title span{
            margin-left:100px;
        }
        .rest {
            height: 300px;
            background: #051b28;
            line-height: 300px;
            font-size: 16px;
            color: #fff;
            text-align: center;
        }
        .live{

        }
        .main li a{
            display:block;
            padding:0 5px;
        }
    </style>
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
                        <?php if((\think\Session::get('user_info') == null)): ?>
                        <li class="f-item">请<a href="<?php echo url('home/login/login'); ?>" >登录</a>　<span><a href="<?php echo url('home/login/register'); ?>">免费注册</a></span></li>
                        <?php else: ?>
                        <li class="f-item">Hi,<a href="<?php echo url('home/member/index'); ?>" ><?php echo \think\Session::get('user_info.nickname'); ?></a>　<span><a href="<?php echo url('home/login/logout'); ?>">退出</a></span></li>
                        <?php endif; ?>


                    </ul>
                    <ul class="fr">
                        <li class="f-item">我的订单</li>
                        <li class="f-item space"></li>
                        <li class="f-item"><a href="javascript:;">我的品优购</a></li>
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
                                <li><a href="javascript:;">合作招商</a></li>
                                <li><a href="javascript:;">商家后台</a></li>
                            </ul>
                        </li>
                        <li class="f-item space"></li>
                        <li class="f-item">网站导航</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!--list-content-->
<div class="main">
    <div class="title"><span>品优购直播</span></div>
    <div class="py-container">
        <div class="live" style="overflow: hidden;">
            <div class="live-left fl" style="width:800px;">
                <div style="border: 1px solid #ddd;">
                    <ul style="height:40px;font-size:18px;background-color: #EAEAEA;border-bottom: 1px solid #ddd;">
                        <li class="fl" style="list-style:none;line-height: 40px;"><span>回放/直播</span><span>店铺实景直播</span></li>
                        <li class="fr" style="list-style:none;line-height: 40px;"><span> </span><span>手机看直播</span></li>
                    </ul>
                    <!--<div class="player" style="height:600px;">-->

                    <!--<video src="" controls="controls" width="500" height="600" style="margin:0 150px"></video>-->
                    <!--</div>-->
                    <div class="player video" style="height:600px;">
                    </div>
                </div>
            </div>
            <div class="live-right fr" style="width:398px;">
                <div style="border: 1px solid #ddd;">
                    <ul style="height:40px;font-size:18px;background-color: #EAEAEA;border-bottom: 1px solid #ddd;">
                        <li class="fl" style="list-style:none;line-height: 40px;"><span></span><span>全部宝贝</span></li>
                    </ul>
                    <div class="goods" style="height:600px;">
                        <table width="100%" style="">
                            <?php foreach($live['goods'] as $v): ?>
                            <tr>
                                <td width="40%"><a href="<?php echo url('home/goods/detail', ['id' => $v['goods_id']]); ?>"><img src="<?php echo $v['goods_logo']; ?>"></a></td>
                                <td width="40%"><a href="<?php echo url('home/goods/detail', ['id' => $v['goods_id']]); ?>"><?php echo $v['goods_name']; ?></a></td>
                                <td width=""><?php echo $v['goods_price']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>

                </div>

            </div>
        </div>
        <div class="replay">
            <h3>TA的回放</h3>
            <ul class="yui3-g">
                <li class="yui3-u-1-4"><a href=""><img src="/static/home/img/live1.webp"></a></li>
                <li class="yui3-u-1-4"><a href=""><img src="/static/home/img/live1.webp"></a></li>
                <li class="yui3-u-1-4"><a href=""><img src="/static/home/img/live1.webp"></a></li>
                <li class="yui3-u-1-4"><a href=""><img src="/static/home/img/live1.webp"></a></li>
            </ul>
        </div>
        <div class="more">
            <h3>更多直播</h3>
            <ul class="yui3-g">
                <li class="yui3-u-1-4"><a href=""><img src="/static/home/img/live1.webp"></a></li>
                <li class="yui3-u-1-4"><a href=""><img src="/static/home/img/live1.webp"></a></li>
                <li class="yui3-u-1-4"><a href=""><img src="/static/home/img/live1.webp"></a></li>
                <li class="yui3-u-1-4"><a href=""><img src="/static/home/img/live1.webp"></a></li>
            </ul>
        </div>
    </div>
</div>

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
                        <a href="<?php echo url('home/cart/index'); ?>" class="title"><i></i><em class="title">购物车</em></a>
                        <span class="close-panel J-close" onclick="cartPanelView.tbar_panel_close('cart');" ></span>
                    </h3>
                    <div class="tbar-panel-main">
                        <div class="tbar-panel-content J-panel-content">
                            <div id="J-cart-tips" class="tbar-tipbox hide">
                                <div class="tip-inner">
                                    <span class="tip-text">还没有登录，登录后商品将被保存</span>
                                    <a href="<?php echo url('home/login/login'); ?>" class="tip-btn J-login">登录</a>
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
                            <a class="jtc-btn J-btn" href="<?php echo url('home/cart/index'); ?>">去购物车结算</a>
                        </div>
                    </div>
                </div>

                <!-- 我的关注 -->
                <div style="visibility: hidden;" data-name="follow" class="J-content toolbar-panel tbar-panel-follow">
                    <h3 class="tbar-panel-header J-panel-header">
                        <a href="javascript:;" class="title"> <i></i> <em class="title">我的关注</em> </a>
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
                        <a href="javascript:;" class="title"> <i></i> <em class="title">我的足迹</em> </a>
                        <span class="close-panel J-close" onclick="cartPanelView.tbar_panel_close('history');"></span>
                    </h3>
                    <div class="tbar-panel-main">
                        <div class="tbar-panel-content J-panel-content">
                            <div class="jt-history-wrap">
                                <ul>
                                    <!--<li class="jth-item">
                                        <a href="javascript:;" class="img-wrap"> <img src="../../.../portal/img/like_03.png" height="100" width="100" /> </a>
                                        <a class="add-cart-button" href="javascript:;" target="_blank">加入购物车</a>
                                        <a href="javascript:;" target="_blank" class="price">￥498.00</a>
                                    </li>
                                    <li class="jth-item">
                                        <a href="javascript:;" class="img-wrap"> <img src="../../../portal/img/like_02.png" height="100" width="100" /></a>
                                        <a class="add-cart-button" href="javascript:;" target="_blank">加入购物车</a>
                                        <a href="javascript:;" target="_blank" class="price">￥498.00</a>
                                    </li>-->
                                </ul>
                                <a href="javascript:;" class="history-bottom-more">查看更多足迹商品 &gt;&gt;</a>
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
                <div class="toolbar-tab tbar-tab-top" > <a href="javascript:;"> <i class="tab-ico  "></i> <em class="footer-tab-text">顶部</em> </a> </div>
                <div class="toolbar-tab tbar-tab-feedback" > <a href="javascript:;"> <i class="tab-ico"></i> <em class="footer-tab-text ">反馈</em> </a> </div>
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
            <span class="p-img"><a href="javascript:;"><img src="{2}" alt="{1}" height="50" width="50" /></a></span>
            <div class="p-name">
                <a href="javascript:;">{1}</a>
            </div>
            <div class="p-price"><strong>¥{3}</strong>×{4} </div>
            <a href="javascript:;" class="p-del J-del">删除</a>
        </div>
    </div>
</script>
<!--侧栏面板结束-->
<script type="text/javascript" src="/static/home/js/plugins/ckplayer/ckplayer.js"></script>
<script type="text/javascript">
    $(function(){
        var videoObject = {
            container: '.video',//“#”代表容器的ID，“.”或“”代表容器的class
            variable: 'player',//该属性必需设置，值等于下面的new chplayer()的对象
            loaded:'loadedHandler',//监听播放器加载成功
            video:'<?php echo $live['live_url']; ?>'//视频地址
        };
        var player=new ckplayer(videoObject);
        function loadedHandler(){
            //播放器加载后会调用该函数
            player.addListener('time', timeHandler); //监听播放时间,addListener是监听函数，需要传递二个参数，'time'是监听属性，这里是监听时间，timeHandler是监听接受的函数
        }
        function timeHandler(t){
            console.log('当前播放的时间：'+t);
        }
    })

</script>
</body>

</html>