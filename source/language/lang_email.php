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
	'hello' => '����',
	'moderate_member_invalidate' => '���',
	'moderate_member_delete' => 'ɾ��',
	'moderate_member_validate' => 'ͨ��',


	'get_passwd_subject' =>		'ȡ������˵��',
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
   <h2>{username} ��ã�</h2>
   <div class="email-name">
       �������޸���� �������� �ʺ����룬����·����ӽ���ҳ�����������뼴��<br />
      <a href="{siteurl}member.php?mod=getpasswd&amp;uid={uid}&amp;id={idstring}" target="_blank">{siteurl}member.php?mod=getpasswd&amp;uid={uid}&amp;id={idstring}</a><br />
       ����������3������Ч��3�����Ҫ�����޸ģ�
   </div>
   <div class="email-foot">
      ������������޷����ʣ��뽫����ַ����ճ�����µ�����������С�<br />
      ����������յ��˴��ʼ������㲢δע�� �������� �ʺ�����Բ�����ɾ�����ʼ���
   </div>
</div>',


	'email_verify_subject' =>	'Email ��ַ��֤',
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
   <h2>{username}��ã�</h2>
   <div class="email-name">
       ��ȷ����� �������ˣ�uestar.cn���ʺ�<br />
       &nbsp;&nbsp;&nbsp;&nbsp;ȷ������ʺŽ�ʹ���� �������� ȫ�������պ������Ҳ�����������ʼ���ַ�С�<br />
      <a href="{url}" target="_blank">{url}</a><br />
       ����������3������Ч��3�����Ҫ����ע�ᣩ
   </div>
   <div class="email-foot">
      ������������޷����ʣ��뽫����ַ����ճ�����µ�����������С�<br />
      ����������յ��˴��ʼ������㲢δע�� �������� �ʺ�����Բ�����ɾ�����ʼ���
   </div>
</div>
',


	'add_member_subject' =>		'������ӳ�Ϊ��Ա',
	'add_member_message' => 	'
{newusername} ��
��������� {bbname} ���͵ġ�<br />
<br />
���� {adminusername} ��{bbname} �Ĺ�����֮һ�����յ�����ʼ�����������<br />
�ոձ���ӳ�Ϊ {bbname} �Ļ�Ա����ǰ Email ��������Ϊ��ע��������ַ��<br />
<br />
----------------------------------------------------------------------<br />
��Ҫ��<br />
----------------------------------------------------------------------<br />
<br />
������� {bbname} ������Ȥ�������Ϊ��Ա�����������ʼ���<br />
<br />
----------------------------------------------------------------------<br />
�ʺ���Ϣ<br />
----------------------------------------------------------------------<br />
<br />
��վ���ƣ�{bbname}<br />
��վ��ַ��{siteurl}<br />
<br />
�û�����{newusername}<br />
���룺{newpassword}<br />
<br />
��������������ʹ�������ʺŵ�¼ {bbname}��ף��ʹ����죡<br />
<br />
<br />
<br />
����<br />
<br />
{bbname} �����Ŷ�.<br />
{siteurl}',


	'birthday_subject' =>		'ף�����տ���',
	'birthday_message' => 		'<br />
{username}��<br />
��������� {bbname} ���͵ġ�<br />
<br />
���յ�����ʼ�����������������ַ�� {bbname} ���Ǽ�Ϊ�û����䣬<br />
���Ұ�������д����Ϣ���������������ա��ܸ������ڴ�ʱΪ������һ��<br />
����ף�����ҽ�����{bbname}�����Ŷӣ�����ף�������տ��֡�<br />
<br />
��������� {bbname} �Ļ�Ա������첢���������գ�����������������������<br />
����ַ����������д��������Ϣ�����ʼ��������ظ����ͣ����������ʼ���<br />
<br />
<br />
����<br />
<br />
{bbname} �����Ŷ�.<br />
{siteurl}',

	'email_to_friend_subject' =>	'{$_G[member][username]} �Ƽ�����: $thread[subject]',
	'email_to_friend_message' =>	'<br />
��������� {$_G[setting][bbname]} �� {$_G[member][username]} ���͵ġ�<br />
<br />
���յ�����ʼ����������� {$_G[member][username]} ͨ�� {$_G[setting][bbname]} �ġ��Ƽ������ѡ�<br />
�����Ƽ������µ����ݸ�����������Դ˲�����Ȥ�����������ʼ���������Ҫ�˶������������һ���Ĳ�����<br />
<br />
----------------------------------------------------------------------<br />
�ż�ԭ�Ŀ�ʼ<br />
----------------------------------------------------------------------<br />
<br />
$message<br />
<br />
----------------------------------------------------------------------<br />
�ż�ԭ�Ľ���<br />
----------------------------------------------------------------------<br />
<br />
��ע������Ž��������û�ʹ�� ���Ƽ������ѡ����͵ģ�������վ�ٷ��ʼ���<br />
��վ�����ŶӲ���������ʼ�����<br />
<br />
��ӭ������ {$_G[setting][bbname]}<br />
$_G[siteurl]',

	'email_to_invite_subject' =>	'�������� {$_G[member][username]} ���� {$_G[setting][bbname]} ��վע�����������',
	'email_to_invite_message' =>	'<br />
