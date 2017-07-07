/**现货行情图表js
 */
/**
 * 全局变量
 * @type {{socketUtil: socketUtil}}
 */
var Globle = {
    socket: new socketUtil(site_flag),
    siteFlag:site_flag,
    symbol:Number(jQuery("#symbol").val()),
    merge:Number(jQuery("#merge").attr("val"))||Number(getCookieValue("deptMerge_stock")),
    symbolStr:SYMBOLS_UTIL.symbolStr[Number(jQuery("#symbol").val())],
    currentSymbolStr:Number(site_flag)==2?"$":"￥",
    languageType:Number(jQuery("#languageType").val()),
    symbolPricePoint:SYMBOLS_UTIL.priceRate[Number(jQuery("#symbol").val())],
    symbolDepthAmountPoint:SYMBOLS_UTIL.amountRate[Number(jQuery("#symbol").val())],
    symbolOrderAmountPoint:SYMBOLS_UTIL.amountOrderRate[Number(jQuery("#symbol").val())],
    isLogin:islogin,
    showSize:60
};
//未优化删除
var push = {};
push.ltclast = 0;
push.btclast = 0;
push.btcsell = 0;
push.btcbuy = 0;
push.ltcsell = 0 ;
push.ltcbuy = 0 ;
/**
 *数据显示根据币种截位显示
 * @type {{}}
 */
var Current=new function(){
    this.price=function(value,rate){
        var current=Number(value);
        if(!rate){
            rate=2;
        }
        return Number(current).toFixed(rate);
    }
    this.priceFormat=function(value,rate){
        if(!rate){
            rate=2
        }
        return formatNumber(this.price(value,rate),rate);
    }
}
var market = new Market().init();
/**
 * 页面定时器处理类
 * @constructor
 */
function TimerTask(dataSource){
    var dataSource=dataSource;
    var tmpThis=this;
    this.refershPubID,
        this.refreshPriID,
        this.stopTimer=function (timerTaskID){
            if(!!timerTaskID){
                clearInterval(timerTaskID);
            }
        }
    this.startRefreshPubTime=function(){
        this.stopTimer(this.startPubTime);
        this.startPubTime=setInterval(function(){
            var random=new Date().getTime();
            var url_ticker = "/real/ticker.do?random="+random;
            $.post(url_ticker,{symbol:Globle.symbol},function(data){
                dataSource.refreshTicker(data);
            },"JSON");
            var url_depth="/marketRefresh.do?random="+random;
            $.post(url_depth,{symbol:Globle.symbol,deptMerge_stock:Globle.merge},function(data){
                dataSource.refreshDepth(data);
                if(!!data.recentDealList){
                    dataSource.refreshRecent(data.recentDealList);
                }
            },"JSON");
        },2000);
    }
    this.startRefreshPriTime=function(){
        this.stopTimer(this.startPriTime);
        this.startPriTime=setInterval(function(){
            tmpThis.refreshTradeOrders(0);
            var url = "/trade/freshUserInfo.do";
            $.post(url,{symbol:Globle.symbol},function(data){
                dataSource.refreshAccount(data);
            },"JSON");
        },2000);
    }

    this.startRefreshTradeOrders=function(type){
        this.refreshTradeOrders(type);
    }

    this.stopAllTimer=function(){
        this.stopTimer(this.startPubTime);
        this.stopTimer(this.startPriTime);
    }

    this.refreshTradeOrders=function(type){

        //var t = -1;
        //jQuery(".operationTab").each(function(){
        //    if (jQuery(this).find("a").hasClass("cur")){
        //        t = jQuery(this).attr("type");
        //    }
        //});

        //先获取一下当前取的是哪个tab，只有在未成交tab选择的时候，才会进行轮询更新操作
        //当前不是未成交tab 但是 type == 0 说明是轮询的请求
        //if (t != 1 && type == 0) {
        //    return;
        //}

        var url = "/marketTradeOrdersRefresh.do?";
        var param = {symbol:Globle.symbol,type:type};
        jQuery.post(url,param,function(data){
            dataSource.refreshTradeOrders(type,data);
        });

    }


}
/**
 * 行情图标主类
 */
function Market() {
    var dataSource=new DataSource();
    var timerTask=new TimerTask(dataSource);
    var kline_iframe=jQuery("#kline_iframe")[0];
    var isHaveKline=function(){
        if(!!jQuery("#kline_iframe")&&!!jQuery("#kline_iframe")[0]&&!!jQuery("#kline_iframe")[0].contentWindow){
            return true;
        }else{
            return false;
        }
    }
    var loadKline=function(){
        if(!kline_iframe.contentWindow._set_current_language){
            setTimeout(loadKline,200);
            return;
        }
        kline_iframe.contentWindow.onPushingStarted(PushFrom);
        if(Globle.languageType==0){
            kline_iframe.contentWindow._set_current_language("zh-cn"); // "zh-cn" "en-us" "zh-tw" //中英文切换
        }else{
            kline_iframe.contentWindow._set_current_language("en-us");
        }
        kline_iframe.contentWindow._setCaptureMouseWheelDirectly(false);
        if(Globle.siteFlag!=2) {
            // kline_iframe.contentWindow._set_current_url("https://www.okcoin.cn/api/klineData.do");//url
            kline_iframe.contentWindow._set_current_url("/api/klineData.do");//url
        }else {
            //kline_iframe.contentWindow._set_current_url("https://www.okcoin.com/api/klineData.do");//url
            kline_iframe.contentWindow._set_current_url("/api/klineData.do");//url
        }
        kline_iframe.contentWindow._set_current_coin(SYMBOLS_UTIL.marketFrom[Globle.symbol]);
        kline_iframe.contentWindow._set_money_type("usd"); // "usd" 'cny'现货不需要币种转换

    }
    var haveKline=isHaveKline();
    this.getTimerTask=function(){
        return timerTask;
    }
    if (typeof this.init != 'function') {
        this.init = function () {
            Globle.socket.addErrCallBackFun(this.startRefersh);
            Globle.socket.addSuccCallBackFun(this.send);
            Globle.socket.addKlineCallBack(function(data,dataType){
                kline_iframe.contentWindow.onPushingResponse(marketFrom, type, coinVol, data);
            });
            Globle.socket.addCallBack("ok_info_ltc_order",dataSource.handleTradeOrder);
            Globle.socket.addCallBack("ok_info_btc_order",dataSource.handleTradeOrder);
            Globle.socket.addCallBack("ok_info_account",dataSource.handleAccount);
            if(haveKline){
                loadKline();
            }
            testNetwork();
            //初始化价格框
            jQuery("#tradePrice").focus();
            Globle.socket.connection();
            return this;
        }
        this.send = function () {
            timerTask.stopAllTimer();//停止定时器刷新
            var userid = getCookieValue("coin_session_user_id");
            if (!!userid) {
                Globle.socket.login(userid);
            }
            if(!!kline_iframe&&!!kline_iframe.contentWindow&&!!kline_iframe.contentWindow.onPushingStarted){
                kline_iframe.contentWindow.onPushingStarted(PushFrom);
            }
            sendDepth();
        }
        var sendDepth=function(){
            dataSource.clean();//清除缓存
            var strType = "ok_";
            switch (Globle.symbol) {
                case 0:
                    strType += "btc_"
                    break;
                case 1:
                    strType += "ltc_";
                    break;
                default:
                    //@todo to add new coin
                    return;
            }
            switch (Globle.merge) {
                case 1:
                    Globle.socket.send(strType + 'depth_merge',dataSource.handlMerge);
                    break;//深度合并时 不需要重新获取行情信息
                case 0:
                    Globle.socket.send(strType + 'depth_driven_200',dataSource.handleDepth);
                    Globle.socket.send(strType + 'deal',dataSource.handleRecent);
                    Globle.socket.send('ok_ltc_ticker',dataSource.handleTicker);
                    Globle.socket.send('ok_btc_ticker',dataSource.handleTicker);
                    break;
                case 2:
                    Globle.socket.send(strType + 'depth_merge_01',dataSource.handlMerge);
                    break;
            }
        }
        this.reStartMerge=function(merge){
            Globle.merge=merge;
            var strType = "ok_";
            switch (Globle.symbol) {
                case 0:
                    strType += "btc_"
                    break;
                case 1:
                    strType += "ltc_";
                    break;
                default:
                    //@todo to add new coin
                    return;
            }
            switch (merge) {
                case 0:
                    Globle.socket.removeCallBack(strType + 'depth_merge');
                    Globle.socket.removeCallBack(strType + 'depth_merge_01');
                    Globle.socket.stop(strType + 'depth_merge');
                    Globle.socket.stop(strType + 'depth_merge_01');
                    break;
                case 1:
                    Globle.socket.removeCallBack(strType + 'depth_driven_200');
                    Globle.socket.removeCallBack(strType + 'deal');
                    Globle.socket.removeCallBack('ok_ltc_ticker');
                    Globle.socket.removeCallBack('ok_btc_ticker');
                    Globle.socket.removeCallBack(strType + 'depth_merge_01');
                    Globle.socket.stop('ok_ltc_ticker');
                    Globle.socket.stop('ok_btc_ticker');
                    Globle.socket.stop(strType + 'depth_driven_200');
                    Globle.socket.stop(strType + 'deal');
                    Globle.socket.stop(strType + 'depth_merge_01');
                case 2:
                    Globle.socket.removeCallBack(strType + 'depth_driven_200');
                    Globle.socket.removeCallBack(strType + 'depth_merge');
                    Globle.socket.removeCallBack(strType + 'deal');
                    Globle.socket.removeCallBack('ok_ltc_ticker');
                    Globle.socket.removeCallBack('ok_btc_ticker');
                    Globle.socket.stop('ok_ltc_ticker');
                    Globle.socket.stop('ok_btc_ticker');
                    Globle.socket.stop(strType + 'depth_merge');
                    Globle.socket.stop(strType + 'depth_driven_200');
                    Globle.socket.stop(strType + 'deal');
                    break;
            }
            sendDepth();
        }
        /**
         * 启动轮训页面刷新
         */
        this.startRefersh=function(){
            timerTask.startRefreshPubTime(dataSource);
            if(Globle.isLogin){
                timerTask.startRefreshPriTime(dataSource);
            }
        }

    }
}
/**
 * 核算并更新页面用户账户信息
 */
