{*
 * 2007-2018 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author     Getresponse <grintegrations@getresponse.com>
 * @copyright 2007-2018 PrestaShop SA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
<form method="post" class="form-horizontal">
<div class="panel">
	<div class="panel-heading">
		<i class="icon-gears"></i> GetResponse Account Data
	</div>
	<div class="form-wrapper">
		<div class="form-group">
		<label class="col-lg-3" style="text-align: right"><strong>Status:</strong></label><p class="col-lg-9 text-success"> CONNECTED</p>
		<label class="col-lg-3" style="text-align: right"><strong>API Key:</strong></label><p class="col-lg-9"> {$api_key|escape:'htmlall':'UTF-8'}</p>
		<label class="col-lg-3" style="text-align: right"><strong>Name:</strong></label><p class="col-lg-9"> {$gr_acc_name|escape:'htmlall':'UTF-8'}</p>
		<label class="col-lg-3" style="text-align: right"><strong>Email:</strong></label><p class="col-lg-9"> {$gr_acc_email|escape:'htmlall':'UTF-8'}</p>
		<label class="col-lg-3" style="text-align: right"><strong>Company:</strong></label><p class="col-lg-9"> {if empty($gr_acc_company)} - {else} {$gr_acc_company|escape:'htmlall':'UTF-8'} {/if}</p>
		<label class="col-lg-3" style="text-align: right"><strong>Phone:</strong></label><p class="col-lg-9"> {if empty($gr_acc_phone)} - {else} {$gr_acc_phone|escape:'htmlall':'UTF-8'} {/if}</p>
		<label class="col-lg-3" style="text-align: right"><strong>Address:</strong></label><p class="col-lg-9"> {if empty($gr_acc_address)} - {else} {$gr_acc_address|escape:'htmlall':'UTF-8'} {/if}</p>
		</div>
	</div>
	<div class="panel-footer">
		{literal}
			<button id="disconnectFromGetResponse" type="submit" class="btn btn-default pull-right" name="disconnectFromGetResponse"><i class="icon-getresponse-connect icon-unlink"></i> Disconnect</button>
		{/literal}
	</div>
</div>
</form>
<script>
	$(function(){
		$('#disconnectFromGetResponse').on('click', function(e) {
			if (confirm('Disconnect from GetResponse?' + "\n\n" +
				'When you disconnect you won\'t be able to get new contacts via forms, comments, or during account registration.')){
				return true;
			} else {
				e.preventDefault();
			};
		});
	})
</script>
