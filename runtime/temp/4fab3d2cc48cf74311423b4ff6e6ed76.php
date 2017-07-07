<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:62:"D:\wamp64\www\sdb/application/index\view\market\fullkline.html";i:1499392827;}*/ ?>
<!DOCTYPE html>

<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="__PUBLIC__/home/fullScreenKline.css" rel="stylesheet" type="text/css">
</head>
<body>

<!-- Chart Container -->
<div id="chart_container" class="dark" style="width: 880px; height: 500px; visibility: visible;">

    <!-- Dom Element Cache -->
    <div id="chart_dom_elem_cache"></div>

    <!-- ToolBar -->
    <div id="chart_toolbar" style="left: 0px; top: 0px; width: 880px; height: 29px;">
        <div class="chart_toolbar_minisep"> </div>

        <!-- Symbol Selector -->
        <div class="chart_dropdown" id="chart_dropdown_symbols" style="display:none;">
            <div class="chart_dropdown_t"><a><span class="chart_str_btc_this_week">当周 BTC</span><span></span></a></div>
            <div class="chart_dropdown_data">
                <table><tbody><tr>
                    <td>BTC</td>
                    <td>
                        <ul id="chart_symbols_btc">
                            <li><a><span class="chart_str_btc_this_week">当周 BTC</span><span></span></a></li>
                            <li><a><span class="chart_str_btc_next_week">次周 BTC</span><span></span></a></li>
                            <li><a><span class="chart_str_btc_month">全月 BTC</span><span></span></a></li>
                            <li><a><span class="chart_str_btc_quarter">季度 BTC</span><span></span></a></li>
                        </ul>
                    </td>
                </tr><tr>
                    <td>LTC</td>
                    <td>
                        <ul id="chart_symbols_ltc">
                            <li><a><span class="chart_str_ltc_this_week">当周 LTC</span><span></span></a></li>
                            <li><a><span class="chart_str_ltc_next_week">次周 LTC</span><span></span></a></li>
                            <li><a><span class="chart_str_ltc_month">全月 LTC</span><span></span></a></li>
                            <li><a><span class="chart_str_ltc_quarter">季度 LTC</span><span></span></a></li>
                        </ul>
                    </td>
                </tr></tbody></table>
            </div>
        </div>
        <div class="chart_dropdown kline_logo_black" id="kline_logo" style=" width: 43px;height: 10px; margin:9.5px 0px 0px 3px;">&nbsp;</div>
        <!-- Periods -->
        <div class="chart_dropdown" id="chart_toolbar_periods_vert">
            <div class="chart_dropdown_t"><a class="chart_str_period">周期</a></div>
            <div class="chart_dropdown_data">
                <table><tbody><tr>
                    <td>
                        <ul>
                            <li id="chart_period_line_v" name="line"><a class="chart_str_period_line">分时</a></li>
                            <li id="chart_period_1m_v" name="1m">  <a class="chart_str_period_1m">1分钟</a></li>
                            <li id="chart_period_3m_v" name="3m">  <a class="chart_str_period_3m">3分钟</a></li>
                            <li id="chart_period_5m_v" name="5m">  <a class="chart_str_period_5m">5分钟</a></li>
                            <li id="chart_period_15m_v" name="15m"> <a class="chart_str_period_15m selected">15分钟</a></li>
                            <li id="chart_period_30m_v" name="30m"> <a class="chart_str_period_30m">30分钟</a></li>
                            <li id="chart_period_1h_v" name="1h">  <a class="chart_str_period_1h">1小时</a></li>
                            <li id="chart_period_2h_v" name="2h">  <a class="chart_str_period_2h">2小时</a></li>
                            <li id="chart_period_4h_v" name="4h">  <a class="chart_str_period_4h">4小时</a></li>
                            <li id="chart_period_6h_v" name="6h">  <a class="chart_str_period_6h">6小时</a></li>
                            <li id="chart_period_12h_v" name="12h"> <a class="chart_str_period_12h">12小时</a></li>
                            <li id="chart_period_1d_v" name="1d">  <a class="chart_str_period_1d">日线</a></li>
                            <li id="chart_period_1w_v" name="1w">  <a class="chart_str_period_1w">周线</a></li>
                        </ul>
                    </td>
                </tr></tbody></table>
            </div>
        </div><div id="chart_toolbar_periods_horz">
            <ul class="chart_toolbar_tabgroup" style="padding-left:5px; padding-right:11px;">
                <li id="chart_period_line_h" name="line" style="display: inline-block;"><a class="chart_str_period_line">分时</a></li>
                <li id="chart_period_1m_h" name="1m" style="display: inline-block;">  <a class="chart_str_period_1m">1分钟</a></li>
                <li id="chart_period_3m_h" name="3m" style="display: inline-block;">  <a class="chart_str_period_3m">3分钟</a></li>
                <li id="chart_period_5m_h" name="5m" style="display: none;">  <a class="chart_str_period_5m">5分钟</a></li>
                <li id="chart_period_15m_h" name="15m" style="display: inline-block;"> <a class="chart_str_period_15m selected">15分钟</a></li>
                <li id="chart_period_30m_h" name="30m" style="display: inline-block;"> <a class="chart_str_period_30m">30分钟</a></li>
                <li id="chart_period_1h_h" name="1h" style="display: inline-block;">  <a class="chart_str_period_1h">1小时</a></li>
                <li id="chart_period_2h_h" name="2h" style="display: none;">  <a class="chart_str_period_2h">2小时</a></li>
                <li id="chart_period_4h_h" name="4h" style="display: none;">  <a class="chart_str_period_4h">4小时</a></li>
                <li id="chart_period_6h_h" name="6h" style="display: none;">  <a class="chart_str_period_6h">6小时</a></li>
                <li id="chart_period_12h_h" name="12h" style="display: none;"> <a class="chart_str_period_12h">12小时</a></li>
                <li id="chart_period_1d_h" name="1d" style="display: inline-block;">  <a class="chart_str_period_1d">日线</a></li>
                <li id="chart_period_1w_h" name="1w" style="display: inline-block;">  <a class="chart_str_period_1w">周线</a></li>
            </ul>
        </div>

        <!-- Periods -->
        

        <!-- Open TabBar -->
        

        <!-- Open ToolPanel -->
        

        <!-- Theme -->
        

        <!-- Settings -->
        <div id="chart_show_indicator" class="chart_toolbar_button chart_str_indicator_cap">技术指标</div><div id="chart_show_tools" class="chart_toolbar_button chart_str_tools_cap">画线工具</div><div id="chart_toolbar_theme">
            <div class="chart_toolbar_label chart_str_theme_cap">主题选择</div>
            <a name="dark" class="chart_icon chart_icon_theme_dark selected"></a>
            <a name="light" class="chart_icon chart_icon_theme_light"></a>
        </div><div class="chart_dropdown" id="chart_dropdown_settings">
            <div class="chart_dropdown_t"><a class="chart_str_settings">更多</a></div>
            <div class="chart_dropdown_data" style="margin-left: -169px;">
                <table><tbody><tr id="chart_select_main_indicator">
                    <td class="chart_str_main_indicator">主指标</td>
                    <td>
                        <ul>
                            <li><a name="MA">MA</a></li>
                            <li><a name="EMA" class="selected">EMA</a></li>
                            <li><a name="BOLL">BOLL</a></li>
                            <li><a name="SAR">SAR</a></li>
                            <li><a class="chart_str_none" name="NONE">关闭</a></li>
                        </ul>
                    </td>
                </tr><tr id="chart_select_chart_style">
                    <td class="chart_str_chart_style">主图样式</td>
                    <td>
                        <ul>
                            <li><a>CandleStick</a></li>
                            <li><a class="selected">CandleStickHLC</a></li>
                            <li><a>OHLC</a></li>
                        </ul>
                    </td>
                </tr><tr id="chart_select_theme" style="display: none;">
                    <td class="chart_str_theme">主题选择</td>
                    <td>
                        <ul>
                            <li><a name="dark" class="chart_icon chart_icon_theme_dark selected"></a></li>
                            <li><a name="light" class="chart_icon chart_icon_theme_light"></a></li>
                        </ul>
                    </td>
                </tr><tr id="chart_enable_tools" style="display: none;">
                    <td class="chart_str_tools">画线工具</td>
                    <td>
                        <ul>
                            <li><a name="on" class="chart_str_on">开启</a></li>
                            <li><a name="off" class="chart_str_off selected">关闭</a></li>
                        </ul>
                    </td>
                </tr><tr id="chart_enable_indicator" style="display: none;">
                    <td class="chart_str_indicator">技术指标</td>
                    <td>
                        <ul>
                            <li><a name="on" class="chart_str_on">开启</a></li>
                            <li><a name="off" class="chart_str_off selected">关闭</a></li>
                        </ul>
                    </td>
                </tr><tr>
                    <td></td>
                    <td>
                        <ul><li><a id="chart_btn_parameter_settings" class="chart_str_indicator_parameters">指标参数设置</a></li></ul>
                    </td>
                </tr></tbody></table>
            </div>
        </div>

        <div id="chart_updated_time">
            <span class="chart_str_updated">更新于</span>
            <span id="chart_updated_time_text">26秒</span>
            <span class="chart_str_ago">前</span>
        </div>
    </div>


    <!-- ToolPanel -->
    <div id="chart_toolpanel" style="display: none;">
        <div class="chart_toolpanel_separator"></div>
        <div class="chart_toolpanel_button">
            <div class="chart_toolpanel_icon" id="chart_Cursor" name="Cursor"></div>
            <div class="chart_toolpanel_tip chart_str_cursor">光标</div>
        </div>
        <div class="chart_toolpanel_button selected">
            <div class="chart_toolpanel_icon" id="chart_CrossCursor" name="CrossCursor"></div>
            <div class="chart_toolpanel_tip chart_str_cross_cursor">十字光标</div>
        </div>
        <div class="chart_toolpanel_button">
            <div class="chart_toolpanel_icon" id="chart_SegLine" name="SegLine"></div>
            <div class="chart_toolpanel_tip chart_str_seg_line">线段</div>
        </div>
        <div class="chart_toolpanel_button">
            <div class="chart_toolpanel_icon" id="chart_StraightLine" name="StraightLine"></div>
            <div class="chart_toolpanel_tip chart_str_straight_line">直线</div>
        </div>
        <div class="chart_toolpanel_button">
            <div class="chart_toolpanel_icon" id="chart_RayLine" name="RayLine"></div>
            <div class="chart_toolpanel_tip chart_str_ray_line">射线</div>
        </div>
        <div class="chart_toolpanel_button">
            <div class="chart_toolpanel_icon" id="chart_ArrowLine" name="ArrowLine"></div>
            <div class="chart_toolpanel_tip chart_str_arrow_line">箭头</div>
        </div>
        <div class="chart_toolpanel_button">
            <div class="chart_toolpanel_icon" id="chart_HoriSegLine" name="HoriSegLine"></div>
            <div class="chart_toolpanel_tip chart_str_horz_seg_line">水平线段</div>
        </div>
        <div class="chart_toolpanel_button">
            <div class="chart_toolpanel_icon" id="chart_HoriStraightLine" name="HoriStraightLine"></div>
            <div class="chart_toolpanel_tip chart_str_horz_straight_line">水平直线</div>
        </div>
        <div class="chart_toolpanel_button">
            <div class="chart_toolpanel_icon" id="chart_HoriRayLine" name="HoriRayLine"></div>
            <div class="chart_toolpanel_tip chart_str_horz_ray_line">水平射线</div>
        </div>
        <div class="chart_toolpanel_button">
            <div class="chart_toolpanel_icon" id="chart_VertiStraightLine" name="VertiStraightLine"></div>
            <div class="chart_toolpanel_tip chart_str_vert_straight_line">垂直直线</div>
        </div>
        <div class="chart_toolpanel_button">
            <div class="chart_toolpanel_icon" id="chart_PriceLine" name="PriceLine"></div>
            <div class="chart_toolpanel_tip chart_str_price_line">价格线</div>
        </div>
        <div class="chart_toolpanel_button">
            <div class="chart_toolpanel_icon" id="chart_TriParallelLine" name="TriParallelLine"></div>
            <div class="chart_toolpanel_tip chart_str_tri_parallel_line">价格通道线</div>
        </div>
        <div class="chart_toolpanel_button">
            <div class="chart_toolpanel_icon" id="chart_BiParallelLine" name="BiParallelLine"></div>
            <div class="chart_toolpanel_tip chart_str_bi_parallel_line">平行直线</div>
        </div>
        <div class="chart_toolpanel_button">
            <div class="chart_toolpanel_icon" id="chart_BiParallelRayLine" name="BiParallelRayLine"></div>
            <div class="chart_toolpanel_tip chart_str_bi_parallel_ray">平行射线</div>
        </div>
        <div class="chart_toolpanel_button">
            <div class="chart_toolpanel_icon" id="chart_DrawFibRetrace" name="DrawFibRetrace"></div>
            <div class="chart_toolpanel_tip chart_str_fib_retrace">斐波纳契回调</div>
        </div>
        <div class="chart_toolpanel_button">
            <div class="chart_toolpanel_icon" id="chart_DrawFibFans" name="DrawFibFans"></div>
            <div class="chart_toolpanel_tip chart_str_fib_fans">斐波纳契扇形</div>
        </div>
    </div>


    <!-- Canvas Group -->
    <div id="chart_canvasGroup" class="chart_canvasGroup_blackLogo" style="left: 0px; top: 30px; width: 880px; height: 470px;">
        <canvas class="chart_canvas" id="chart_mainCanvas" width="880" height="470" style="cursor: none;"></canvas>
        <canvas class="chart_canvas" id="chart_overlayCanvas" width="880" height="470" style="cursor: none;"></canvas>
    </div>

    <!-- TabBar -->
    <div id="chart_tabbar" style="display: none;">
        <ul>
            <li><a name="MACD">MACD</a></li>
            <li><a name="KDJ">KDJ</a></li>
            <li><a name="StochRSI">StochRSI</a></li>
            <li><a name="RSI">RSI</a></li>
            <li><a name="DMI">DMI</a></li>
            <li><a name="OBV">OBV</a></li>
            <li><a name="BOLL">BOLL</a></li>
            <li><a name="SAR">SAR</a></li>
            <li><a name="DMA">DMA</a></li>
            <li><a name="TRIX">TRIX</a></li>
            <li><a name="BRAR">BRAR</a></li>
            <li><a name="VR">VR</a></li>
            <li><a name="EMV">EMV</a></li>
            <li><a name="WR">WR</a></li>
            <li><a name="ROC">ROC</a></li>
            <li><a name="MTM">MTM</a></li>
            <li><a name="PSY">PSY</a></li>
        </ul>
    </div>

    <!-- Parameter Settings -->
    <div id="chart_parameter_settings" style="left: 120px; top: 41px;">
        <h2 class="chart_str_indicator_parameters">指标参数设置</h2>
        <table>
            <tbody><tr>
                <th>MA</th>
                <td><input name="MA"><input name="MA"><input name="MA"><input name="MA"></td>
                <td><button class="chart_str_default">默认值</button></td>

                <th>DMA</th>
                <td><input name="DMA"><input name="DMA"><input name="DMA"></td>
                <td><button class="chart_str_default">默认值</button></td>
            </tr>

            <tr>
                <th>EMA</th>
                <td><input name="EMA"><input name="EMA"><input name="EMA"><input name="EMA"></td>
                <td><button class="chart_str_default">默认值</button></td>

                <th>TRIX</th>
                <td><input name="TRIX"><input name="TRIX"></td>
                <td><button class="chart_str_default">默认值</button></td>
            </tr>

            <tr>
                <th>VOLUME</th>
                <td><input name="VOLUME"><input name="VOLUME"></td>
                <td><button class="chart_str_default">默认值</button></td>

                <th>BRAR</th>
                <td><input name="BRAR"></td>
                <td><button class="chart_str_default">默认值</button></td>
            </tr>

            <tr>
                <th>MACD</th>
                <td><input name="MACD"><input name="MACD"><input name="MACD"></td>
                <td><button class="chart_str_default">默认值</button></td>

                <th>VR</th>
                <td><input name="VR"><input name="VR"></td>
                <td><button class="chart_str_default">默认值</button></td>
            </tr>

            <tr>
                <th>KDJ</th>
                <td><input name="KDJ"><input name="KDJ"><input name="KDJ"></td>
                <td><button class="chart_str_default">默认值</button></td>

                <th>EMV</th>
                <td><input name="EMV"><input name="EMV"></td>
                <td><button class="chart_str_default">默认值</button></td>
            </tr>

            <tr>
                <th>StochRSI</th>
                <td><input name="StochRSI"><input name="StochRSI"><input name="StochRSI"><input name="StochRSI"></td>
                <td><button class="chart_str_default">默认值</button></td>

                <th>WR</th>
                <td><input name="WR"><input name="WR"></td>
                <td><button class="chart_str_default">默认值</button></td>
            </tr>

            <tr>
                <th>RSI</th>
                <td><input name="RSI"><input name="RSI"><input name="RSI"></td>
                <td><button class="chart_str_default">默认值</button></td>

                <th>ROC</th>
                <td><input name="ROC"><input name="ROC"></td>
                <td><button class="chart_str_default">默认值</button></td>
            </tr>

            <tr>
                <th>DMI</th>
                <td><input name="DMI"><input name="DMI"></td>
                <td><button class="chart_str_default">默认值</button></td>

                <th>MTM</th>
                <td><input name="MTM"><input name="MTM"></td>
                <td><button class="chart_str_default">默认值</button></td>
            </tr>

            <tr>
                <th>OBV</th>
                <td><input name="OBV"></td>
                <td><button class="chart_str_default">默认值</button></td>

                <th>PSY</th>
                <td><input name="PSY"><input name="PSY"></td>
                <td><button class="chart_str_default">默认值</button></td>
            </tr>

            <tr>
                <th>BOLL</th>
                <td><input name="BOLL"></td>
                <td><button class="chart_str_default">默认值</button></td>
            </tr>
        </tbody></table>
        <div id="close_settings"><a class="chart_str_close">关闭</a></div>
    </div>

    <!-- Loading -->
    <div id="chart_loading" class="chart_str_loading" style="left: 340px; top: 113px;">正在读取数据...</div>

</div> <!-- End Of ChartContainer -->


<script src="__PUBLIC__/home/jquery-3.2.1.min.js"></script>
<script src="__PUBLIC__/home/jquery.mousewheel.js"></script>
<script src="__PUBLIC__/home/fullKilne.js"></script>

</body></html>