function AccountCompute(){
    //现货信息
    this.btcLastPrice;
    this.btcBuyOnePrice;
    this.btcSellOnePrice;


    this.ltcLastPrice;
    this.ltcBuyOnePrice;
    this.ltcSellOnePrice;


    this.lendBtc//借出ltc可用
    this.lendLtc//借出btc可用
    this.lendCny;//借出人民币余额

    this.borrowLtc;//借入ltc
    this.borrowBtc;//借入btc
    this.borowCny;//借入人民币

    this.binterestBtc;//利息btc
    this.binterestLtc;//利息ltc
    this.binterestCny;//利息cny

    this.cnyBalance;//用户余额
    this.ltcBalance;//ltc 余额
    this.btcBalance;//btc 余额

    this.freezeBtcBalance;//冻结余额
    this.freezeCnyBalance;
    this.freezeLtcBalance;

    this.borrowBtcBalance;//借入btc余额
    this.borrowLtcBalance;//借入ltc
    this.borrowCnyBalance;//借入cny

    this.lendFreezeBtcBalance;//借贷btc冻结
    this.lendFreezeLtcBalance;//借贷ltc冻结
    this.lendFreezeCnyBalance;//借贷cny冻结

    this.fundBtcBalance;//btc 基金 余额
    this.fundLtcBalance;//ltc 基金 余额

    this.lendedBtcBalance;//已经借出btc
    this.lendedLtcBalance;//已经借出ltc
    this.lendedCnyBalance;//已经借出cny
        //期货信息
    this.futureBtcRights;//账户权益
    this.futureLtcRights;//

    this.futureAccount;//期货余额信息

    this.dealAmount=function(data){
        if(!data){
            return;
        }
        this.binterestBtc=data.binterestBtcBalance;
        this.binterestCny=data.binterestCnyBalance;
        this.binterestLtc=data.binterestLtcBalance;
        this.borrowBtcBalance=data.borrowBtcBalance;
        this.borrowCnyBalance=data.borrowCnyBalance;
        this.borrowLtcBalance=data.borrowLtcBalance;
        this.cnyBalance=data.cnyBalance;
        this.btcBalance=data.btcBalance;
        this.ltcBalance=data.ltcBalance;
        this.freezeBtcBalance=data.freezeBtcBalance;
        this.freezeCnyBalance=data.freezeCnyBalance;
        this.freezeLtcBalance=data.freezeLtcBalance;
    }

    this.toRefreshJsonData=function(data){
        if(!data){
            return;
        }
        this.cnyBalance=data.bannerUserCnyBalance;
        this.btcBalance=data.bannerUserBtcBalance;
        this.ltcBalance=data.bannerUserLtcBalance;
        this.freezeBtcBalance=data.bannerFreezeBtcBalance;
        this.freezeCnyBalance=data.bannerFreezeCnyBalance;
        this.freezeLtcBalance=data.bannerFreezeLtcBalance;
        //@todo 未完成优化
        return {
            availableCny:this.cnyBalance,
            availableBtc:this.btcBalance,
            availableLtc:this.ltcBalance,
            frozenCny:this.freezeCnyBalance,
            frozenBtc:this.freezeBtcBalance,
            frozenLtc:this.freezeLtcBalance,
            allasset:unFormatNumber(data.allasset),
            netasset:unFormatNumber(data.netasset),
            asubtotalCny:unFormatNumber(data.asubtotalCny),
            asubtotalBtc:unFormatNumber(data.asubtotalBtc),
            asubtotalLtc:unFormatNumber(data.asubtotalLtc),
            futureAllasset:account_Futureaccount,
            lendValue:account_lendValue,
            fundValue:account_fundValue,
            uNetValue:account_uNetValue,
            tradeValue:account_tradeValue
        }
    }
    this.toJSONData=function(data){
        if(!data){
            return null;
        }
        if(!!data.symbol){
            switch(data.symbol){
                case 0:
                    this.btcLastPrice=Number(data.last);
                    this.btcBuyOnePrice=Number(data.buy);
                    this.btcSellOnePrice=Number(data.sell);
                    break;
                case 1:
                    this.ltcLastPrice=Number(data.last);
                    this.ltcBuyOnePrice=Number(data.buy);
                    this.ltcSellOnePrice=Number(data.sell);
                    break;
                default :
                    //@todo add new coin
                    return;
            }
        }
        var account_Futureaccount=this.futureAccount;
        var futureaccountSum = accAdd(accMul(this.futureBtcRights,this.btcLastPrice),accMul(this.futureLtcRights,this.ltcLastPrice));
        if(futureaccountSum>0){
            account_Futureaccount=futureaccountSum;
        }
        //借贷账户 借出 BTC * 市价  LTC * 市价  + CNY
        var account_Sum_LendBtc = accAdd(accAdd(this.lendBtc,this.freezeBtcBalance),this.lendedBtcBalance);
        var account_Sum_LendLtc = accAdd(accAdd(this.lendLtc,this.freezeLtcBalance),this.lendedLtcBalance);
        var account_Sum_LendCny = accAdd(accAdd(this.lendCny,this.freezeCnyBalance),this.lendedCnyBalance);
        //交易账户BTC 可用BTC + 冻结BTC
        var  account_tradeBtc = accAdd(this.btcBalance,this.freezeBtcBalance);
        //交易账户BTC 可用BTC + 冻结BTC
        var  account_tradeLtc = accAdd(this.ltcBalance,this.freezeLtcBalance);

        //基金账户
        var account_fundValue = accAdd(accMul(this.fundBtcBalance,this.btcLastPrice),accMul(this.fundLtcBalance,this.ltcLastPrice));
        //借贷账户 借出 BTC * 市价  LTC * 市价  + CNY
        var account_lendValue = accAdd(accAdd(accMul(account_Sum_LendBtc,this.btcLastPrice),accMul(account_Sum_LendLtc,this.ltcLastPrice)),account_Sum_LendCny);
        //交易账户 交易BTC * 市价 + 交易：LTC *市价　+可用CNY + 冻结CNY
        var account_tradeValue = accAdd(accAdd(accAdd(accMul(account_tradeBtc,this.btcLastPrice),accMul(account_tradeLtc,this.ltcLastPrice)),this.cnyBalance),this.freezeCnyBalance);

        //交易账户合计
        var asubtotalCny = accAdd(this.freezeCnyBalance,this.cnyBalance);
        var asubtotalBtc = accAdd(this.freezeBtcBalance,this.btcBalance);
        var asubtotalLtc = accAdd(this.freezeLtcBalance,this.ltcBalance);
        //借贷合计
        var account_lsubtotalCnyValue = accAdd(accAdd(this.lendCny,this.lendFreezeCnyBalance),this.lendedCnyBalance);
        var account_lsubtotalBtcValue = accAdd(accAdd(this.lendBtc,this.lendFreezeBtcBalance),this.lendedBtcBalance);
        var account_lsubtotalLtcValue = accAdd(accAdd(this.lendLtc,this.lendFreezeLtcBalance),this.lendedLtcBalance);

        //净资产CNY = 可用CNY + 冻结CNY -借款CNY - 减去利息
        var cny = accSub(accSub(accAdd(this.cnyBalance,this.freezeCnyBalance),this.borrowCnyBalance),this.binterestCny);
        //净资产BTC = 可用BTC + 冻结BTC - 借款BTC - 利息BTC
        var btc = accSub(accSub(accAdd(this.btcBalance,this.freezeBtcBalance),this.borrowBtcBalance),this.binterestBtc);
        //净资产LTC = 可用LTC + 冻结LTC -借款LTC - 利息LTC
        var ltc = accSub(accSub(accAdd(this.ltcBalance,this.freezeLtcBalance),this.borrowLtcBalance),this.binterestLtc);
        
        btc = accMul(btc, this.btcLastPrice);
        ltc = accMul(ltc, this.ltcLastPrice);
        var account_uNetValue = accAdd(accAdd(cny,btc),ltc);

        //总资产
        var allasset = accAdd(accAdd(accAdd(account_tradeValue, account_lendValue),account_fundValue),account_Futureaccount);
        //净资产
        var netasset = accAdd(accAdd(account_uNetValue,account_lendValue)<0?0:accAdd(accAdd(account_uNetValue,account_lendValue),account_fundValue),account_Futureaccount);
        return {
            availableCny:this.cnyBalance,
            availableBtc:this.btcBalance,
            availableLtc:this.ltcBalance,
            frozenCny:this.freezeCnyBalance,
            frozenBtc:this.freezeBtcBalance,
            frozenLtc:this.freezeLtcBalance,
            allasset:allasset,
            netasset:netasset,
            asubtotalCny:asubtotalCny,
            asubtotalBtc:asubtotalBtc,
            asubtotalLtc:asubtotalLtc,
            futureAllasset:account_Futureaccount,
            lendValue:account_lendValue,
            fundValue:account_fundValue,
            uNetValue:account_uNetValue,
            tradeValue:account_tradeValue
        }
    }
    this.init=function(){
        this.btcLastPrice=this.getValue("bannerAccountBtcLast");
        this.btcBuyOnePrice=this.getValue("bannerBtcBuy");
        this.btcSellOnePrice=this.getValue("bannerBtcSell");


        this.ltcLastPrice=this.getValue("bannerAccountLtcLast");
        this.ltcBuyOnePrice=this.getValue("bannerLtcBuy");
        this.ltcSellOnePrice=this.getValue("bannerLtcSell");

        this.futureAccount=this.getValue("futureaccount_hidden");
        this.futureBtcRights=this.getValue("bannerfutureAccountBtcRights");
        this.futureLtcRights=this.getValue("bannerfutureAccountLtcRights");

        this.lendBtc=this.getValue("lendBtc");
        this.lendLtc=this.getValue("lendLtc");
        this.lendCny=this.getValue("lendCny");

        this.borrowLtc=this.getValue("bannerborrowsLtc");
        //this.borrowBtc=this.getValue("bannerborrowsBtc");
        this.borowCny=this.getValue("bannerborowsCny");

        this.binterestBtc=this.getValue("bannerBinterestBtc");
        this.binterestLtc=this.getValue("bannerBinterestLtc");
        this.binterestCny=this.getValue("bannerBinterestCny");


        this.cnyBalance=this.getValue("bannerUserCnyBalance");
        this.ltcBalance=this.getValue("bannerUserLtcBalance");
        this.btcBalance=this.getValue("bannerUserBtcBalance");


        this.freezeBtcBalance=this.getValue("bannerFreezeBtcBalance");
        this.freezeCnyBalance=this.getValue("bannerFreezeLtcBalance");
        this.freezeLtcBalance=this.getValue("bannerFreezeCnyBalance");



        this.borrowBtcBalance=this.getValue("bannerBorrowBtcBalance");
        this.borrowLtcBalance=this.getValue("bannerBorrowLtcBalance");
        this.borrowCnyBalance=this.getValue("bannerBorrowCnyBalance");


        this.lendFreezeBtcBalance=this.getValue("bannerLendFreezeBtcBalance");
        this.lendFreezeLtcBalance=this.getValue("bannerLendFreezeLtcBalanced");
        this.lendFreezeCnyBalance=this.getValue("bannerLendFreezeCnyBalance");


        this.lendFreezeCnyBalance=this.getValue("bannerFundBtcBalance");
        this.lendFreezeCnyBalance=this.getValue("bannerFundLtcBalance");

        this.fundBtcBalance=this.getValue("bannerLendFreezeCnyBalance");
        this.fundLtcBalance=this.getValue("bannerLendFreezeCnyBalance");

        this.lendedBtcBalance=this.getValue("bannerLendedOutBtcBalance");
        this.lendedLtcBalance=this.getValue("bannerLendedOutLtcBalance");
        this.lendedCnyBalance=this.getValue("bannerLendedOutCnyBalance");


        this.futureAccount=this.getValue("futureaccount_hidden");
    }
    this.getValue=function(id){
        return Number(jQuery("#"+id).val());
    }
    this.init();
}
/**
 * DataSource 数据处理类 推送和轮序类型转换类
 * @constructor
 */
function DataSource() {
    var rendering=new Rendering();
    var accountCompute=new AccountCompute();
    this.sellDepth,this.buyDepth;
    var tmpThis=this;
    if (typeof this.handleTicker != 'function') {
        this.refreshAccount=function(data){
            //console.info(data);
            //暂未修改用的以前的代码@TODO
            bannerUserAccountPolling(data);
            AccountingUserAccountInfo();
            if (document.getElementById("canpush") != null) {
                _ChangeBalance();
            }

        }
        this.handleAccount=function(data){
            jQuery("#bannerUserCnyBalance").val(data.cnyBalance);
            jQuery("#bannerUserLtcBalance").val(data.ltcBalance);
            jQuery("#bannerUserBtcBalance").val(data.btcBalance);
            jQuery("#bannerFreezeBtcBalance").val(data.freezeBtcBalance);
            jQuery("#bannerFreezeLtcBalance").val(data.freezeLtcBalance);
            jQuery("#bannerFreezeCnyBalance").val(data.freezeCnyBalance);

            jQuery(".tradeCnyBalance").html(formatNumber(floor(data.cnyBalance,2),2));
            switch (Globle.symbol){
                case 0:
                    jQuery(".tradeBtcOrLtcBalance").html(formatNumber(floor(data.btcBalance,4),4));
                    break;
                case 1:
                    jQuery(".tradeBtcOrLtcBalance").html(formatNumber(floor(data.ltcBalance,4),4));
                    break;
                default :
                    //@todo add new coin
                    return;
            }
        }
        this.handlMerge = function(data){
            if(Globle.merge!=1 && Globle.merge != 2){
                return;
            }
            rendering.renderingDepth(data);
            rendering.renderingRecent(data.recentDealList);
            if(!!data.ticker){
                if(!!data.ticker.btc){
                    data.ticker.btc.symbol=0;
                    rendering.renderingTicker(data.ticker.btc);
                }
                if(!!data.ticker.ltc){
                    data.ticker.ltc.symbol=1;
                    rendering.renderingTicker(data.ticker.ltc);
                }
                if(Globle.isLogin){
                    AccountingUserAccountInfo()//核算更新账户信息
                    if(document.getElementById("canpush")!=null){
                        _ChangeBalance();
                    }
                }
            }

        }
        this.handleTicker = function (data,type) {
            if(!type){
                return;
            }
            var symbol=0;
            if(type.indexOf("ltc")!=-1){
                symbol=1;
            }
            //@todo add new coin
            if(symbol!=1&&symbol!=0){
                return;
            }
            data.symbol=symbol;
            rendering.renderingTicker(data);
            if (Globle.isLogin) {
                //rendering.renderingAccount(accountCompute.toJSONData(data)); //未使用优化
            }
            //未优化代码
            if (!!data.btc) {
                jQuery("#bannerAccountBtcLast").val(ticker.btc.last);
                jQuery("#bannerBtcBuy").val(ticker.btc.buy);
                jQuery("#bannerBtcSell").val(ticker.btc.sell);
                push.btclast = Number(ticker.btc.last);
                jQuery("#bannerBtcLast").html((ticker.btc.last + '').replace(/\d{1,3}(?=(\d{3})+(\.\d*)?$)/g, '$&,'));
                jQuery("#bannerBtcVol").html(ticker.btc.vol.substr(0,ticker.btc.vol.indexOf(".")));
            }
            if (!!data.ltc) {
                jQuery("#bannerAccountLtcLast").val(ticker.ltc.last);
                jQuery("#bannerLtcBuy").val(ticker.ltc.buy);
                jQuery("#bannerLtcSell").val(ticker.ltc.sell);
                push.ltclast = Number(ticker.ltc.last);
                jQuery("#bannerLtcLast").html((ticker.ltc.last + '').replace(/\d{1,3}(?=(\d{3})+(\.\d*)?$)/g, '$&,'));
                jQuery("#bannerLtcVol").html(ticker.ltc.vol.substr(0,ticker.ltc.vol.indexOf(".")));
            }
            if(Globle.isLogin){
                AccountingUserAccountInfo()//核算更新账户信息
                if(document.getElementById("canpush")!=null){
                    _ChangeBalance();
                }
                //rendering.renderingAccount(accountCompute.toJSONData(data)); //未使用优化
            }
            //end
        }
        this.handleDepth=function(data){
            if(Globle.merge!=0){
                return;
            }
            var merge={buyDepthList:tmpThis.getBuyDepthList(data.buyDepthList),sellDepthList:tmpThis.getSellDepthList(data.sellDepthList)};
            var klineIframe=jQuery("#kline_iframe")[0];
            if(!!klineIframe&&!!klineIframe.contentWindow._set_current_depth){
                klineIframe.contentWindow._set_current_depth(getKlineJsonDepth(merge));//深度信息
            }
            rendering.renderingDepth(merge);
        }
        var getKlineJsonDepth=function(data){
            var sell = data.sellDepthList;
            var json_sell_result=new Array();
            if(!!sell){
                for(var i=0;i<sell.length;i++){
                    var json_sell=new Array();
                    json_sell.push(sell[i][0]);
                    json_sell.push(sell[i][1])
                    json_sell_result.push(json_sell);
                }
            }
            var buy = data.buyDepthList;
            var json_buy_result=new Array();
            if(!!buy){
                for(var i=0;i<buy.length;i++){
                    var json_buy=new Array();
                    json_buy.push(buy[i][0]);
                    json_buy.push(buy[i][1])
                    json_buy_result.push(json_buy);
                }
            }
            return {"asks":json_sell_result,"bids":json_buy_result};
        }
        this.handleRecent=function(data){
            rendering.renderingRecent(data);
        }
        this.handleTradeOrder=function(data){
            rendering.renderingHandleTradeOrders(data);
        }
        //轮训刷新
        this.refreshTicker = function(data){
            rendering.renderingTicker(data);
        }
        this.refreshDepth =function(data){
            if(Globle.merge==0){
                var klineIframe=jQuery("#kline_iframe")[0];
                if(!!klineIframe&&!!klineIframe.contentWindow._set_current_depth){
                    klineIframe.contentWindow._set_current_depth(getKlineJsonDepth(data));//深度信息
                }
            }
            rendering.renderingDepth(data);
        }
        /***
         * 最新成交
         * @param data
         */
        this.refreshRecent =function(data){
            rendering.renderingRecent(data);
        }
        this.getBuyDepthList = function (depthList) {
            if (!this.buyDepth) {//第一次加载为空的时候
                this.buyDepth = depthList;
                return depthList;
            }
            if (!!depthList) {
                for (var i = 0; i < depthList.length; i++) {
                    var price = depthList[i][0];
                    var amount=depthList[i][1];
                    var index = this.buyDepth.binarySearchDesc(price);
                    if(Number(amount)==0){//删除
                        this.buyDepth.splice(index,1);
                        continue;
                    }
                    if (index != -1) {//修改
                        this.buyDepth[index] = depthList[i];
                        continue;
                    }
                    if(index==-1){//增加
                        this.buyDepth.push(depthList[i]);
                        continue;
                    }
                }
                //重新排序
                this.buyDepth.sort(function(o1,o2){
                    return Number(o2[0])-Number(o1[0]);
                });
                //删除超过200的
                if(this.buyDepth.length>200){
                    this.buyDepth.splice(200,this.buyDepth.length-200);
                }
            }
            return this.buyDepth;
        };
        this.getSellDepthList=function(depthList){
            if(!this.sellDepth){//第一次加载为空的时候
                this.sellDepth=depthList;
                return depthList;
            }
            if (!!depthList) {
                for (var i = 0; i < depthList.length; i++) {
                    var price = depthList[i][0];
                    var amount=depthList[i][1];
                    var index = this.sellDepth.binarySearchDesc(price);
                    if(Number(amount)==0){//删除
                        var tmp=this.sellDepth.splice(index,1);
                        continue;
                    }
                    if (index != -1) {//修改
                        this.sellDepth[index] = depthList[i];
                        continue;
                    }
                    if(index==-1){//增加
                        this.sellDepth.push(depthList[i]);
                        continue;
                    }
                }
                //重新排序
                this.sellDepth.sort(function(o1,o2){
                    return Number(o2[0])-Number(o1[0]);
                });
                //删除超过200的
                if(this.sellDepth.length>200){
                    this.sellDepth.splice(0,this.sellDepth.length-200);
                }
            }
            return this.sellDepth;
        }
        this.refreshTradeOrders=function(type,data){

            if (data == null || data == "") {
                return;
            }
            data = eval('(' + data + ')');
            if (type == 0) {//未成交
                rendering.renderingTradeOrders(data);
            } else {//历史
                rendering.renderingTradeOrdersFinish(data);
            }
        }
        this.clean=function(){
            this.buyDepth=null;
            this.sellDepth=null;
        }
    }
}
/**
 * 渲染页面主类
 * @constructor
 */
