/**
 * @category   MagePsycho
 * @package    MagePsycho_RegionCityPro
 * @author     Raj KB <magepsycho@gmail.com>
 * @website    https://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

define([
    'jquery',
    'Magento_Ui/js/form/element/region',
    'select2'
], function ($, Region, select2) {
    'use strict';

    return Region.extend({
        afterSelect2Render: function () {
            $('select[name="' + this.inputName + '"]').select2({
                width: '100%'
            });
        }
    });
});
