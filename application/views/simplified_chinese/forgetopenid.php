<?php
/*

This is an fraking email. Only inline style works and javascript will not work.
We downgrade ourselves to send only HTML fragment instead of standard HTML doc as most people use webmail anyway.

*/
?>

<div style="font: 0.92em sans-serif; background-color: #ffffff">
    <p>您好，</p>
    <p>来自 <?php print $ip ?> 的使用者查询了此 E-mail 帐号下，所有抓火狐页面所使用的 OpenID。
    您现在可以复制下列的 OpenID，至抓火狐网站<a href="<?php print site_url('#login');?>">登录</a>，重新编辑您的抓火狐页面。</p>
    <ol>
<?php
    foreach ($logins as $L) {
?>
<?php
        if (isset($L['name'])) {
?>
        <li>推荐页网址：<a href="<? print site_url($L['name']); ?>"><? print site_url($L['name']); ?></a>
<?php
        } else {
?>
        <li>推荐页网址：(尚未建立)</li>
<?php
        }
?>
            <ul>
                <li>OpenID 网址：<?php print htmlspecialchars($L['login']); ?></li>
                <li><form action="<?php print site_url('auth/login'); ?>" method="post">
                    <input type="hidden" name="openid-identifier" value="<?php print htmlspecialchars($L['login']); ?>" />
                    <input type="submit" value="直接登录" />
                </form></li><?php
?>
            </ul>
        </li>
<?php
    }
?>
    </ol>
    <p>若您忘记上列 OpenID 的密码，请迳向 OpenID 服务商查询。</p>
    <p>若您并没有向我们查询您使用过的 OpenID，<strong>请直接忽略此讯息，您的个人资料并没有被泄漏。</strong></p>
    <p>祝　顺心，</p>
    <p>抓火狐网站 同仁敬上</p>
    <hr />
    <p style="font-size: 0.82em; color: #cccccc">版权所有 &copy; gfx.tw</p>
</div>