function Rendering(){
    var pushjs1=get$("pushjs1");
    if (typeof this.renderingTicker != 'function') {
        var sortDepth=new function(){
            this.sort=function (depth) {
                depth.sort(function (a, b) {
                    return a[1] - b[1];
                });
                return depth;
            };
            this.median=function(depth){
                var i=floor((depth.length/3)*2,0);
                return depth[i][1]<1?1:depth[i][1];;
            }
            this.medianUnit=function(buydepth,sellDepth,colorWidth){
                var tmpBuy=new Array(buydepth);
                tmpBuy=tmpBuy[0];
                var tmpSell=new Array(sellDepth);
                tmpSell=tmpSell[0];
                tmpBuy=tmpBuy.concat(tmpSell);
                var result=this.median(this.sort(tmpBuy))/colorWidth;
                tmpBuy=null;
                tmpSell=null;
                return result;
            }
            this.width=function(amount,medianUnit){
                if(medianUnit==0){
                    return 1;
                }else{
                    var result=round(Number(amount)/medianUnit,0);
                    if(result<=0){
                        return 1;
                    }else if(result>160){
                        return 100;
                    }else{
                        return result/160*100;
                    }
                }
            }
        }
        var Ele=function(id){
            var element=document.getElementById(id);
            if(!!element){
                return jQuery(element);
            }else{
                return jQuery("#"+id);
            }
        }
        var dEle=function(id){
            var element=document.getElementById(id);
            if(!!element){
                return element;
            }
        }
        this.renderingAccount=function(data){
           //交易账户
            //余额
            Ele("available.cny").html(formatNumber(floor(data.availableCny,2),2));
            Ele("available.btc").html(flexibleNumber(data.availableBtc));
            Ele("available.ltc").html(flexibleNumber(data.availableLtc));
            //冻结
            Ele("frozen.cny").html(formatNumber(floor(data.frozenCny,2),2));
            Ele("frozen.btc").html(flexibleNumber(data.frozenBtc));
            Ele("frozen.ltc").html(flexibleNumber(data.frozenLtc));

            Ele("allasset").html(formatNumber(floor(data.allasset,2),2));//总资产 现货
            Ele("netasset").html(formatNumber(floor(data.netasset,2),2));//净资产

            Ele("uNetValue").html(formatNumber(floor(data.uNetValue,2),2));//交易账户净资产
            Ele("tradeValue").html(formatNumber(floor(data.tradeValue,2),2));//交易账户总资产
            //下拉可用
            Ele("trade.available.cny").html(formatNumber(floor(data.availableCny,2),2));
            Ele("trade.available.btc").html(flexibleNumber(data.availableBtc));
            Ele("trade.available.ltc").html(flexibleNumber(data.availableLtc));
            //冻结
            Ele("trade.frozen.cny").html(formatNumber(floor(data.frozenCny,2),2));
            Ele("trade.frozen.btc").html(flexibleNumber(data.frozenBtc));
            Ele("trade.frozen.ltc").html(flexibleNumber(data.frozenLtc));
            //小计
            Ele("asubtotalCny").html(formatNumber(floor(data.asubtotalCny,2),2));
            Ele("asubtotalBtc").html(flexibleNumber(data.asubtotalBtc));
            Ele("asubtotalLtc").html(flexibleNumber(data.asubtotalLtc));
            //end
            //合约账户
            Ele("futureaccount_bannerShow").html(formatNumber(floor(data.futureAllasset,2),2));//合约账户总资产
            Ele("fundValue_bannerShow").html(flexibleNumber(data.fundValue));//基金总资产
            Ele("lendValue_bannerShow").html(flexibleNumber(data.lendValue));//合约账户总资产
            //end

        }
        //行情信息
        this.renderingTicker = function (data) {
            //banner
            switch(data.symbol){
                case 0:
                    jQuery("#bannerBtcLast").html(Current.priceFormat(data.last));
                    jQuery("#bannerBtcVol").html(formatNumber(unFormatNumber(data.vol),0));
                    jQuery("#bannerAccountBtcLast").val(Number(data.last));
                    push.btclast = Number(data.last);
                    break;
                case 1:
                    jQuery("#bannerAccountLtcLast").val(Number(data.last));
                    push.ltclast = Number(data.last);
                    jQuery("#bannerLtcLast").html(Current.priceFormat(data.last,Globle.siteFlag==1?2:3));
                    jQuery("#bannerLtcVol").html(formatNumber(unFormatNumber(data.vol),0));//已经格式化过了 什么玩意
                    break;
                default:
                    //@todo add new coin
                    return;
            }
            //end
            //docement.title
            if (data.symbol == Globle.symbol) {
                var _cny_balance = Number(jQuery("#bannerUserCnyBalance").val());
                jQuery(".allBuyCoin").html(formatNumber(floor(accDiv(_cny_balance,data.last),4),4));
                var coinBlance=Number(jQuery("#bannerUserBtcBalance").val());
                if(Globle.symbol==1){
                    coinBlance = Number(jQuery("#bannerUserLtcBalance").val());
                }
                jQuery(".allSellMoney").html(formatNumber(floor(accMul(floor(coinBlance,4),data.last),2),2));
                var oldTitle = document.title;
                if (oldTitle != null && oldTitle.length > 0) {
                    var arrs = oldTitle.split("-");
                    var title = "";
                    var symbolPoint=SYMBOLS_UTIL.priceRate[Globle.symbol];
                    if (arrs.length == 3) {
                        if (pushjs1 == arrs[1]) {
                            title = Globle.symbolStr + ":" + Globle.currentSymbolStr + Current.priceFormat(data.last,symbolPoint) + "-" + arrs[1] + "-" + arrs[2];
                        } else {
                            title = Globle.symbolStr + ":" + Globle.currentSymbolStr +  Current.priceFormat(data.last,symbolPoint) + "-" + pushjs1 + "-" + arrs[1] + "-" + arrs[2];
                        }
                    } else {
                        if (typeof(arrs[2]) == 'undefined') {
                            title = Globle.symbolStr + ":" + Globle.currentSymbolStr +  Current.priceFormat(data.last,symbolPoint) + "-" + pushjs1 + "-" + arrs[1];
                        } else {
                            title = Globle.symbolStr + ":" + Globle.currentSymbolStr +  Current.priceFormat(data.last,symbolPoint) + "-" + pushjs1 + "-" + arrs[2] + "-" + arrs[3];
                        }
                    }
                    document.title = title;
                }
            }
            //end
        }
        //深度信息
        var  $sell=jQuery("#sell");
        var  $buy=jQuery("#buy");
        var  size=200;
        var sellStr=get$("depth_sell");
        this.renderingDepth = function (data) {
            if(!data){
                return;
            }
            if(!getAddDepthHtml.initDepth){
                getAddDepthHtml.initDepth=true;
                jQuery("#depthHidding").hide();
                var index=(size-1);
                var result="";
                while(index>=0){
                    result+=getAddDepthHtml(1,index);
                    index--;
                }
                $sell.append(result);
                index=0;
                result="";
                while(index<size){
                    result+=getAddDepthHtml(0,index);
                    index++;
                }
                $buy.append(result);
            }
            var medianUnit = sortDepth.medianUnit(data.buyDepthList, data.sellDepthList, 70);
            if(!!data.sellDepthList){
                var depthLength=data.sellDepthList.length;
                if(size>depthLength){
                    var sub=size-depthLength;
                    var tmp=0;
                    while(sub>0){
                        dEle("depthli1"+(depthLength+tmp)).style.display="none";
                        sub--;
                        tmp++;
                    }
                }
                editDepthHtml(data.sellDepthList,1,medianUnit);
            }
            if(!!data.buyDepthList){
                var depthLength=data.buyDepthList.length;
                if(size>depthLength){
                    var sub=size-depthLength;
                    var tmp=0;
                    while(sub>0){
                        dEle("depthli0"+(depthLength+tmp)).style.display="none";
                        sub--;
                        tmp++;
                    }
                }
                editDepthHtml(data.buyDepthList,0,medianUnit);
            }

        }
        var editDepthHtml = function (data, type,medianUnit) {
            var dataLength = data.length;
            for (var i = 0; i < dataLength; i++) {
                switch (type) {
                    case 0:
                        if (i >= Globle.showSize) {
                            dEle("depthli" + type + i).style.display = "none";
                        } else {
                            dEle("depthli" + type + i).style.display = "block";
                        }
                        break;
                    case 1:
                        if (i < (dataLength-Globle.showSize)) {
                            dEle("depthli" + type + i).style.display = "none";
                        }else{
                            dEle("depthli" + type + i).style.display = "block";
                        }
                        dEle("order" + type + i).innerHTML=sellStr+(dataLength-i);
                        break;
                }

                dEle("width" + type + i).style.width = getWidth(data[i][1],medianUnit) + "%";
                dEle("price" + type + i).innerHTML = formatNumber(data[i][0], Globle.symbolPricePoint);
                dEle("number" + type + i).innerHTML = formatNumber(data[i][1], Globle.symbolDepthAmountPoint);
            }
        }
        var getWidth = function (amount,medianUnit) {
            return sortDepth.width(amount,medianUnit);
        }
        var getAddDepthHtml = function (type,index) {
            var order = type==0?index+1:(size-index);
            var result = '<li id="depthli'+type+index+'" class="listCont '+(type==0?"clear":"")+'">';
            if(type==0){
                result += '<span id="order'+type+index+'" class="order">'+get$("depth_buy")+order+'</span>';
                result += '<span id="price'+type+index+'" class="price"></span>';
                result += '<span id="number'+type+index+'" class="number"></span>';
                result += '<span id="numberWidth'+type+index+'" class="numberWidth"><i id="width'+type+index+'"></i></span>';
            }else{
                result += '<span id="order'+type+index+'" class="order">'+sellStr+order+'</span>';
                result += '<span id="price'+type+index+'" class="price"></span>';
                result += '<span id="number'+type+index+'"  class="number"></span>';
                result += '<span id="numberWidth'+type+index+'" class="numberWidth"><i id="width'+type+index+'"></i></span>';
            }
            result += '</li>';
            return result;
        }
        var getHistoryHtml=function(data){
            var result='<li class="listCont">';
                result+='<span class="time">'+data[2]+'</span>';
                result+='<span class="price">'+formatNumber(data[0],Globle.symbolPricePoint)+'</span>';
                result+='<span class="number '+(data[3]==1?"green":"red")+' ">'+formatNumber(data[1],Globle.symbolDepthAmountPoint)+'</span>';
                result+=' </li>';
            return result
        }
        //交易记录
        this.renderingRecent = function (data) {
            if(!!data){
                var length=data.length;
                if(!this.isInit){
                    this.isInit=1;
                    var result='';
                    for(var i=(length-1);i>=0;i--){
                        result+=getHistoryHtml(data[i]);
                    }
                    jQuery("#history").html(result);
                }else{
                    if(!Globle.socket.isConnection()){//轮训时赋值操作
                        var result='';
                        for(var i=(length-1);i>=0;i--){
                            result+=getHistoryHtml(data[i]);
                        }
                        jQuery("#history").html(result);
                        return;
                    }
                    var $history=jQuery("#history");
                    var result='';
                    for(var i=0;i<length;i++){
                        $history.find('li').last().remove();
                        $history.prepend(getHistoryHtml(data[i]));
                    }

                }
            }

        }
        var getshowMsg=function(order){
            var msg=get$("turnover")+":"+Globle.currentSymbolStr+formatNumber(order.averagePrice,Globle.symbolPricePoint);
                msg+=get$("kline1_volume1")+":"+Globle.symbolStr+formatNumber(order.dealAmount,Globle.symbolOrderAmountPoint);
            return msg;
        }
        var getShowTite=function(order){
            var msg=SYMBOLS_UTIL.symbolStr2[Globle.symbol];
            msg+=order.tradeType==1?get$("immediatelybuy"):get$("immediatelysell");
            if(order.tradeCnyPrice == 1000000||order.tradeCnyPrice ==0) {
                msg +=get$("trade_entrust_ten_instant");
            }else{
                msg+=get$("futureplanentrust2");
            }            msg+=get$("alreadDeall");
            return msg;
        }
        this.renderingHandleTradeOrders=function(order){

            // 改为重新加载(推送待改进)
            return market.getTimerTask().startRefreshTradeOrders(0);

            //var t = -1;
            //jQuery(".operationTab").each(function(){
            //    if (jQuery(this).find("a").hasClass("cur")){
            //        t = jQuery(this).attr("type");
            //    }
            //});
            var status = order.status;
            if(status==2){
                NotifyMsg.showMsg(getShowTite(order),getshowMsg(order),function(){
                    jQuery(".operationTab").each(function(){
                        if (jQuery(this).attr("type") == 2) {
                            jQuery(this).click();
                            return;
                        }});
                });
            }
            //先获取一下当前取的是哪个tab，只有在未成交tab选择的时候，才会进行推送更新操作
            //if (t != 1) {
            //    return;
            //}

            var initLength = jQuery("#unfinishedOrdersList li").length;
            var id = order.id;
            var orderLi = jQuery("#undeal_"+id);

            if (orderLi.length > 0) {//列表中存在数据，进行删除，修改等操作
                if (status == 2 || status == -1||Number(order.unDealMoney)<=0||(Number(order.tradeAmount)-Number(order.dealAmount))<=0) {//已成交、撤单  需删除列表内的数据
                    jQuery(orderLi).remove();
                }
                if (status == 1) {//部分成交  需修改行内数据
                    var rate =round(accDiv((order.tradeAmount - order.unDealMoney),order.tradeAmount)*100,2);
                    if (order.tradeCnyPrice == 1000000 && order.tradeType == 1) {
                        jQuery(orderLi).find(".number").html(Globle.currentSymbolStr+" "+order.unDealMoney);
                    } else {
                        jQuery(orderLi).find(".number").html(Globle.symbolStr+" "+order.unDealMoney);
                    }
                    jQuery(orderLi).find("i").css("width",rate+"%");
                    // 更新状态文字为<部分成交>
                    jQuery(orderLi).find('.state').html(get$("trade_entrust_ten_partfulfilled"));
                }
            } else { //列表中不存在该数据，进行新增操作

                if (status == 2 || status == -1 || status == 1) {//已成交、撤单、部分成交，不进行新增操作
                    return;
                }

                var html = this.getUndealOrderHtml(order);
                jQuery("#unfinishedOrdersList").prepend(html);

            }

            //操作结束后，看列表中的条数，若大于3条，则循环删除最后一个，直到剩余3条
            var rowLength = jQuery("#unfinishedOrdersList li").length;
            if (rowLength > 3) {
                for (var i=0 ;i<rowLength-3;i++) {//循环删除最后一个
                    jQuery("#unfinishedOrdersList li").last().remove();
                }
            }
            //操作前列表为3条数据，删除操作结束后，页面小于3条，则进行一次轮训操作清空数据重新加载
            rowLength = jQuery("#unfinishedOrdersList li").length;
            if(initLength == 3 && rowLength == 2) {
                market.getTimerTask().startRefreshTradeOrders(0);
            }
            if (rowLength <= 0) {
                jQuery(".unfinishedEntrust").find(".entrustRecord").hide();
                jQuery(".unfinishedEntrust").find(".noRecord").show();
                jQuery(".unfinishedEntrust").find(".inLoading").hide();
            } else {
                jQuery(".unfinishedEntrust").find(".entrustRecord").show();
                jQuery(".unfinishedEntrust").find(".noRecord").hide();
                jQuery(".unfinishedEntrust").find(".inLoading").hide();
            }

        }

        this.renderingTradeOrders = function(data){

            if (data.length <= 0) {
                jQuery(".unfinishedEntrust").find(".entrustRecord").hide();
                jQuery(".unfinishedEntrust").find(".noRecord").show();
                jQuery(".unfinishedEntrust").find(".inLoading").hide();
            } else {
                jQuery(".unfinishedEntrust").find(".entrustRecord").show();
                jQuery(".unfinishedEntrust").find(".noRecord").hide();
                jQuery(".unfinishedEntrust").find(".inLoading").hide();
            }
            //清空列表
            jQuery("#unfinishedOrdersList").empty();
            var len = data.length;
            if (len > 3) {
                len = 3;
            }
            for (var i=0;i<len;i++) {
                var html = this.getUndealOrderHtml(data[i]);
                jQuery("#unfinishedOrdersList").append(html);
            }
        }
        this.renderingTradeOrdersFinish = function(data){

            if (data.length <= 0) {
                jQuery(".historyEntrust").find(".historyRecord").hide();
                jQuery(".historyEntrust").find(".noRecord").show();
            } else {
                jQuery(".historyEntrust").find(".historyRecord").show();
                jQuery(".historyEntrust").find(".noRecord").hide();
            }
            //清空列表
            jQuery("#finishedOrdersList").empty();
            for (var i=0;i<data.length;i++) {
                var html = this.getFinishOrderHtml(data[i]);
                jQuery("#finishedOrdersList").append(html);
            }


        }

        this.getUndealOrderHtml=function(order){
            var html = "";
            var styleStr = "";
            var priceStr = formatNumber(order.tradeCnyPrice,Globle.symbolPricePoint);
            if (order.tradeType == 1) {
                styleStr = "buy";
            } else {
                styleStr = "sell";
            }
            var rate = round(accDiv(order.dealAmount,order.tradeAmount)*100,2);
            var isMarket = false;
            if (order.tradeCnyPrice == 1000000 || order.tradeCnyPrice == 0) {
                priceStr = get$("trade_entrust_ten_instant");
                isMarket = true;
            }

            if (order.status == 0) {
                styleStr += " undeal";
            }

            html+="<li id=\"undeal_"+order.id+"\" class=\""+styleStr+"\">";
            html+="<i style=\"width: "+rate+"%;\"></i>";
            html+="<span class=\"price\"><em>"+(isMarket ? '' : Globle.currentSymbolStr)+"</em>"+priceStr+"</span>";
            html+="<span class=\"number\">"+Globle.symbolStr+" "+formatNumber(order.tradeAmount-order.dealAmount,Globle.symbolOrderAmountPoint)+"</span>";
            html+="<span class=\"state\">"+(order.status ==0 ?get$("market_entrust_undeal"):get$("trade_entrust_ten_partfulfilled")) +"</span>";
            html+="<span class=\"operate\"><a class=\"cancle orderCancel\" order-id=\""+order.id+"\">"+get$("trade_entrust_ten_cancel")+"</a></span>";
            html+="</li>";
            return html;
        }
        this.getFinishOrderHtml=function(order){
            var html = "";
            var styleStr = "";
            var titleStr = "";
            var cancelStr = "";
            var priceStr = "<em>"+Globle.currentSymbolStr+"</em>"+formatNumber(order.tradeCnyPrice,Globle.symbolPricePoint);
            var amountStr = "<em>"+Globle.symbolStr+"</em>"+formatNumber(order.tradeAmount,Globle.symbolOrderAmountPoint);
            if (order.tradeType == 1) {
                styleStr = "buyTitle";
                titleStr = get$("trade_entrust_ten_bid");
            } else {
                styleStr = "sellTitle";
                titleStr = get$("trade_entrust_ten_ask");
            }
            if (order.status == -1) {
                cancelStr = "<em>"+get$("cancelled")+"</em>";
            }
            if (order.tradeCnyPrice == 1000000 || order.tradeCnyPrice == 0) {
                priceStr = get$("trade_entrust_ten_instant");
                if(order.tradeType == 1) {
                    amountStr = "<em>"+Globle.currentSymbolStr+"</em>" + formatNumber(order.allMoney,Globle.symbolPricePoint);
                }
            }
            html+="<li id=\"finish_"+order.id+"\" class=\"list\">";
            html+="<div>";
            html+="<span class=\""+styleStr+"\">"+titleStr+cancelStr+"</span>";
            html+="<span class=\"tNumber\">"+order.createdDate+"</span>";
            html+="</div>";
            html+="<div>";
            html+="<span class=\"title\">"+get$("market_entrust_havedeal")+"</span>";
            html+="<span class=\"number\"><em>"+Globle.symbolStr+"</em>"+formatNumber(order.dealAmount,Globle.symbolOrderAmountPoint)+"</span>";
            html+="</div>";
            html+="<div>";
            html+="<span class=\"title\">"+get$("market_entrust_dealprice")+"</span>";
            html+="<span class=\"number\"><em>"+Globle.currentSymbolStr+"</em>"+CommaFormattedByOriginal(order.averagePrice,4)+"</span>";
            html+="</div>";
            html+="<div>";
            html+="<span class=\"title\">"+get$("market_entrust_amount")+"</span>";
            html+="<span class=\"number\">"+amountStr+"</span>";
            html+="</div>";
            html+="<div>";
            html+="<span class=\"title\">"+get$("market_entrust_price")+"</span>";
            html+="<span class=\"number\">"+priceStr+"</span>";
            html+="</div>";
            html+="</li>";
            return html;
        }
    }
}
//-----------------kline用到的 未更改
var cmd = '';
var marketFrom = '0';
var type = '2';
var coinVol = '1';
function PushFrom(contractType,marketFrom_, type_, coinVol_, time) {
    marketFrom = marketFrom_;
    type = type_;
    coinVol = coinVol_;
    var socket=Globle.socket.socket;
    if (cmd != ''){
        if(!!socket){
            socket.emit("removePushType", cmd);
        }
    }
    cmd = '{millInterval : 300, type : "ok_';
    switch (contractType) {
        case 'btc_spot':
            cmd += 'btc_kline_';
            break;
        case 'ltc_spot':
            cmd += 'ltc_kline_';
            break;
        case 'btc_index':
            cmd+="future_btc_kline_index_";
            break;
        case 'ltc_index':
            cmd+="future_ltc_kline_index_";
            break;
        case 'btc_this_week':
            cmd+="future_btc_kline_this_week_";
            break;
        case 'btc_next_week':
            cmd+="future_btc_kline_next_week_";
            break;
        case 'btc_quarter':
            cmd+="future_btc_kline_quarter_";
            break;
        case 'ltc_this_week':
            cmd+="future_ltc_kline_this_week_";
            break;
        case 'ltc_next_week':
            cmd+="future_ltc_kline_next_week_";
            break;
        case 'ltc_quarter':
            cmd+="future_ltc_kline_quarter_";
            break;
        default :
            cmd += 'btc_kline_';
            break;
    }
    switch (type) {
        case '0':
            cmd += '1min';
            break;
        case '1':
            cmd += '5min';
            break;
        case '2':
            cmd += '15min';
            break;
        case '3':
            cmd += 'day';
            break;
        case '4':
            cmd += 'week';
            break;
        case '7':
            cmd += '3min';
            break;
        case '9':
            cmd += '30min';
            break;
        case '10':
            cmd += '1hour';
            break;
        case '11':
            cmd += '2hour';
            break;
        case '12':
            cmd += '4hour';
            break;
        case '13':
            cmd += '6hour';
            break;
        case '14':
            cmd += '12hour';
            break;
        case '15':
            cmd += '3day';
            break;
        default :
            cmd += '15min';
            break;
    }
    if(Number(coinVol)==0){
        cmd+="_coin"
    }
    cmd+='", binary : '+Globle.socket.isBinary+', since :';
    cmd += time + '}';
    if(!!socket){
        socket.emit("addPushType", cmd);
    }
}

