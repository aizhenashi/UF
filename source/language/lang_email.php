<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: lang_email.php 27449 2012-02-01 05:32:35Z zhangguosheng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}


$lang = array
(
	'hello' => '您好',
	'moderate_member_invalidate' => '否决',
	'moderate_member_delete' => '删除',
	'moderate_member_validate' => '通过',


	'get_passwd_subject' =>		'取回密码说明',
	'get_passwd_message' =>		'
	<style type="text/css">
#Email-box{ border-top:5px solid #2f7ccc; width:800px; height:400px; margin:0 auto}
.email-logo{ width:125px; height:60px; margin:-4px 0 0 126px; position:relative}
#Email-box h2{ padding-top:15px;}
#Email-box h2,.email-name,.email-foot{ display:block; margin:0 0 0 160px; line-height:20px; font-size:12px; font-weight:normal}
.email-foot{ color:#999; padding-top:15px;}
.email-name a{ color:#2f7ccc; margin:5px 0}
</style>
<div id="Email-box">
   <div class="email-logo"><img src="http://www.uestar.cn/images/email-logo.jpg"></div>
   <h2>{username} 你好！</h2>
   <div class="email-name">
       你正在修改你的 优艺网盟 帐号密码，点击下方连接进入页面输入新密码即可<br />
      <a href="{siteurl}member.php?mod=getpasswd&amp;uid={uid}&amp;id={idstring}" target="_blank">{siteurl}member.php?mod=getpasswd&amp;uid={uid}&amp;id={idstring}</a><br />
       （该链接在3天内有效，3天后需要重新修改）
   </div>
   <div class="email-foot">
      如果以上连接无法访问，请将该网址复制粘贴至新的浏览器窗口中。<br />
      如果你错误地收到了此邮件，或你并未注册 优艺网盟 帐号请忽略并立即删除此邮件。
   </div>
</div>',


	'email_verify_subject' =>	'Email 地址验证',
	'email_verify_message' =>	'
	
	
	
	
	<style type="text/css">
#Email-box{ border-top:5px solid #2f7ccc; width:800px; height:400px; margin:0 auto}
.email-logo{ width:125px; height:60px; margin:-4px 0 0 126px; position:relative}
#Email-box h2{ padding-top:15px;}
#Email-box h2,.email-name,.email-foot{ display:block; margin:0 0 0 160px; line-height:20px; font-size:12px; font-weight:normal}
.email-foot{ color:#999; padding-top:15px;}
.email-name a{ color:#2f7ccc; margin:5px 0}
</style>
<div id="Email-box">
   <div class="email-logo"><img src="http://www.uestar.cn/images/email-logo.jpg"></div>
   <h2>{username}你好！</h2>
   <div class="email-name">
       请确认你的 优艺网盟（uestar.cn）帐号<br />
       &nbsp;&nbsp;&nbsp;&nbsp;确认你的帐号将使你获得 优艺网盟 全部服务，日后的推送也将发送至该邮件地址中。<br />
      <a href="{url}" target="_blank">{url}</a><br />
       （该链接在3天内有效，3天后需要重新注册）
   </div>
   <div class="email-foot">
      如果以上连接无法访问，请将该网址复制粘贴至新的浏览器窗口中。<br />
      如果你错误地收到了此邮件，或你并未注册 优艺网盟 帐号请忽略并立即删除此邮件。
   </div>
</div>
',


	'add_member_subject' =>		'您被添加成为会员',
	'add_member_message' => 	'
{newusername} ，
这封信是由 {bbname} 发送的。<br />
<br />
我是 {adminusername} ，{bbname} 的管理者之一。您收到这封邮件，是由于您<br />
刚刚被添加成为 {bbname} 的会员，当前 Email 即是我们为您注册的邮箱地址。<br />
<br />
----------------------------------------------------------------------<br />
重要！<br />
----------------------------------------------------------------------<br />
<br />
如果您对 {bbname} 不感兴趣或无意成为会员，请忽略这封邮件。<br />
<br />
----------------------------------------------------------------------<br />
帐号信息<br />
----------------------------------------------------------------------<br />
<br />
网站名称：{bbname}<br />
网站地址：{siteurl}<br />
<br />
用户名：{newusername}<br />
密码：{newpassword}<br />
<br />
从现在起您可以使用您的帐号登录 {bbname}，祝您使用愉快！<br />
<br />
<br />
<br />
此致<br />
<br />
{bbname} 管理团队.<br />
{siteurl}',


	'birthday_subject' =>		'祝您生日快乐',
	'birthday_message' => 		'<br />
{username}，<br />
这封信是由 {bbname} 发送的。<br />
<br />
您收到这封邮件，是由于这个邮箱地址在 {bbname} 被登记为用户邮箱，<br />
并且按照您填写的信息，今天是您的生日。很高兴能在此时为您献上一份<br />
生日祝福，我谨代表{bbname}管理团队，衷心祝福您生日快乐。<br />
<br />
如果您并非 {bbname} 的会员，或今天并非您的生日，可能是有人误用了您的邮<br />
件地址，或错误的填写了生日信息。本邮件不会多次重复发送，请忽略这封邮件。<br />
<br />
<br />
此致<br />
<br />
{bbname} 管理团队.<br />
{siteurl}',

	'email_to_friend_subject' =>	'{$_G[member][username]} 推荐给您: $thread[subject]',
	'email_to_friend_message' =>	'<br />
这封信是由 {$_G[setting][bbname]} 的 {$_G[member][username]} 发送的。<br />
<br />
您收到这封邮件，是由于在 {$_G[member][username]} 通过 {$_G[setting][bbname]} 的“推荐给朋友”<br />
功能推荐了如下的内容给您。如果您对此不感兴趣，请忽略这封邮件。您不需要退订或进行其他进一步的操作。<br />
<br />
----------------------------------------------------------------------<br />
信件原文开始<br />
----------------------------------------------------------------------<br />
<br />
$message<br />
<br />
----------------------------------------------------------------------<br />
信件原文结束<br />
----------------------------------------------------------------------<br />
<br />
请注意这封信仅仅是由用户使用 “推荐给朋友”发送的，不是网站官方邮件，<br />
网站管理团队不会对这类邮件负责。<br />
<br />
欢迎您访问 {$_G[setting][bbname]}<br />
$_G[siteurl]',

	'email_to_invite_subject' =>	'您的朋友 {$_G[member][username]} 发送 {$_G[setting][bbname]} 网站注册邀请码给您',
	'email_to_invite_message' =>	'<br />
$sendtoname,<br />
这封信是由 {$_G[setting][bbname]} 的 {$_G[member][username]} 发送的。<br />
<br />
您收到这封邮件，是由于 {$_G[member][username]} 通过 {bbname} 的“发送邀请码给朋友”<br />
功能推荐了如下的内容给您。如果您对此不感兴趣，请忽略这封邮件。您不需要退订或进行其他进<br />
一步的操作。<br />
<br />
----------------------------------------------------------------------<br />
信件原文开始<br />
----------------------------------------------------------------------<br />
<br />
$message<br />
<br />
----------------------------------------------------------------------<br />
信件原文结束<br />
----------------------------------------------------------------------<br />
<br />
请注意这封信仅仅是由用户使用 “发送邀请码给朋友”发送的，不是网站官方邮件，<br />
网站管理团队不会对这类邮件负责。<br />
<br />
欢迎您访问 {$_G[setting][bbname]}<br />
$_G[siteurl]',


	'moderate_member_subject' =>	'用户审核结果通知',
	'moderate_member_message' =>	'<br />
<p>{username},
这封信是由 {bbname} 发送的。</p>

<p>您收到这封邮件，是由于这个邮箱地址在 {bbname} 被新用户注册时所
使用，且管理员设置了对新用户需要进行人工审核，本邮件将通知您提交
申请的审核结果。</p>
<br />
----------------------------------------------------------------------<br />
<strong>注册信息与审核结果</strong><br />
----------------------------------------------------------------------<br />
<br />
用户名: {username}<br />
注册时间: {regdate}<br />
提交时间: {submitdate}<br />
提交次数: {submittimes}<br />
注册原因: {message}<br />
<br />
审核结果: {modresult}<br />
审核时间: {moddate}<br />
审核管理员: {adminusername}<br />
管理员留言: {remark}<br />
<br />
----------------------------------------------------------------------<br />
<strong>审核结果说明</strong><br />
----------------------------------------------------------------------<br />

<p>通过: 您的注册已通过审核，您已成为 {bbname} 的正式用户。</p>

<p>否决: 您的注册信息不完整，或未满足我们对新用户的某些要求，您可以
	  根据管理员留言，<a href="home.php?mod=spacecp&ac=profile" target="_blank">完善您的注册信息</a>，然后再次提交。</p>

<p>删除：您的注册由于与我们的要求偏差较大，或本站的新注册人数已
	  超过预期，申请已被否决。您的帐号已从数据库中删除，将无法
	  再使用其登录或提交再次审核，请您谅解。</p>

<br />
<br />
此致<br />
<br />
{bbname} 管理团队.<br />
{siteurl}',

	'adv_expiration_subject' =>	'您站点的广告将于 {day} 天后到期，请及时处理',
	'adv_expiration_message' =>	'您站点的以下广告将于 {day} 天后到期，请及时处理：<br /><br />{advs}',
	'invite_payment_email_message' => '
欢迎您光临{bbname}（{siteurl}），您的订单{orderid}已经支付完成，订单已确认有效。<br />
<br />----------------------------------------------------------------------<br />
以下是您获得的邀请码
<br />----------------------------------------------------------------------<br />

{codetext}

<br />----------------------------------------------------------------------<br />
重要！
<br />----------------------------------------------------------------------<br />',

	'email_cooperation_subject1' =>	'商业邀请',
	'email_cooperation_message1' =>	'
	<style type="text/css">
#Email-box{ border-top:5px solid #2f7ccc; width:800px; height:400px; margin:0 auto}
.email-logo{ width:125px; height:60px; margin:-4px 0 0 126px; position:relative}
#Email-box h2{ padding-top:15px;}
#Email-box h2,.email-name,.email-foot{ display:block; margin:0 0 0 160px; line-height:20px; font-size:12px; font-weight:normal}
.email-foot{ color:#999; padding-top:15px;}
.email-name a{ color:#2f7ccc; margin:5px 0}
</style>
<div id="Email-box">
   <div class="email-logo"><img src="http://www.uestar.cn/images/email-logo.jpg"></div>
   <h2>{name}你好！</h2>
   <div class="email-name">
       {m}诚邀与您合作。备注信息：<br />
       &nbsp;&nbsp;&nbsp;&nbsp;{cooperation_content}<br />
      <p style="text-align:right;"><a href="{url}" target="_blank">{bbname}</a></p>
   </div>
   <div class="email-foot">
      如果以上连接无法访问，请将该网址复制粘贴至新的浏览器窗口中。<br />
      如果你错误地收到了此邮件，或你并未注册 优艺网盟 帐号请忽略并立即删除此邮件。
   </div>
</div>
',
	'email_cooperation_subject2' =>	'职位申请',
	'email_cooperation_message2' =>	'
	<style type="text/css">
#Email-box{ border-top:5px solid #2f7ccc; width:800px; height:400px; margin:0 auto}
.email-logo{ width:125px; height:60px; margin:-4px 0 0 126px; position:relative}
#Email-box h2{ padding-top:15px;}
#Email-box h2,.email-name,.email-foot{ display:block; margin:0 0 0 160px; line-height:20px; font-size:12px; font-weight:normal}
.email-foot{ color:#999; padding-top:15px;}
.email-name a{ color:#2f7ccc; margin:5px 0}
</style>
<div id="Email-box">
   <div class="email-logo"><img src="http://www.uestar.cn/images/email-logo.jpg"></div>
   <h2>{name}你好！</h2>
   <div class="email-name">
       {m}向您发起职位申请。备注信息：<br />
       &nbsp;&nbsp;&nbsp;&nbsp;{cooperation_content}<br />
      <p style="text-align:right;"><a href="{url}" target="_blank">{bbname}</a></p>
   </div>
   <div class="email-foot">
      如果以上连接无法访问，请将该网址复制粘贴至新的浏览器窗口中。<br />
      如果你错误地收到了此邮件，或你并未注册 优艺网盟 帐号请忽略并立即删除此邮件。
   </div>
</div>
',

);

?>