$sendtoname,<br />
��������� {$_G[setting][bbname]} �� {$_G[member][username]} ���͵ġ�<br />
<br />
���յ�����ʼ��������� {$_G[member][username]} ͨ�� {bbname} �ġ���������������ѡ�<br />
�����Ƽ������µ����ݸ�����������Դ˲�����Ȥ�����������ʼ���������Ҫ�˶������������<br />
һ���Ĳ�����<br />
<br />
----------------------------------------------------------------------<br />
�ż�ԭ�Ŀ�ʼ<br />
----------------------------------------------------------------------<br />
<br />
$message<br />
<br />
----------------------------------------------------------------------<br />
�ż�ԭ�Ľ���<br />
----------------------------------------------------------------------<br />
<br />
��ע������Ž��������û�ʹ�� ����������������ѡ����͵ģ�������վ�ٷ��ʼ���<br />
��վ�����ŶӲ���������ʼ�����<br />
<br />
��ӭ������ {$_G[setting][bbname]}<br />
$_G[siteurl]',


	'moderate_member_subject' =>	'�û���˽��֪ͨ',
	'moderate_member_message' =>	'<br />
<p>{username},
��������� {bbname} ���͵ġ�</p>

<p>���յ�����ʼ�����������������ַ�� {bbname} �����û�ע��ʱ��
ʹ�ã��ҹ���Ա�����˶����û���Ҫ�����˹���ˣ����ʼ���֪ͨ���ύ
�������˽����</p>
<br />
----------------------------------------------------------------------<br />
<strong>ע����Ϣ����˽��</strong><br />
----------------------------------------------------------------------<br />
<br />
�û���: {username}<br />
ע��ʱ��: {regdate}<br />
�ύʱ��: {submitdate}<br />
�ύ����: {submittimes}<br />
ע��ԭ��: {message}<br />
<br />
��˽��: {modresult}<br />
���ʱ��: {moddate}<br />
��˹���Ա: {adminusername}<br />
����Ա����: {remark}<br />
<br />
----------------------------------------------------------------------<br />
<strong>��˽��˵��</strong><br />
----------------------------------------------------------------------<br />

<p>ͨ��: ����ע����ͨ����ˣ����ѳ�Ϊ {bbname} ����ʽ�û���</p>

<p>���: ����ע����Ϣ����������δ�������Ƕ����û���ĳЩҪ��������
	  ���ݹ���Ա���ԣ�<a href="home.php?mod=spacecp&ac=profile" target="_blank">��������ע����Ϣ</a>��Ȼ���ٴ��ύ��</p>

<p>ɾ��������ע�����������ǵ�Ҫ��ƫ��ϴ󣬻�վ����ע��������
	  ����Ԥ�ڣ������ѱ�����������ʺ��Ѵ����ݿ���ɾ�������޷�
	  ��ʹ�����¼���ύ�ٴ���ˣ������½⡣</p>

<br />
<br />
����<br />
<br />
{bbname} �����Ŷ�.<br />
{siteurl}',

	'adv_expiration_subject' =>	'��վ��Ĺ�潫�� {day} ����ڣ��뼰ʱ����',
	'adv_expiration_message' =>	'��վ������¹�潫�� {day} ����ڣ��뼰ʱ����<br /><br />{advs}',
	'invite_payment_email_message' => '
��ӭ������{bbname}��{siteurl}�������Ķ���{orderid}�Ѿ�֧����ɣ�������ȷ����Ч��<br />
<br />----------------------------------------------------------------------<br />
����������õ�������
<br />----------------------------------------------------------------------<br />

{codetext}

<br />----------------------------------------------------------------------<br />
��Ҫ��
<br />----------------------------------------------------------------------<br />',

	'email_cooperation_subject1' =>	'��ҵ����',
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
   <h2>{name}��ã�</h2>
   <div class="email-name">
       {m}����������������ע��Ϣ��<br />
       &nbsp;&nbsp;&nbsp;&nbsp;{cooperation_content}<br />
      <p style="text-align:right;"><a href="{url}" target="_blank">{bbname}</a></p>
   </div>
   <div class="email-foot">
      ������������޷����ʣ��뽫����ַ����ճ�����µ�����������С�<br />
      ����������յ��˴��ʼ������㲢δע�� �������� �ʺ�����Բ�����ɾ�����ʼ���
   </div>
</div>
',
	'email_cooperation_subject2' =>	'ְλ����',
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
   <h2>{name}��ã�</h2>
   <div class="email-name">
       {m}��������ְλ���롣��ע��Ϣ��<br />
       &nbsp;&nbsp;&nbsp;&nbsp;{cooperation_content}<br />
      <p style="text-align:right;"><a href="{url}" target="_blank">{bbname}</a></p>
   </div>
   <div class="email-foot">
      ������������޷����ʣ��뽫����ַ����ճ�����µ�����������С�<br />
      ����������յ��˴��ʼ������㲢δע�� �������� �ʺ�����Բ�����ɾ�����ʼ���
   </div>
</div>
',

);

?>