//=======add类似做法，merge赋值d时候进行判断（hasClass）,赋值的时候附上class。此外，socketIO的时候添加到reStartMerge方法中
jQuery("#deptMerge_burst_btn").click(function(){
    //var $a=jQuery(this);
    var merge;
    jQuery("#deptMerge_burst_btn a").each(function(i, val){
            if (jQuery(val).hasClass('cur')) {
                merge = Number(jQuery(val).attr('code'));
            }
        });

    setCookieValue("deptMerge_stock",merge);
    //$a.attr("val",merge);
    market.reStartMerge(merge);
    jQuery("#deptMerge_burst_btn a").each(function(i, val){
        if ( merge == jQuery(val).attr('code') ) {
            jQuery(val).attr('class', 'cur');
        } else {
            jQuery(val).attr('class', '');
        }
    });
});
//=======end

jQuery("#merge").click(function(){
    var $a=jQuery(this);
    var merge=Number($a.attr("val"))==1?0:1;
    var preUrl = jQuery("#__pre_url").val();
    setCookieValue("deptMerge_stock",merge);
    $a.attr("val",merge);
    market.reStartMerge(merge);
    switch (merge) {
        case 0:
            jQuery("#entrustNumberId p").each(function(index,pro){
                if(index>Globle.showSize){
                    jQuery(pro).hide();
                }else{
                    jQuery(pro).show();
                }
            });
            $a.children("IMG").attr("src",preUrl+"/image/future/off_min.png");
            break;
        case 1:
            $a.children("IMG").attr("src",preUrl+"/image/future/on_min.png");
            jQuery("#entrustNumberId p").each(function(index,pro){
                if(index>19){
                    jQuery(pro).hide();
                }
            });
            break;
    }
});

/**
 * 操作选择tab切换事件
 */
//jQuery(".operationTab").click(function(){
//    jQuery(".tradePart").hide();
//    jQuery(".unfinishedEntrust").hide();
//    jQuery(".historyEntrust").hide();
//
//    jQuery(this).parent().find("li a").removeClass("cur");
//    jQuery(this).find("a").addClass("cur");
//
//    var type = jQuery(this).attr("type");
//    if (type == 0) {//交易选择
//        jQuery(".tradePart").show();
//    } else if (type == 1) {//未成交选择
//        jQuery(".unfinishedEntrust").show();
//        market.getTimerTask().startRefreshTradeOrders(0);
//    } else if (type == 2) {//已成交选择
//        jQuery(".historyEntrust").show();
//        market.getTimerTask().startRefreshTradeOrders(1);
//    }
//
//
//
//});

// 推送未成交记录：线上推送跟测试环境轮询不同，须主动触发
market.getTimerTask().startRefreshTradeOrders(0);

/**
 * 限价、市价tab切换事件
 */
jQuery(".tradeTypeTab").click(function(){
    var type = jQuery(this).attr("type");

    jQuery(this).parent().find("li a").removeClass("cur");
    jQuery(this).find("a").addClass("cur");

    if (type == 0) { //限价

        jQuery("#partValue").val(0);
        jQuery("#limitedPartValueDiv").find("a").removeClass("cur");
        jQuery("#tradeAmount").removeClass("disabled");
        jQuery("#tradeMoney").removeClass("disabled");

        jQuery("#tradeAmount").val("");//交易数量
        jQuery("#tradeMoney").val("");//交易数量

        jQuery(".limitOrderTrade").show();
        jQuery(".marketOrderTrade").hide();
    } else {//市价

        jQuery("#marketBuyAmount").removeClass("disabled");
        jQuery("#marketBuyAmount").val("");
        jQuery("#marketSellAmount").removeClass("disabled");
        jQuery("#marketSellAmount").val("");

        jQuery("#marketPartValueDiv_buy").find("a").removeClass("cur");
        jQuery("#partValue_buy").val("0");
        jQuery("#marketPartValueDiv_sell").find("a").removeClass("cur");
        jQuery("#partValue_sell").val("0");

        jQuery(".limitOrderTrade").hide();
        jQuery(".marketOrderTrade").show();
    }

    limitSlider.setPercent(0);
    marketSlider.setPercent(0);

    jQuery('#limitBuyAmountValue').html('0.00');
    jQuery('#limitSellAmountValue').html('0.0000');
    jQuery('#marketBuyAmountValue').html('0.00');
    jQuery('#marketSellAmountValue').html('0.0000');

});


/**
 * 限价 价格、数量、金额输入框输入计算
 */
jQuery(".tradeInput").keyup(function(e){
    limitedAlertTips("");

    if (this.value == '.') return;
    if (this.value == '..') {
        this.value = '.';
        return;
    }

    // 特殊字符
    var notAllowed = e.keyCode && (e.keyCode==17 || e.keyCode==39);
    if (notAllowed) return;

    var inputType = jQuery(this).attr("input-type");
    var symbol = jQuery("#symbol").val();

    if (inputType == 1) {
        // 价格：国际站LTC取3位 其他情况取2位
        checkNumberByObj(this, (site_flag == 2 && symbol == 1) ? 3 : 2);
    } else if (inputType == 3) {
        // 金额取2位
        checkNumberByObj(this, 2);
    } else {
        // 数量取4位
        checkNumberByObj(this,4);
    }

    var partValue = jQuery("#partValue").val();
    if (partValue != 0) {//已选中下单比例，不需要计算
        return;
    }

    var tradePrice = jQuery("#tradePrice").val();
    var tradeAmount = jQuery("#tradeAmount").val();

    if (inputType == 1) {//价格限制
        if (Number(tradePrice) > 10000000) {
            tradePrice = 1000000;
            jQuery("#tradePrice").val(tradePrice);
        }
    }
    if (!!tradeAmount && inputType == 2) {//数量限制
        if (Number(tradeAmount) > 10000000) {
            tradeAmount = 10000000;
            jQuery("#tradeAmount").val(tradeAmount);
        }
        var limitedAmount = SYMBOLS_UTIL.buyPriceLimt[symbol];
        var symbolStr = SYMBOLS_UTIL.symbolStr2[symbol];
        if (tradeAmount > 0 && Number(tradeAmount) < limitedAmount) {
            if (symbol == 0) {
                limitedAlertTips(get$("buytradejs6").replace("@",limitedAmount))
            } else if(symbol==1) {
                limitedAlertTips(get$("buytradejs5").replace("@",limitedAmount))
            }
        }
    }

    if (inputType == 3) {//金额输入计算
        var tradeMoney = jQuery("#tradeMoney").val();
        tradeAmount = floor(accDiv(tradeMoney,tradePrice),4);
        jQuery("#tradeAmount").val(tradeAmount);
    }

    if (!tradePrice || !tradeAmount) {
        jQuery('#limitBuyAmountValue').html('0');
        jQuery('#limitSellAmountValue').html('0');
        return;
    }

    var tradeMoney = floor(accMul(tradePrice,tradeAmount),4);

    if (inputType != 3) {
        jQuery("#tradeMoney").val(tradeMoney);
    }

    jQuery('#limitBuyAmountValue').html(jQuery("#tradeMoney").val());
    jQuery('#limitSellAmountValue').html(jQuery("#tradeAmount").val());
});


