<h1>Suggestion</h1>
<div class='field' id='set_cc_mail'><label><font color='#467500'>邮件抄送人</font></label>
    <div style="width:100%;" class="ui fluid multiple search selection dropdown noresult">
        <input type="hidden" id="cc_mail_textarea" value="">
        <input  autocomplete="off" tabindex="0" value="" class="search" placeholder="指定本项目所有发出邮件的抄送人,例如: wuxin,lijinsuo" style="width:50%">
        <div class="default text" id="git_test_input" class="scmpf_search">指定本项目所有发出邮件的抄送人,例如: wuxin,lijinsuo</div>
        <div class="menu"></div>
    </div>
</div>
<script src="js/jquery.js"></script>
<script>

    var suggestion_tmp = new Array();
    var suggestion_timeout = '';
    /// 重写的表情事件无效 重新绑定事件
    $(document).on('click', '.search', function(e) {
        if ($(this).hasClass('noresult')) {
            console.log($(this).children(':first').data('flag'));
            if (e.which == 8
                && $(this).children('input.search').val() == ''
                && $(this).children('a:last').html()
                && $(this).children('a:last').html().indexOf('config') !== -1
                && $(this).children(':first').data('flag') !== undefined) {
                $(this).children('a:last').remove();
            }
        }
    });

    $(document).on('input', '.search', function() {
        var _this   = $(this);
        var _parent = _this.parent();
        var _nnext  = _this.next().next();
        var val     = _this.val();

        if (_this && _this.length) {
            if (_this[0].tagName.toUpperCase() != 'INPUT' || !_parent.hasClass('noresult') || !val) {
                return;
            }
        }
        if (suggestion_timeout) {
            clearTimeout(suggestion_timeout);
        }
        if (_parent.data('time')) {
            var time = 300;
        } else {
            var time = 0;
            _parent.data('time', '1');
            _parent.children(":first").change(function() {
                var _first_this = $(this);
                _parent.children('.search').val('').focus();
                /// config页需要去除span
                setTimeout(function() {
                    _first_this.val(_first_this.val().replace(/<span>.*?<\/span>/g, ""));
                    var _first_val = _first_this.val();
                    if (_first_this.data('flag') != undefined
                        && _first_this.data('flag').length >= _first_val.length) {
                        _first_this.data('flag', _first_val);
                        return ;
                    }
                    /// 延迟100ms是为了解决删除的时候会一次删除多个的问题
                    setTimeout(function() { _first_this.data('flag', _first_val); }, 100);

                    var data_value = _parent.children('a:last').data('value')
                        .replace(/<span>.*?<\/span>/g, "");
                    _parent.children('a:last').data('value', data_value)
                        .html(data_value + '<i class="delete icon config"></i>');
                }, 0);
            });
            $(document).on('click', 'i.config', function() {
                $(this).parent().remove();
            });
        }

        suggestion_timeout = setTimeout(function () {
            $.ajax({
                async         : true,
                url           : "http://100.69.102.36:8081/ja/user/autoComplete.do",
                type          : "GET",
                dataType      : "jsonp",
                jsonp         : "callback",
                jsonpCallback : "fillData",
                retryLimit    : 3,
                tryCount      : 0,
                data          : "searchName="+val+"&start=0&count=100",
                timeout       : 1000,
                error         : function (xhr, textStatus) {
                    if ( textStatus == 'timeout'
                        || xhr.status >= 500) {
                        this.tryCount++;
                        if (this.tryCount < this.retryLimit) {
                            $.ajax(this);
                            return;
                        } else {
                            alert_s('异常', 'suggestion接口连接异常，请刷新页面后重新输入！')
                            return;
                        }
                    }
                },
                success       : function(data) {
                    var input_val = _parent.children(':first').val();
                    var input_val_arr = new Array();
                    if (input_val) {
                        input_val_arr = input_val.split(",");
                    }
                    var html = '';
                    var users = data.data.users;
                    for (var i=0; i <= users.length; i++) {
                        var i_user = users[i];
                        if (!i_user
                            || !i_user.hasOwnProperty('displayName') || i_user['displayName'] == ""
                            || !i_user.hasOwnProperty('emplid') || i_user['emplid'] == ""
                            || !i_user.hasOwnProperty('mail') || i_user['mail'] == ""
                            || !i_user.hasOwnProperty('department') || i_user['department'] == ""
                            || !i_user.hasOwnProperty('username') || i_user['username'] == ""
                            || $.inArray(i_user['username'], input_val_arr) !== -1) {
                            continue;
                        }
                        if (users[i]) {
                            var department_arr = i_user['department'].split(',');
                            html += '<div class="item" data-flag="suggestion">'+i_user['username']+'<span>('+i_user['displayName']+'('+department_arr[0]+'))</span></div>';
                        }
                    }
                    _nnext.html(html);
                    _parent.addClass('active').addClass('visible');
                    _nnext.addClass('transition').addClass('visible').show();
                }
            });
        }, time);
    });
</script>

