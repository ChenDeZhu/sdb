
            /**
             * [添加委托信息]
             * @param {[type]} type [b:添加买入委托 s:添加卖出委托]
             */
            function add(type){
            	html = '';
            	$.each(data1[type], function(index, val) {
                    html +='<tr><td>'+val["price"]+'</td><td>'+val["number"]+'</td></tr>'
                });
                $('.'+type).html(html);
            }
            /**
             * [发起行为者的委托信息完成,改变资产]
             * @param  {[type]} type [0:买方发起的行为 1：卖方发起的行为]
             */
            function doneSelf(type){
                var money = $('#money').html();
                var currency = $('#currency').html();
                if(type == 0){
                    money = Number(money)-(Number(data1['price'])*Number(data1['number']));
                    currency = Number(currency)+Number(data1['number']);
                }else{
                    money = Number(money)+(Number(data1['price'])*Number(data1['number']));
                    currency = Number(currency)-Number(data1['number']);
                }
                money = money.toFixed(2);
                currency = currency.toFixed(6);
                $('#money').html(money);
                //可用货币修改
                $('#currency').html(currency); 
            }
            
            /**
             * [不存在符合条件的委托单或发起行为者的委托单部分完成]
             * @param  {[type]} type [0:买方发起的行为 1：卖方发起的行为]
             */
            function comPart(type){
                    var money = $('#money').html();
                    var currency = $('#currency').html();
                    var dcurrency = $('#dcurrency').html();
                    var dmoney = $('#dmoney').html();
                    
                    if (type==0) {
                        currency = Number(currency)-Number(data1['number']);        
                        money = Number(money)+Number(data1['price'])*(Number(data1['number'])-Number(data1['number_no']));
                        //修改冻结货币
                        dcurrency = Number(dcurrency)+Number(data1['number_no']);
                        dcurrency = dcurrency.toFixed(6);
                        $('#dcurrency').html(dcurrency); 
                        
                    }else{                   
                        currency = Number(currency)+Number(data1['number'])-Number(data1['number_no']);          
                        money = Number(money)-Number(data1['price'])*Number(data1['number']);            
                        dmoney = Number(dmoney)+Number(data1['number_no'])*Number(data1['price']);
                        dmoney = dmoney.toFixed(2);
                        $('#dmoney').html(dmoney); 
                        
                    }
                    money = money.toFixed(2);
                    currency = currency.toFixed(6);
                    $('#currency').html(currency);
                    $('#money').html(money);
            }
            /**
             * [发起行为者的委托信息完成,改变委托信息]
             * @param  {[type]} type [3：买方发起的行为 4：卖方发起的行为]
             */
            function doneSelfAll(type){
                if(type == 3){
                    data2 = data1['s'];
                    data3 = data1['b'];
                    type1 = 'buy';
                }else{
                    data2 = data1['b'];
                    data3 = data1['s'];
                    type1 = 'sale';
                }
                $("#"+data2['id']).html(data2['number_no']);
                html = '';
                html+='<tr><td>'+data3["price"]+'</td><td>'+data3["number"]+'</td><td id="'+data3['id']+'">'+data3["number_no"]+'</td></tr>'
                $('.'+type1).prepend(html);
                //添加到交易记录
                html1 = '<tr><td>'+data1["time"]+'</td><td>'+data3['price']+'</td><td class="deal'+type+'">'+Number(data3["number"])+'</td></tr>'
                $(".deal").prepend(html1);
            }