/**
 * 市价 买卖  数量输入框限制，计算
 */
jQuery(".marketTradeInput").keyup(function(e){
    var symbol = jQuery("#symbol").val();
    var inputType = jQuery(this).attr("input-type");

    var symbolStr = SYMBOLS_UTIL.symbolStr2[symbol];
    var point = inputType == 1 ? 2 : 4;

    if (this.value == '.') return;
    if (this.value == '..') {
        this.value = '.';
        return;
    }

    // 特殊字符
    var notAllowed = e.keyCode && (e.keyCode==17 || e.keyCode==39);
    if (notAllowed) return;

    checkNumberByObj(this,point);//检查是否为数字

    var value = Number(jQuery(this).val());

    if (inputType == 1) {//买
        marketAlertTips("",1);
        var cnyBalance = jQuery("#bannerUserCnyBalance").val();
        if (value > cnyBalance) {
            marketAlertTips(get$("balanceisinsufficient"),1);
        }

        jQuery('#marketBuyAmountValue').html(value);

    } else if (inputType == 2) {//卖
        marketAlertTips("",2);
        var coinBalance = getCoinBalance();
        if (value > coinBalance) {
            if (symbol == 0) {
                marketAlertTips(get$("buytradejs2"),2);//BTC余额不足
            } else if(symbol==1) {
                marketAlertTips(get$("buytradejs3"),2);//LTC余额不足
            }
        }

        jQuery('#marketSellAmountValue').html(value);
    }

});

/**
 * 现金余额点击事件
 */
jQuery(".tradeCnyBalance").click(function(){

    clearPercentData();

    //清除输入框蒙层
    jQuery("#marketBuyAmount").removeClass("disabled");
    jQuery("#tradeAmount").removeClass("disabled");
    jQuery("#tradeMoney").removeClass("disabled");

    //市价买的输入框
    var cnyBalance = jQuery("#bannerUserCnyBalance").val();
    jQuery("#marketBuyAmount").val(floor(cnyBalance,2));

    jQuery("#tradeMoney").val(floor(cnyBalance,2));

    // 限价买入金额显示
    jQuery('#limitBuyAmountValue').html(jQuery("#tradeMoney").val());

    // 市价买入金额显示
    jQuery('#marketBuyAmountValue').html(jQuery("#marketBuyAmount").val());

    var tabLink = jQuery('.operationTab a.cur');
    var isMarket = tabLink.parent().attr('type') == '1';

    // 市价Tab 卖出数量文本框值显示
    if (isMarket) {
        var marketSellAmount = jQuery("#marketSellAmount").val();
        if (marketSellAmount != '') {
            // 市价卖出金额显示
            jQuery('#marketSellAmountValue').html(marketSellAmount);
        }
    }

    jQuery("#tradeAmount").val("");
    var tradePrice = jQuery("#tradePrice").val();
    if (tradePrice == null || tradePrice == "") {
        return;
    }

    var tradeAmount = floor(accDiv(cnyBalance,tradePrice),2);
    jQuery("#tradeAmount").val(tradeAmount);

    // 限价卖出数量显示
    jQuery('#limitSellAmountValue').html(jQuery("#tradeAmount").val());
});

/**
 * Coin余额点击事件
 */
jQuery(".tradeBtcOrLtcBalance").click(function(){

    clearPercentData();

    //清除输入框蒙层
    jQuery("#marketSellAmount").removeClass("disabled");
    jQuery("#tradeAmount").removeClass("disabled");
    jQuery("#tradeMoney").removeClass("disabled");

    var coinBalance = getCoinBalance();
    jQuery("#marketSellAmount").val(floor(coinBalance,4));
    //限价
    jQuery("#tradeAmount").val(floor(coinBalance,4));

    // 现价卖出数量显示
    jQuery('#limitSellAmountValue').html(jQuery("#tradeAmount").val());

    // 市价卖出数量显示
    jQuery('#marketSellAmountValue').html(jQuery("#marketSellAmount").val());

    var tabLink = jQuery('.operationTab a.cur');
    var isMarket = tabLink.parent().attr('type') == '1';

    // 市价Tab 取买入数量显示
    if (isMarket) {
        var marketBuyAmount = jQuery("#marketBuyAmount").val();
        if (marketBuyAmount != '') {
            // 市价买入金额显示
            jQuery('#marketBuyAmountValue').html(marketBuyAmount);
        }
    }

    jQuery("#tradeMoney").val("");
    var tradePrice = jQuery("#tradePrice").val();
    if (tradePrice == null || tradePrice == "") {
        return;
    }
    var tradeMoney = floor(accMul(tradePrice,coinBalance),2);
    jQuery("#tradeMoney").val(tradeMoney);

    // 限价买入金额显示
    jQuery('#limitBuyAmountValue').html(jQuery("#tradeMoney").val());
});

/**
 * 清除百分比相关数据
 */
function clearPercentData() {
    limitSlider.setPercent(0);
    marketSlider.setPercent(0);

    // 重置限价买入卖出显示
    jQuery('#limitBuyAmountValue').html('0.00');
    jQuery('#limitSellAmountValue').html('0.0000');

    // 重置市价买入卖出显示
    jQuery('#marketBuyAmountValue').html('0.00');
    jQuery('#marketSellAmountValue').html('0.0000');

    //清除下单比例
    jQuery("#partValue").val(0);
    jQuery("#partValue_buy").val("0");
    jQuery("#limitedPartValueDiv").find("a").removeClass("cur");
    jQuery("#partValue_sell").val("0");
    jQuery("#marketPartValueDiv_buy").find("a").removeClass("cur");
    jQuery("#marketPartValueDiv_sell").find("a").removeClass("cur");
}

/**
 * 限价 价格、数量、金额输入框获取焦点事件
 */
jQuery(".tradeInput").focus(function(){
    var inputType = jQuery(this).attr("input-type");
    if (inputType == 1) {
        return;
    }

    if (limitSlider.getCurrPercent() > 0) {
        jQuery('#limitBuyAmountValue').html('0.00');
        jQuery('#limitSellAmountValue').html('0.0000');
    }
    limitSlider.setPercent(0);

    jQuery("#partValue").val(0);
    jQuery("#limitedPartValueDiv").find("a").removeClass("cur");
    jQuery("#tradeAmount").removeClass("disabled");
    jQuery("#tradeMoney").removeClass("disabled");
});

/**
 * 市价 买卖  数量输入框获取焦点事件
 */
jQuery(".marketTradeInput").focus(function(){

    if (marketSlider.getCurrPercent() > 0) {
        jQuery('#marketBuyAmountValue').html('0.0');
        jQuery('#marketSellAmountValue').html('0.0000');
    }
    marketSlider.setPercent(0);

    jQuery(this).removeClass("disabled");
    var inputType = jQuery(this).attr("input-type");
    if (inputType == 1) {//买入
        jQuery("#marketPartValueDiv_buy").find("a").removeClass("cur");
        jQuery("#partValue_buy").val("0");
    } else {//卖出
        jQuery("#marketPartValueDiv_sell").find("a").removeClass("cur");
        jQuery("#partValue_sell").val("0");
    }
});


/**
 * 限价 下单比例点击事件
 */
jQuery(".partValue").click(function(){

    jQuery("#tradeAmount").val("");
    jQuery("#tradeMoney").val("");

    jQuery("#tradeAmount").addClass("disabled");
    jQuery("#tradeMoney").addClass("disabled");

    jQuery(this).parent().find("a").removeClass("cur");
    jQuery(this).addClass("cur");
    jQuery("#partValue").val(jQuery(this).attr("value"));

});

/**
 * 市价 下单比例点击事件
 */
jQuery(".market_partValue").click(function(){

    var type = jQuery(this).attr("type");
    var value = jQuery(this).attr("value");
    if (type == 0) {
        jQuery("#marketBuyAmount").addClass("disabled");
        jQuery("#partValue_buy").val(value);
    } else {
        jQuery("#marketSellAmount").addClass("disabled");
        jQuery("#partValue_sell").val(value);
    }

    jQuery(this).parent().find("a").removeClass("cur");
    jQuery(this).addClass("cur");
});

/**
 * 限价交易点击事件
 */
jQuery(".limitedBtn").click(function(){

    var symbol = jQuery("#symbol").val();
    var tradeType = jQuery(this).attr("trade-type");//交易类型
    var tradePrice = jQuery("#tradePrice").val();//交易价格
    var tradeAmount = jQuery("#tradeAmount").val();//交易数量
    var partValue = jQuery("#partValue").val();//下单比例
    var tradePwd = jQuery("#tradePassword").val();

    var tradePwdopen = jQuery("#tradePwdopen").val();//是否开启交易密码

    var lastPrice;
    if(symbol==0){
        lastPrice=jQuery("#bannerAccountBtcLast").val();
    }else if(symbol ==1 ){
        lastPrice=jQuery("#bannerAccountLtcLast").val();
    }
    //@todo add new coin
    var limitedAmount = SYMBOLS_UTIL.buyPriceLimt[symbol];
    var symbolStr = SYMBOLS_UTIL.symbolStr2[symbol];
    var reg = new RegExp("^[0-9]+\.{0,1}[0-9]{0,8}$");

    if (!reg.test(tradePrice)) {
        limitedAlertTips(get$("buytradejs7"));
        return;
    }

    if (tradePwdopen == 0 && (tradePwd == null || tradePwd == "")) {
        limitedAlertTips(get$("entertransactionpassword"));
        return;
    }

    if (tradeType == 0) { //买入
        var cnyBalance = Number(jQuery("#bannerUserCnyBalance").val());
        if (partValue != 0) {
            // 限价按比例买入截取2位
            tradeAmount = floor(accMul(accDiv(cnyBalance,tradePrice),partValue),2);
        }
        //判断非空和字符
        if (!reg.test(tradeAmount)) {
            limitedAlertTips(get$("market_entrust_pleasefillamount"));
            return;
        }
        //最小交易量判断
        if (tradeAmount < limitedAmount) {
            if (symbol == 0) {
                limitedAlertTips(get$("buytradejs6").replace("@",limitedAmount))
            } else if(symbol==1) {
                limitedAlertTips(get$("buytradejs5").replace("@",limitedAmount))
            }//@todo add new coin
            return;
        }
        //下单金额是否超出当前余额
        var tradeMoney = accMul(tradeAmount,tradePrice);
        if (tradeMoney > cnyBalance) {
            limitedAlertTips(get$("balanceisinsufficient"));
            return;
        }
    } else {//卖出
        var coinBalance = Number(getCoinBalance());
        if (partValue != 0) {
            tradeAmount = floor(accMul(coinBalance,partValue),4);
        }
        //判断非空和字符
        if (!reg.test(tradeAmount)) {
            limitedAlertTips(get$("market_entrust_pleasefillamount"));
            return;
        }
        //最小交易量判断
        if (tradeAmount < limitedAmount) {
            if (symbol == 0) {
                limitedAlertTips(get$("buytradejs6").replace("@",limitedAmount))
            } else if(symbol==1) {
                limitedAlertTips(get$("buytradejs5").replace("@",limitedAmount))
            }            //@todo add new coin
            return;
        }
        //下单金额是否超出当前余额
        if (tradeAmount > coinBalance) {
            if (symbol == 0) {
                limitedAlertTips(get$("buytradejs2"),2);//BTC余额不足
            } else if(symbol==1) {
                limitedAlertTips(get$("buytradejs3"),2);//LTC余额不足
            }//@todo add new coin

            return;
        }
    }

    var url = "";
    if(tradeType ==0){
        url = "/trade/buyBtcSubmit.do?random="+Math.round(Math.random()*100);
    }else{
        url = "/trade/sellBtcSubmit.do?random="+Math.round(Math.random()*100);
    }
    tradePwd = tradePwdopen == 1 ? "":tradePwd;
    var param={tradeAmount:tradeAmount,tradeCnyPrice:tradePrice,tradePwd:tradePwd,symbol:symbol,limited:0,isMarket:1};

    var callback = {okBack:function(){
        marketTrade_post(url,param,tradeType,symbol,0);
    },noBack:void(0)};


    var rate = accDiv(tradePrice,lastPrice);
    //买单价格超过最新成交价±20%
    if(tradeType == 0 && rate >= 1.02){
        okcoinAlert(get$("tradeorderbuyalert"),true,callback);
        return;
    }
    if(tradeType == 1 && rate <= 0.98){
        okcoinAlert(get$("tradeordersellalert"),true,callback);
        return;
    }

    marketTrade_post(url,param,tradeType,symbol,0);

});

