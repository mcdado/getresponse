/**
 * @author Getresponse <grintegrations@getresponse.com>
 * @copyright GetResponse
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */
$(function () {
    //enterprise package selector
    if ($('input[name="is_enterprise"]:checked').val() == 1) {
        $('input[name="account_type"]').parent().parent().parent().parent().show();
        $('#domain').parent().parent().show();
    } else {
        $('input[name="account_type"]').parent().parent().parent().parent().hide();
        $('#domain').parent().parent().hide();
    }

    $('input[name="is_enterprise"]').on('change', function () {
        if ($('input[name="is_enterprise"]:checked').val() == 1) {
            $('input[name="account_type"]').parent().parent().parent().parent().show();
            $('#domain').parent().parent().show();
        } else {
            $('input[name="account_type"]').parent().parent().parent().parent().hide();
            $('#domain').parent().parent().hide();
        }
    });
});
