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
{if isset($export_customers_form)}
    {$export_customers_form}
{/if}

{if isset($export_customers_list)}
    {$export_customers_list}
{/if}

{if isset($campaign_days)}
    <script>
        (function ($) {
            var available_cycles = $.parseJSON('{$campaign_days}');

            var cycles1 = cycles.init(
                available_cycles,
                $('#campaign'),
                $('#autoresponder_day'),
                $('#addToCycle_1'),
                {$cycle_day}
            );
        })(jQuery);
    </script>
{/if}