jQuery(".marketBtn").click(function(){

    var symbol = jQuery("#symbol").val();
    var lastPrice;
    if(symbol==0){
        lastPrice=jQuery("#bannerAccountBtcLast").val();
    }else if(symbol ==1 ){
        lastPrice=jQuery("#bannerAccountLtcLast").val();
    }
    //@todo add new coin
    var limitedAmount = SYMBOLS_UTIL.buyPriceLimt[symbol];
    var symbolStr = SYMBOLS_UTIL.symbolStr2[symbol];
    var reg = new RegExp("^[0-9]+\.{0,1}[0-9]{0,8}$");
    var tradeType = jQuery(this).attr("trade-type");
    var tradePwd = "";
    var tradePwdopen = jQuery("#tradePwdopen").val();//是否开启交易密码
    var tradeAmount = "";
    if (tradeType == 0) {//买入
        tradePwd = jQuery("#tradePassword_market").val();
        var cnyBalance = jQuery("#bannerUserCnyBalance").val();
        tradeAmount = jQuery("#marketBuyAmount").val();
        var partValue = jQuery("#partValue_buy").val();
        if (partValue != 0) {//下单比例选择
            // 市价按比例下单截取2位
            tradeAmount = floor(accMul(cnyBalance,partValue),2);
        }

        //判断非空和字符
        if (!reg.test(tradeAmount)) {
            marketAlertTips(get$("pleaseentertheamount"),1);
            return;
        }

        //最小金额判断
        var limitMoney = Number(accMul(limitedAmount,lastPrice));
        if (tradeAmount < limitMoney) {
            if(symbol == 0){
                marketAlertTips(get$("buytradejs21").replace("@",limitedAmount),1);
            }else if(symbol ==1){
                marketAlertTips(get$("buytradejs22").replace("@",limitedAmount),1);
            }//@todo  add new coin
            return;
        }

        //余额判断
        if (Number(tradeAmount) > Number(cnyBalance)) {
           marketAlertTips(get$("balanceisinsufficient"),1);
            return;
        }

    } else {//卖出
        tradePwd = jQuery("#tradePassword_market").val();
        var coinBalance = getCoinBalance();
        tradeAmount = jQuery("#marketSellAmount").val();
        var partValue = jQuery("#partValue_sell").val();
        if (partValue != 0) {
            tradeAmount = round(accMul(coinBalance,partValue),4);
        }

        //判断非空和字符
        if (!reg.test(tradeAmount)) {
            marketAlertTips(get$("market_entrust_pleasefillamount"),2);
            return;
        }

        if (tradeAmount < limitedAmount) {
            if (symbol == 0) {
                marketAlertTips(get$("selltradejs1").replace("@",limitedAmount),2);
            } else if(symbol==1){
                marketAlertTips(get$("selltradejs2").replace("@",limitedAmount),2);
            }//@todo  add new coin
            return;

        }

        if (Number(tradeAmount) > Number(coinBalance)) {
            if (symbol == 0) {
                marketAlertTips(get$("buytradejs2"),2);//BTC余额不足
            } else if(symbol==1) {
                marketAlertTips(get$("buytradejs3"),2);//LTC余额不足
            }//@todo  add new coin
            return;
        }

    }


    var url = "";
    if(tradeType ==0){
        url = "/trade/buyBtcSubmit.do?random="+Math.round(Math.random()*100);
    }else{
        url = "/trade/sellBtcSubmit.do?random="+Math.round(Math.random()*100);
    }
    tradePwd = tradePwdopen == 1 ? "":tradePwd;
    var param={tradeAmount:tradeAmount,tradeCnyPrice:0,tradePwd:tradePwd,symbol:symbol,limited:1,isMarket:1};

    marketTrade_post(url,param,tradeType,symbol,(tradeType+1));


});

jQuery(".orderCancel").live("click",function(){
    var entrustId = jQuery(this).attr("order-id");
    var url = "/trade/cancelEntrust.do?random="+Math.round(Math.random()*100);
    var param={entrustId:entrustId,symbol:Globle.symbol,isMarket:1};
    jQuery.post(url,param,function(data){
        var result = eval('(' + data + ')');

        _hmtPush(['_trackEvent', '行情图表', '撤单']);

        if(result!=null&&data==-10){
            jQuery("#cancelErrorTips").html(get$("reBackMoney"));
            window.setTimeout(function(){
                jQuery("#cancelErrorTips").html("");
            },5000);
        }
    });
});




var check = 1;
function marketTrade_post(url,param,tradeType,symbol,isLimited) {
    check = 2;
    jQuery.post(url,param,function(data){
        var json = eval('(' + data + ')');
        if(json==null){
            return ;
        }
        var result = json.result;
        if(result==null){
            result = eval('(' + data + ')');
        }
        if(result!=null){
            check =1;

            if (isLimited == 0) {
                jQuery("#tradePassword").val("");
            } else {
                jQuery("#tradePassword_market").val("");
            }

            //else if (isLimited == 1) {
            //    jQuery("#tradePassword_marketBuy").val("");
            //} else {
            //    jQuery("#tradePassword_marketSell").val("");
            //}

            if(result.resultCode == 0){

                // 埋点
                if (isLimited == 0) {
                    if (tradeType == 0) {
                        _hmtPush(['_trackEvent', '行情图表', '限价单买入']);
                    } else {
                        _hmtPush(['_trackEvent', '行情图表', '限价单卖出']);
                    }
                } else {
                    if (tradeType == 0) {
                        _hmtPush(['_trackEvent', '行情图表', '市价单买入']);
                    } else {
                        _hmtPush(['_trackEvent', '行情图表', '市价单卖出']);
                    }
                }

                limitSlider.setPercent(0);
                marketSlider.setPercent(0);

                jQuery('#limitBuyAmountValue').html('0.00');
                jQuery('#limitSellAmountValue').html('0.0000');
                jQuery('#marketBuyAmountValue').html('0.00');
                jQuery('#marketSellAmountValue').html('0.0000');

                //推送连接刷新tradeOrders
                if(typeof(isConnect) == 'undefined' || !isConnect){
                    //refreshTradeOrders();
                }
                var open = json.isOpen;
                if(open==1){
                    //这里需要把交易密码的输入框隐藏掉
                    jQuery("#tradePwdopen").val(open);

                    jQuery("#tradePasswordDiv").hide();
                    jQuery("#tradePassword_marketBuyDiv").hide();
                    jQuery("#tradePassword_marketSellDiv").hide();
                }


                //回复输入框初始状态
                if (isLimited == 0) {//限价
                    jQuery("#partValue").val(0);
                    jQuery("#limitedPartValueDiv").find("a").removeClass("cur");
                    jQuery("#tradeAmount").removeClass("disabled");
                    jQuery("#tradeMoney").removeClass("disabled");

                    jQuery("#tradeAmount").val("");//交易数量
                    jQuery("#tradeMoney").val("");//交易数量
                } else if (isLimited == 1) {//市价买
                    jQuery("#marketBuyAmount").removeClass("disabled");
                    jQuery("#marketBuyAmount").val("");
                    jQuery("#marketPartValueDiv_buy").find("a").removeClass("cur");
                    jQuery("#partValue_buy").val("0");
                } else if (isLimited == 1) {//市价卖
                    jQuery("#marketSellAmount").removeClass("disabled");
                    jQuery("#marketSellAmount").val("");
                    jQuery("#marketPartValueDiv_sell").find("a").removeClass("cur");
                    jQuery("#partValue_sell").val("0");
                }
                jQuery("#tradeAmount").focus();
                alertTips(get$("ordersuccess"),isLimited);
            } else if(result.resultCode == -10){
                alertTips(get$("reBackMoney"),isLimited);
            }else if(result.resultCode == -1){
                if(tradeType ==0){
                    if(symbol==1) {
                        alertTips(get$("buytradejs9").replace("@",0.01),isLimited);
                    } else if(symbol==0) {
                        alertTips(get$("buytradejs8").replace("@",0.1),isLimited);
                    }//@todo  add new coin
                }else{
                    if(symbol==1) {
                        alertTips(get$("selltradejs4"),isLimited);
                    }else if(symbol==0) {
                        alertTips(get$("selltradejs3"),isLimited);
                    }//@todo  add new coin
                }
            }else if(result.resultCode == -2){
                if(result.errorNum == 0){
                    alertTips(get$("buytradejs10"),isLimited);
                }else{
                    alertTips(get$("buytradejs11")+get$("youhave")+result.errorNum+get$("chancesleft"),isLimited);
                }

                if (isLimited == 0) {
                    jQuery("#tradePassword").val("");
                } else {
                    jQuery("#tradePassword_market").val("");
                }
                //else if (isLimited == 1) {
                //    jQuery("#tradePassword_marketBuy").val("");
                //} else {
                //    jQuery("#tradePassword_marketSell").val("");
                //}

            }else if(result.resultCode == -3){
                alertTips(get$("buytradejs13"),isLimited);
            }else if(result.resultCode == -4){
                alertTips(get$("buytradejs14"),isLimited);
            }else if(result.resultCode == -5){
                alertTips(get$("buytradejs15"),isLimited);
            }else if(result.resultCode == -6){
                alertTips(get$("buytradejs16"),isLimited);
            }else if(result.resultCode == -7){
                alertTips(get$("buytradejs17"),isLimited);
            }else if(result.resultCode == -8){
                alertTips(get$("entertransactionpassword"),isLimited);
            }else if(result.resultCode == 2){
                alertTips(get$("buytradejs19"),isLimited);
            }else if(result.resultCode == -200){
                klinealertTipsSpan(get$("youfreezebymasterxia"));
            }else if(result.resultCode == 3){
                alertTips(get$("buytradejs20"),isLimited);
            }else if (result.resultCode == -97) {
                alertTips(get$("tradesmallamountmore"),isLimited);
            }
        }
    });

}

jQuery(".cancelEntrustBatch").click(function(){
    jQuery(document).live("keyup",function(event){
        if(event.keyCode==13){
            jQuery("#alertOk").click();
        }
    });
    var callback = {okBack:function(){
        cancelBatch();
        jQuery(document).unbind("keyup");
    },noBack:function(){
        jQuery(document).unbind("keyup");
    }};
    var status = jQuery("#cancelBatchStatus").val();
    if (status == 0) {
        callback.okBack();
    } else {
        okcoinAlert(get$("cancelAllFutureComfirm"),true,callback);
    }
});

function cancelBatch(){
    var symbol = jQuery("#symbol").val();
    var url = "/trade/cancelEntrustBatch.do";
    var param = {symbol:symbol};
    jQuery.post(url,param,function(data){
        //alertTipsSpan2("");

        var result = eval('(' + data + ')');
        if(result!=null){

            _hmtPush(['_trackEvent', '行情图表', '批量撤单']);

            /*if (result.errorCode == 0) {
                alertTipsSpan2(get$("openorderscanceled"));
            } else if (result.errorCode == -10) {
                alertTipsSpan2(get$("noopenorders"));
            } else {
                alertTipsSpan2(get$("ordersnotcanceled"));
            }*/
        }

    });

}


function limitedAlertTips(content) {
    jQuery("#limitedErrorTips").html(content);
    if (limitedAlertTips.tipsTimeout != null) {
        clearTimeout(limitedAlertTips.tipsTimeout);
    }
    limitedAlertTips.tipsTimeout=setTimeout(function(){
        limitedAlertTips("");
    },5000);
}


function marketAlertTips(content,type) {
    var id = "";
    if (type == 1) {//市价买
        id = "marketErrorTips_buy";
    } else {//市价卖
        id = "marketErrorTips_sell";
    }
    //先清除
    jQuery("#marketErrorTips_buy").html("");
    jQuery("#marketErrorTips_sell").html("");

    //提示文字赋值
    jQuery("#"+id).html(content);

    //查看定时器是否存在，并清除
    if (marketAlertTips.tipsTimeout != null) {
        clearTimeout(marketAlertTips.tipsTimeout);
    }
    //设置定时器清楚文字
    marketAlertTips.tipsTimeout = setTimeout(function(){
        marketAlertTips("",type);
    },5000);
}

/**
 * 下单出提示文字，  0 ：限价  1：市价买 2：市价卖
 * @param content
 * @param type
 */
function alertTips(content,type){
    if (type == 0) {
        limitedAlertTips(content);
    } else {
        marketAlertTips(content,type);
    }
}

function getCoinBalance(){
    if (Globle.symbol == 0) {
        return jQuery("#bannerUserBtcBalance").val();
    } else if(Globle.symbol==1){
        return jQuery("#bannerUserLtcBalance").val();
    }//@todo  add new coin
}

//---------------------未修改代码待优化
/**
 * 核算并更新页面用户账户信息 suguangqiang
 */
