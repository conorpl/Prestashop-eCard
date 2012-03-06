{capture name=path}eCard{/capture}
{include file="$tpl_dir./breadcrumb.tpl"}

<h2>eCard</h2>

{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}


<p>{l s='Za chwilę zostaniesz przekierowany na stronę płatności. Jeśli przekierowanie nie zadziała automatycznie kliknij "Dalej".' mod='ecard'}<br /></p>

<form action="https://pay.ecard.pl/payment/PS" method="post" id="payment_form">
    <input type="hidden" name="COUNTRY" value="616"/>
    <input type="hidden" name="MERCHANTID" value="{$merchantId}"/>
    <input type="hidden" name="ORDERNUMBER" value="{$orderNumber}"/>
    <input type="hidden" name="ORDERDESCRIPTION" value="{$orderDesc}"/>
    <input type="hidden" name="AMOUNT" value="{$amount}"/>
    <input type="hidden" name="CURRENCY" value="{$currency}"/>
    <input type="hidden" name="NAME" value="{$name}">
    <input type="hidden" name="SURNAME" value="{$surname}"/>
    <input type="hidden" name="LANGUAGE" value="PL"/>
    <input type="hidden" name="EMAIL" value=""/>
    <input type="hidden" name="AUTODEPOSIT" value="{$autodeposit}"/>
    <input type="hidden" name="PAYMENTTYPE" value="{$paymenttype}"/>
    <input type="hidden" name="TRANSPARENTPAGES" value="1"/>
    <input type="hidden" name="CHARSET" value="UTF-8"/>
    <input type="hidden" name="LINKFAIL" value="{$linkfail}"/>
    <input type="hidden" name="LINKOK" value="{$linkok}"/>
    <input type="hidden" name="HASHALGORITHM" value="MD5"/>
    <input type="hidden" name="HASH" value="{$hash}"/>
    <p class="cart_navigation">
        <a href="{$base_dir_ssl}order.php?step=3" class="button_large">{l s='Anuluj' mod='ecard'}</a>
        <input type="submit" name="submit" value="{l s='Dalej' mod='ecard'}" class="exclusive_large" />
    </p>
</form>
<script type="text/javascript">
{literal}
// <![CDATA[
$(document).ready(function() {

$("#payment_form").Submit();
});
// ]]>
{/literal}
</script>       