function AccountingUserAccountInfo(){
    if(!islogin){
        return;
    }
    var account_bannerBtcLast = Number(document.getElementById("bannerAccountBtcLast").value);
    var account_bannerLtcLast = Number(document.getElementById("bannerAccountLtcLast").value);
    var account_bannerBtcBuy = Number(document.getElementById("bannerBtcBuy").value);
    var account_bannerBtcSell = Number(document.getElementById("bannerBtcSell").value);
    var account_bannerLtcBuy = Number(document.getElementById("bannerLtcBuy").value);
    var account_bannerLtcSell = Number(document.getElementById("bannerLtcSell").value);
    if(isNaN(account_bannerBtcLast)||isNaN(account_bannerLtcLast)||isNaN(account_bannerBtcBuy)||isNaN(account_bannerBtcSell)||isNaN(account_bannerLtcBuy)||isNaN(account_bannerLtcSell)){
        return ;
    }
    var account_Futureaccount = Number(document.getElementById('futureaccount_hidden').value);
    //合约使用 两个权益来计算合约账户余额
    var account_futrueBtcRights = Number(document.getElementById("bannerfutureAccountBtcRights").value);
    var account_futrueLtcRights = Number(document.getElementById("bannerfutureAccountLtcRights").value);
    var account_LendBtc = Number(document.getElementById("lendBtc").value);
    var account_LendLtc = Number(document.getElementById("lendLtc").value);
    var account_LendCny = Number(document.getElementById("lendCny").value);
    var account_bannerborrowsLtc = Number(document.getElementById("bannerborrowsLtc").value);
    var account_bannerborowsCny = Number(document.getElementById("bannerborowsCny").value);
    var account_bannerBinterestBtc = Number(document.getElementById("bannerBinterestBtc").value);
    var account_bannerBinterestLtc = Number(document.getElementById("bannerBinterestLtc").value);
    var account_bannerBinterestCny = Number(document.getElementById("bannerBinterestCny").value);

    var account_bannerUserCnyBalance = Number(document.getElementById("bannerUserCnyBalance").value);
    var account_bannerUserLtcBalance = Number(document.getElementById("bannerUserLtcBalance").value);
    var account_bannerUserBtcBalance = Number(document.getElementById("bannerUserBtcBalance").value);
    var account_bannerFreezeBtcBalance = Number(document.getElementById("bannerFreezeBtcBalance").value);
    var account_bannerFreezeLtcBalance = Number(document.getElementById("bannerFreezeLtcBalance").value);
    var account_bannerFreezeCnyBalance = Number(document.getElementById("bannerFreezeCnyBalance").value);
    var account_bannerBorrowBtcBalance = Number(document.getElementById("bannerBorrowBtcBalance").value);
    var account_bannerBorrowLtcBalance = Number(document.getElementById("bannerBorrowLtcBalance").value);
    var account_bannerBorrowCnyBalance = Number(document.getElementById("bannerBorrowCnyBalance").value);
    var account_bannerLendFreezeBtcBalance = Number(document.getElementById("bannerLendFreezeBtcBalance").value);
    var account_bannerLendFreezeLtcBalanced = Number(document.getElementById("bannerLendFreezeLtcBalanced").value);
    var account_bannerLendFreezeCnyBalance = Number(document.getElementById("bannerLendFreezeCnyBalance").value);
    var account_bannerFundBtcBalance = Number(document.getElementById("bannerFundBtcBalance").value);
    var account_bannerFundLtcBalance = Number(document.getElementById("bannerFundLtcBalance").value);
    var account_bannerLendedOutBtcBalance = Number(document.getElementById("bannerLendedOutBtcBalance").value);
    var account_bannerLendedOutLtcBalance = Number(document.getElementById("bannerLendedOutLtcBalance").value);
    var account_bannerLendedOutCnyBalance = Number( document.getElementById("bannerLendedOutCnyBalance").value);
    var futureaccountSum = accAdd(accMul(account_futrueBtcRights,account_bannerBtcLast),accMul(account_futrueLtcRights,account_bannerLtcLast));
    if(!isNaN(futureaccountSum)){
        account_Futureaccount = futureaccountSum;
    }
    //借贷账户 借出 BTC * 市价  LTC * 市价  + CNY
    var account_Sum_LendBtc = accAdd(accAdd(account_LendBtc,account_bannerLendFreezeBtcBalance),account_bannerLendedOutBtcBalance);
    var account_Sum_LendLtc = accAdd(accAdd(account_LendLtc,account_bannerLendFreezeLtcBalanced),account_bannerLendedOutLtcBalance);
    var account_Sum_LendCny = accAdd(accAdd(account_LendCny,account_bannerLendFreezeCnyBalance),account_bannerLendedOutCnyBalance);
    var account_lendValue = accAdd(accAdd(accMul(account_Sum_LendBtc,account_bannerBtcLast),accMul(account_Sum_LendLtc,account_bannerLtcLast)),account_Sum_LendCny);
    //交易账户BTC 可用BTC + 冻结BTC
    var  account_tradeBtc = accAdd(account_bannerUserBtcBalance,account_bannerFreezeBtcBalance);
    //交易账户LTC
    var  account_tradeLtc = accAdd(account_bannerUserLtcBalance,account_bannerFreezeLtcBalance);
    //交易账户 交易BTC * 市价 + 交易：LTC *市价　+可用CNY + 冻结CNY
    var account_tradeValue = accAdd(accAdd(accAdd(accMul(account_tradeBtc,account_bannerBtcLast),accMul(account_tradeLtc,account_bannerLtcLast)),account_bannerUserCnyBalance),account_bannerFreezeCnyBalance);
    //基金账户
    var account_fundValue = accAdd(accMul(account_bannerFundBtcBalance,account_bannerBtcLast),accMul(account_bannerFundLtcBalance,account_bannerLtcLast));
    //交易账户合计
    var account_asubtotalCnyValue = accAdd(account_bannerUserCnyBalance,account_bannerFreezeCnyBalance);
    var account_asubtotalBtcValue = accAdd(account_bannerFreezeBtcBalance,account_bannerUserBtcBalance);
    var account_asubtotalLtcValue = accAdd(account_bannerFreezeLtcBalance,account_bannerUserLtcBalance);
    //借贷合计
    var account_lsubtotalCnyValue = accAdd(accAdd(account_LendCny,account_bannerLendFreezeCnyBalance),account_bannerLendedOutCnyBalance);
    var account_lsubtotalBtcValue = accAdd(accAdd(account_LendBtc,account_bannerLendFreezeBtcBalance),account_bannerLendedOutBtcBalance);
    var account_lsubtotalLtcValue = accAdd(accAdd(account_LendLtc,account_bannerLendFreezeLtcBalanced),account_bannerLendedOutLtcBalance);
    //净资产CNY = 可用CNY + 冻结CNY -借款CNY - 减去利息
    var cny = accSub(accSub(accAdd(account_bannerUserCnyBalance,account_bannerFreezeCnyBalance),account_bannerBorrowCnyBalance),account_bannerBinterestCny);
    //净资产BTC = 可用BTC + 冻结BTC - 借款BTC - 利息BTC
    var btc = accSub(accSub(accAdd(account_bannerUserBtcBalance,account_bannerFreezeBtcBalance),account_bannerBorrowBtcBalance),account_bannerBinterestBtc);
    //净资产LTC = 可用LTC + 冻结LTC -借款LTC - 利息LTC
    var ltc = accSub(accSub(accAdd(account_bannerUserLtcBalance,account_bannerFreezeLtcBalance),account_bannerBorrowLtcBalance),account_bannerBinterestLtc);
    btc = accMul(btc, account_bannerBtcLast);
    ltc = accMul(ltc, account_bannerLtcLast);
    var account_uNetValue = accAdd(accAdd(cny,btc),ltc);

    //净资产
    var account_netasset = accAdd(accAdd(account_uNetValue,account_lendValue)<0?0:accAdd(accAdd(account_uNetValue,account_lendValue),account_fundValue),account_Futureaccount);
    //总资产
    var account_allasset = accAdd(accAdd(accAdd(account_tradeValue, account_lendValue),account_fundValue),account_Futureaccount);
    if(document.getElementById('futureaccount_bannerShow')!=null){
        document.getElementById('futureaccount_bannerShow').innerHTML=CommaFormatted(floor(account_Futureaccount,2),2);
    }
    if(document.getElementById('available.cny')!=null){
        //合约账户不做核算
        document.getElementById('available.cny').innerHTML=CommaFormatted(floor(account_bannerUserCnyBalance,2),2);
        document.getElementById('available.btc').innerHTML=flexibleNumber(account_bannerUserBtcBalance);
        document.getElementById('available.ltc').innerHTML=flexibleNumber(account_bannerUserLtcBalance);
        document.getElementById('frozen.cny').innerHTML=CommaFormatted(floor(account_bannerFreezeCnyBalance,2),2);
        document.getElementById('frozen.btc').innerHTML=flexibleNumber(account_bannerFreezeBtcBalance);
        document.getElementById('frozen.ltc').innerHTML=flexibleNumber(account_bannerFreezeLtcBalance);
        document.getElementById('allasset').innerHTML=CommaFormatted(floor(account_allasset,2),2);
        document.getElementById('netasset').innerHTML=CommaFormatted(floor(account_netasset,2),2);
        document.getElementById('tradeValue').innerHTML=CommaFormatted(floor(account_tradeValue,2),2);
        //account_uNetValue 若净资产< 0 那么显示 0
        document.getElementById('uNetValue').innerHTML=CommaFormatted(floor((account_uNetValue<0?0:account_uNetValue),2),2);
        document.getElementById('trade.available.cny').innerHTML=CommaFormatted(floor(account_bannerUserCnyBalance,2),2);
        document.getElementById('trade.available.btc').innerHTML=flexibleNumber(account_bannerUserBtcBalance);
        document.getElementById('trade.available.ltc').innerHTML=flexibleNumber(account_bannerUserLtcBalance);
        document.getElementById('trade.frozen.cny').innerHTML=CommaFormatted(floor(account_bannerFreezeCnyBalance,2),2);
        document.getElementById('trade.frozen.btc').innerHTML=flexibleNumber(account_bannerFreezeBtcBalance);
        document.getElementById('trade.frozen.ltc').innerHTML=flexibleNumber(account_bannerFreezeLtcBalance);
        if(document.getElementById('asubtotalCny')!=null){
            document.getElementById('asubtotalCny').innerHTML=CommaFormatted(floor(account_asubtotalCnyValue,2),2);
            document.getElementById('asubtotalBtc').innerHTML=flexibleNumber(account_asubtotalBtcValue);
            document.getElementById('asubtotalLtc').innerHTML=flexibleNumber(account_asubtotalLtcValue);
        }
        if(document.getElementById('fundValue_bannerShow')!=null){
            document.getElementById('fundValue_bannerShow').innerHTML = CommaFormatted(floor(account_fundValue,2),2);
        }
        if(document.getElementById('lendValue_bannerShow')!=null){
            document.getElementById('lendValue_bannerShow').innerHTML = CommaFormatted(floor(account_lendValue,2));
        }
    }
    var url = window.location.href;

    if(document.getElementById('canpush')!=null){

    }
    if(document.getElementById('canUseCny')!=null ){
        document.getElementById('canUseCny').innerHTML= CommaFormatted(floor(account_bannerUserCnyBalance,2),2);
    }
    if(document.getElementById('canBuyLTC')!=null){
        var canBuyLTC = '0.0000';
        if( push.ltclast !=0){
            canBuyLTC = CommaFormatted(floor(accDiv(account_bannerUserCnyBalance,account_bannerLtcLast),4),4);
            document.getElementById('canBuyLTC').innerHTML = canBuyLTC;
        }
    }
    if(document.getElementById('canSellLTC')!=null){
        document.getElementById('canSellLTC').innerHTML=CommaFormatted(floor(account_bannerUserLtcBalance,4),4);
    }
    if(document.getElementById('cangetLTCCny')!=null){
        var cangetLTCCny ="0.00"
        if( push.ltclast !=0) {
            cangetLTCCny = CommaFormatted(floor(accMul(floor(account_bannerUserLtcBalance,4), account_bannerLtcLast),2),2);
            document.getElementById('cangetLTCCny').innerHTML = cangetLTCCny;
        }
    }

    if(document.getElementById('userCnyBalance')!=null){
        document.getElementById('userCnyBalance').value=  account_bannerUserCnyBalance;
    }
    if(document.getElementById('canBuyBTC')!=null){
        var  canBuyBTC = '0.00';
        if( push.btclast !=0){
            canBuyBTC = CommaFormatted(floor(account_bannerUserCnyBalance/account_bannerBtcLast,4),4);
            document.getElementById('canBuyBTC').innerHTML = canBuyBTC;
        }
    }

    if(document.getElementById('canSellBTC')!=null){
        document.getElementById('canSellBTC').innerHTML=CommaFormatted(floor(account_bannerUserBtcBalance,4),4);
    }
    if(document.getElementById('canGetCny')!=null){

        if( account_bannerBtcLast !=0) {
            canGetCny = CommaFormatted(floor(accMul(account_bannerUserBtcBalance, account_bannerBtcLast), 2),2);
        }
    }

    if(document.getElementById("canpush")!=null){
        _ChangeBalance();
    }
}
//----------suguagnqiang end
/**
 *
 * @param pollingBannerUserAccountData 轮询用户账户json数据
 */
function bannerUserAccountPolling(pollingBannerUserAccountData){
    document.getElementById("lendBtc").value=pollingBannerUserAccountData.lendBtc;
    document.getElementById("lendLtc").value=pollingBannerUserAccountData.lendLtc;
    document.getElementById("lendCny").value=pollingBannerUserAccountData.lendCny;
    document.getElementById("bannerborrowsBtc").value=pollingBannerUserAccountData.bannerborrowsBtc;
    document.getElementById("bannerborrowsLtc").value=pollingBannerUserAccountData.bannerborrowsLtc;
    document.getElementById("bannerborowsCny").value=pollingBannerUserAccountData.bannerborowsCny;
    document.getElementById("bannerBinterestBtc").value=pollingBannerUserAccountData.bannerBinterestBtc;
    document.getElementById("bannerBinterestLtc").value=pollingBannerUserAccountData.bannerBinterestLtc;
    document.getElementById("bannerBinterestCny").value=pollingBannerUserAccountData.bannerBinterestCny;
    //更新新版
    jQuery(".tradeCnyBalance").html(formatNumber(floor(pollingBannerUserAccountData.bannerUserCnyBalance,2),2));
    switch (Globle.symbol){
        case 0:
            jQuery(".tradeBtcOrLtcBalance").html(formatNumber(floor(pollingBannerUserAccountData.bannerUserBtcBalance,4),4));
            break;
        case 1:
            jQuery(".tradeBtcOrLtcBalance").html(formatNumber(floor(pollingBannerUserAccountData.bannerUserLtcBalance,4),4));
            break;
        //@todo  add new coin
    }

    if(document.getElementById("bannerAccountBtcLast") && pollingBannerUserAccountData.bannerBtcLast!=null && pollingBannerUserAccountData.bannerBtcLast!='' && pollingBannerUserAccountData.bannerBtcLast!= 'undefined'){
        document.getElementById("bannerAccountBtcLast").value=pollingBannerUserAccountData.bannerBtcLast;
    }
    if(document.getElementById("bannerAccountLtcLast") && pollingBannerUserAccountData.bannerLtcLast!=null && pollingBannerUserAccountData.bannerLtcLast!='' && pollingBannerUserAccountData.bannerLtcLast!= 'undefined'){
        document.getElementById("bannerAccountLtcLast").value=pollingBannerUserAccountData.bannerLtcLast;
    }
    if(document.getElementById("bannerBtcBuy") && pollingBannerUserAccountData.bannerBtcBuy!=null && pollingBannerUserAccountData.bannerBtcBuy!='' && pollingBannerUserAccountData.bannerBtcBuy!= 'undefined'){
        document.getElementById("bannerBtcBuy").value=pollingBannerUserAccountData.bannerBtcBuy;
    }
    if(document.getElementById("bannerBtcSell") && pollingBannerUserAccountData.bannerBtcSell!=null && pollingBannerUserAccountData.bannerBtcSell!='' && pollingBannerUserAccountData.bannerBtcSell!= 'undefined'){
        document.getElementById("bannerBtcSell").value=pollingBannerUserAccountData.bannerBtcSell;
    }
    if(document.getElementById("bannerLtcBuy") && pollingBannerUserAccountData.bannerLtcBuy!=null && pollingBannerUserAccountData.bannerLtcBuy!='' && pollingBannerUserAccountData.bannerLtcBuy!= 'undefined'){
        document.getElementById("bannerLtcBuy").value=pollingBannerUserAccountData.bannerLtcBuy;
    }
    if(document.getElementById("bannerLtcSell") && pollingBannerUserAccountData.bannerLtcSell!=null && pollingBannerUserAccountData.bannerLtcSell!='' && pollingBannerUserAccountData.bannerLtcSell!= 'undefined'){
        document.getElementById("bannerLtcSell").value=pollingBannerUserAccountData.bannerLtcSell;
    }
    document.getElementById("bannerUserCnyBalance").value=pollingBannerUserAccountData.bannerUserCnyBalance;
    document.getElementById("bannerUserLtcBalance").value=pollingBannerUserAccountData.bannerUserLtcBalance;
    document.getElementById("bannerUserBtcBalance").value=pollingBannerUserAccountData.bannerUserBtcBalance;
    document.getElementById("bannerFreezeBtcBalance").value=pollingBannerUserAccountData.bannerFreezeBtcBalance;
    document.getElementById("bannerFreezeLtcBalance").value=pollingBannerUserAccountData.bannerFreezeLtcBalance;
    document.getElementById("bannerFreezeCnyBalance").value=pollingBannerUserAccountData.bannerFreezeCnyBalance;
    document.getElementById("bannerBorrowBtcBalance").value=pollingBannerUserAccountData.bannerBorrowBtcBalance;
    document.getElementById("bannerBorrowLtcBalance").value=pollingBannerUserAccountData.bannerBorrowLtcBalance;
    document.getElementById("bannerBorrowCnyBalance").value=pollingBannerUserAccountData.bannerBorrowCnyBalance;
    document.getElementById("bannerLendFreezeBtcBalance").value=pollingBannerUserAccountData.bannerLendFreezeBtcBalance;
    document.getElementById("bannerLendFreezeLtcBalanced").value=pollingBannerUserAccountData.bannerLendFreezeLtcBalanced;
    document.getElementById("bannerLendFreezeCnyBalance").value=pollingBannerUserAccountData.bannerLendFreezeCnyBalance;
    document.getElementById("bannerFundBtcBalance").value=pollingBannerUserAccountData.bannerFundBtcBalance;
    document.getElementById("bannerFundLtcBalance").value=pollingBannerUserAccountData.bannerFundLtcBalance;
    document.getElementById("bannerLendedOutBtcBalance").value = pollingBannerUserAccountData.bannerLendedOutBtcBalance;
    document.getElementById("bannerLendedOutLtcBalance").value = pollingBannerUserAccountData.bannerLendedOutLtcBalance;
    document.getElementById("bannerLendedOutCnyBalance").value = pollingBannerUserAccountData.bannerLendedOutCnyBalance;
    document.getElementById("bannerfutureAccountBtcRights").value=pollingBannerUserAccountData.bannerfutureAccountBtcRights;
    document.getElementById("bannerfutureAccountLtcRights").value=pollingBannerUserAccountData.bannerfutureAccountLtcRights;
}
/***
 * @param _money_CanUse 可用Cny
 * @param _amount_CanBuy 可买Coin
 * @param coin_CanSell 可卖币
 * @param _money_CanSell 卖币可得
 * @private
 */
function _ChangeBalance(){
    if(!islogin){
        return;
    }
    var _currentSymbol = document.getElementById('symbol').value;
    var _btc_last = Number(document.getElementById("bannerAccountBtcLast").value);
    var _ltc_last = Number(document.getElementById("bannerAccountLtcLast").value);
    var _btc_buy = Number(document.getElementById("bannerBtcBuy").value);
    var _btc_sell = Number(document.getElementById("bannerBtcSell").value);
    var _ltc_buy = Number(document.getElementById("bannerLtcBuy").value);
    var _ltc_sell = Number(document.getElementById("bannerLtcSell").value);
    var _cny_balance = Number(document.getElementById("bannerUserCnyBalance").value);
    var _ltc_balance = Number(document.getElementById("bannerUserLtcBalance").value);
    var _btc_balance = Number(document.getElementById("bannerUserBtcBalance").value);
    var _money_CanUse=0,_amount_CanBuy= 0,coin_CanSell= 0,_money_CanSell=0;
    if(_currentSymbol==0 || _currentSymbol=='0'){//btc
        _money_CanUse = floor(_cny_balance,2);
        coin_CanSell = floor(_btc_balance,4)
        if(_btc_last==0){
            _amount_CanBuy =0;
            _money_CanSell = 0;
        }else{
            _amount_CanBuy =floor(accDiv(floor(_cny_balance,2),_btc_last),4);
            _money_CanSell = floor(accMul(floor(_btc_balance,4),_btc_last),2);
        }
    }else if(_currentSymbol=='1' || _currentSymbol==1) {//ltc
        _money_CanUse = floor(_cny_balance, 2);
        coin_CanSell = floor(_ltc_balance, 4);

        if (_ltc_last == 0) {
            _amount_CanBuy = 0;
            _money_CanSell = 0;
        } else {
            _money_CanSell = floor(accMul(floor(_ltc_balance, 4), _ltc_last), 2);
            _amount_CanBuy = floor(accDiv(floor(_cny_balance, 2), _ltc_last), 4);
        }
    }//@todo  add new coin
    var tradeTypeValue = 0;
    if(document.getElementById('tradeType')!=null ){
        tradeTypeValue =  document.getElementById('tradeType').value;
    }
    if(_currentSymbol==0){
        if(tradeTypeValue==1){//sell btc
            if(document.getElementById("userBalance")!=null){
                document.getElementById("userBalance").value=floor(_btc_balance,4);
                document.getElementById("userCoinBalance").value=floor(_btc_balance,4);
            }
        }else if(tradeTypeValue==0){ //buy btc
            document.getElementById("nowPrice").value = push.btcsell;
            if(document.getElementById("userBalance")!=null){
                document.getElementById("userBalance").value=floor(_cny_balance,4);
            }
        }
    }
    if(_currentSymbol==1){
        if(tradeTypeValue==1){//sell btc
            if(document.getElementById("userBalance")!=null){
                document.getElementById("userBalance").value=floor(_ltc_balance,4);
                document.getElementById("userCoinBalance").value=floor(_ltc_balance,4);
            }
        }else if(tradeTypeValue==0){ //buy btc
            document.getElementById("nowPrice").value = push.ltcsell;
            if(document.getElementById("userBalance")!=null){
                document.getElementById("userBalance").value=floor(_cny_balance,4);
            }
        }
    }//@todo  add new coin
    if(document.getElementById("userCnyBalance")!=null){
        document.getElementById("userCnyBalance").value=_money_CanUse;
    }
    if(document.getElementById("cny")!=null){
        document.getElementById("cny").innerHTML=CommaFormatted(_money_CanUse,2);
    }
    if(document.getElementById("amount")!=null){
        document.getElementById("amount").innerHTML=CommaFormatted(_amount_CanBuy,4);
    }
    if(document.getElementById("klineuserBalance")!=null){
        document.getElementById("klineuserBalance").value=coin_CanSell;
    }
    if(document.getElementById("coinBalance")!=null){
        document.getElementById("coinBalance").innerHTML=CommaFormatted(coin_CanSell,4);
    }
    if(document.getElementById("klineuserCoinBalance")!=null){
        document.getElementById("klineuserCoinBalance").value=coin_CanSell;
    }
    if(document.getElementById("kmoney")!=null){
        document.getElementById("kmoney").innerHTML=CommaFormatted(_money_CanSell,2);
    }

}
//未修改代码end

//k线全屏开始-----------------------------------------------------------------------------------------------------------
function klineFullScreenOpen(){
    document.getElementById('marketImageNew').className="marketImageNewc clear";
    if(document.getElementById('changeKlineBackgound') != null ){
        document.getElementById('changeKlineBackgound').style.display="none";
    }
    document.getElementById('maxHeight').className="maxHeight";
    jQuery("#maxHeight").css("position","");
    document.getElementById('marketMainPart').className="maxHeight";
    document.getElementById('klineImage').className="maxHeight";
    document.getElementById('main').style.display="none";
    if(document.getElementById('blind') != null){
        document.getElementById('blind').style.display="none";
    }
    if(document.getElementById('nav_bg2') != null){
        document.getElementById('nav_bg2').style.display="none";
    }
    document.getElementById('buysellTitle').style.display="none";
    if(document.getElementById('new_bannerContentBg') != null ){
        document.getElementById('new_bannerContentBg').style.display="none";
    }
    if(document.getElementById('settingContentNew') != null ){
        document.getElementById('settingContentNew').style.display="none";
    }

    if(document.getElementById('marketRightPartOuter') != null ){
        document.getElementById('marketRightPartOuter').style.display="none";
    }
    if(document.getElementById('datalist') != null ){
        document.getElementById('datalist').style.display="none";
    }
    if(document.getElementById('realtimeboxBody') != null ){
        document.getElementById('realtimeboxBody').style.display="none";
    }
    document.getElementById('allFooter').style.display="none";
    if(document.getElementById('nav1') != null){
        document.getElementById('nav1').style.display="none";
    }
    document.getElementById('closefullscreen').style.display="block";
    document.getElementById('openfullscreen').style.display="none";
    if(document.getElementById('nologindiv') != null ){
        document.getElementById('nologindiv').style.display="none";
    }
    if(document.getElementById('bottomBody') != null ){
        document.getElementById('bottomBody').style.display="none";
    }

    if(window.klineFullScreen!=null){
        window.klineFullScreen.showDepth(0);
    }
    var str =  "klineIsOpen=1" ;
    document.cookie = str;
}

function klineFullScreenClose(){
    if(document.getElementById('marketImageNew') != null ){
        document.getElementById('marketImageNew').className="marketImageNew clear";
    }
    if(document.getElementById('changeKlineBackgound') != null ){
//		document.getElementById('changeKlineBackgound').style.display="block";
    }
    if(document.getElementById('maxHeight') != null){
        document.getElementById('maxHeight').className="";
    }
    jQuery("#maxHeight").css("position","relative");
    if(document.getElementById('marketMainPart') != null){
        document.getElementById('marketMainPart').className="marketMainPart";
    }

    if(document.getElementById('klineImage') != null){
        document.getElementById('klineImage').className="newKlineImage";
    }
    if(document.getElementById('main') != null){
        document.getElementById('main').style.display="block";
    }
    if(document.getElementById('marketRightPartOuter') != null ){
        document.getElementById('marketRightPartOuter').style.display="block";
    }
    if(document.getElementById('blind') != null){
        document.getElementById('blind').style.display="block";
    }
    if(document.getElementById('nav_bg2') != null){
        document.getElementById('nav_bg2').style.display="block";
    }
    if(document.getElementById('new_bannerContentBg') != null ){
        document.getElementById('new_bannerContentBg').style.display="block";
    }
    if(document.getElementById('buysellTitle') != null){
        document.getElementById('buysellTitle').style.display="block";
    }
    if(document.getElementById('settingContentNew') != null ){
        document.getElementById('settingContentNew').style.display="block";
    }
    if(document.getElementById('datalist') != null){
        document.getElementById('datalist').style.display="block";
    }
    if(document.getElementById('realtimeboxBody') != null){
        document.getElementById('realtimeboxBody').style.display="block";
    }
    if(document.getElementById('allFooter') != null){
        document.getElementById('allFooter').style.display="block";
    }
    if(document.getElementById('nav1') != null){
        document.getElementById('nav1').style.display="block";
    }
    if(document.getElementById('closefullscreen') != null){
        document.getElementById('closefullscreen').style.display="none";
    }
    if(document.getElementById('openfullscreen') != null){
        document.getElementById('openfullscreen').style.display="block";
    }
    if(document.getElementById('nologindiv') != null){
        document.getElementById('nologindiv').style.display="block";
    }
    if(document.getElementById('bottomBody') != null){
        document.getElementById('bottomBody').style.display="block";
    }
    if(window.klineFullScreen!=null&&!!window.klineFullScreen.showDepth){
        window.klineFullScreen.showDepth(1);
    }
    var str =  "klineIsOpen=0" ;
    document.cookie = str;
}

//k线全屏结束-----------------------------------------------------------------------------------------------------------

function toBuyOrSell(type,pro){
    jQuery(jQuery(".tradeTypeTab")[0]).click();
    jQuery(jQuery(".operationTab")[0]).click();
    var $li=jQuery(pro);
    var index=$li.index();
    var toAddAmount=0;
    var $lis=jQuery("#"+type+" li");
    for(var i=0;i<=index;i++){
        var number=jQuery($lis[i]).children("[class='number']").html();
        toAddAmount= accAdd(toAddAmount,unFormatNumber(number));
    }
    var price=unFormatNumber($li.children("[class='price']").html());
    if("buy"==type){
        var btcOrltcBlance= unFormatNumber(jQuery(".tradeBtcOrLtcBalance").html());
        if(Number(toAddAmount)>Number(btcOrltcBlance)){
            toAddAmount=btcOrltcBlance;
        }
    }else if("sell"==type){
        var tradeCnyBalance= unFormatNumber(jQuery(".tradeCnyBalance").html());
        tradeCnyBalance=floor(accDiv(tradeCnyBalance,price),4);
        if(Number(toAddAmount)>Number(tradeCnyBalance)){
            toAddAmount=tradeCnyBalance;
        }
    }
    jQuery("#tradePrice").val(price);
    jQuery("#tradeAmount").val(floor(toAddAmount,4));
    var tradeMoney = floor(accMul(price,toAddAmount),2);
    jQuery("#tradeMoney").val(tradeMoney);

    clearPercentData();

    // 买入金额显示
    jQuery('#limitBuyAmountValue').html(jQuery("#tradeMoney").val());
    // 卖出数量显示
    jQuery('#limitSellAmountValue').html(jQuery("#tradeAmount").val());
}
//未修改代码end
jQuery("#buy li").live("click",function(){
    toBuyOrSell("buy",this);
});//未修改代码end
jQuery("#sell li").live("click",function(){
    toBuyOrSell("sell",this);
});

window.scrollEnd=function(){
    if(1==Globle.merge){
        return;
    }
    if(!Globle.initSize){
        Globle.initSize=1;
    }else{
        Globle.initSize++;
    }
    Globle.showSize=(60*Globle.initSize)>200?200:(60*Globle.initSize);
    jQuery("#entrustNumberId p").each(function(index,pro){
        if(index>=Globle.showSize){
            jQuery(pro).hide();
        }else{
            jQuery(pro).show();
        }
    });
};

// 限价单比例滑动条
var limitSlider = new TradeSliderBar({
    width: 150,
    roundNumber: true,
    containerId: 'limitOrderSlider'
});
limitSlider.onPercentChange(function(percent) {
    console.log('limit:', percent);

    jQuery("#tradeAmount").val('').addClass("disabled");
    jQuery("#tradeMoney").val('').addClass("disabled");

    var floatValue = percent * 0.01;

    // 0.1234
    if (floatValue.toString().length > 6) {
        floatValue = floatValue.toFixed(4);
    }

    jQuery("#partValue").val(floatValue);

    var cny = jQuery('.limitOrderTrade .tradeCnyBalance').html().replace(/,/g, '');
    var btc = jQuery('.limitOrderTrade .tradeBtcOrLtcBalance').html().replace(/,/g, '');

    cny = cny * floatValue;
    btc = btc * floatValue;

    if (percent == 0) {
        jQuery('#limitBuyAmountValue').html('0.00');
        jQuery('#limitSellAmountValue').html('0.0000');
    } else {
        jQuery('#limitBuyAmountValue').html(floor(cny, 2));
        jQuery('#limitSellAmountValue').html(floor(btc, 4));
    }
});
limitSlider.onPercentChangeEnd(function(percent) {
    _hmtPush(['_trackEvent', '行情图表', '更改百分比']);
});

// 市价单比例滑动条
var marketSlider = new TradeSliderBar({
    width: 150,
    roundNumber: true,
    containerId: 'marketOrderSlider'
});
marketSlider.onPercentChange(function(percent) {
    console.log('market:', percent);

    jQuery("#marketBuyAmount").val('').addClass("disabled");
    jQuery("#marketSellAmount").val('').addClass("disabled");

    var floatValue = percent * 0.01;

    // 0.1234
    if (floatValue.toString().length > 6) {
        floatValue = floatValue.toFixed(4);
    }

    jQuery("#partValue_buy").val(floatValue);
    jQuery("#partValue_sell").val(floatValue);

    var cny = jQuery('.marketOrderTrade .tradeCnyBalance').html().replace(/,/g, '');
    var btc = jQuery('.marketOrderTrade .tradeBtcOrLtcBalance').html().replace(/,/g, '');

    cny = cny * floatValue;
    btc = btc * floatValue;

    if (percent == 0) {
        jQuery('#marketBuyAmountValue').html('0.00');
        jQuery('#marketSellAmountValue').html('0.0000');
    } else {
        jQuery('#marketBuyAmountValue').html(floor(cny, 2));
        jQuery('#marketSellAmountValue').html(floor(btc, 4));
    }
});
marketSlider.onPercentChangeEnd(function(percent) {
    _hmtPush(['_trackEvent', '行情图表', '更改百分比']);
});