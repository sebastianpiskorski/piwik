/*!
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

$(document).ready(function () {

    $('#piwikpro_license_key').on('keyup', function () {
        var value = $(this).val();
        if (value) {
            $('#piwikpro_license_key_submit').prop('disabled', false);
        } else {
            $('#piwikpro_license_key_submit').prop('disabled', true);
        }
    });

    $('#piwikpro_license_key_submit').on('click', function () {

        var value = $('#piwikpro_license_key')

    });

    // Keeps the plugin descriptions the same height
    $('.marketplace .plugin .description').dotdotdot({
        after: 'a.more',
        watch: 'window'
    });

    $('a.plugin-details[data-pluginName]').on('click', function (event) {
        event.preventDefault();

        var pluginName = $(this).attr('data-pluginName');
        if (!pluginName) {
            return;
        }

        var activeTab = $(this).attr('data-activePluginTab');
        if (activeTab) {
            pluginName += '!' + activeTab;
        }

        broadcast.propagateNewPopoverParameter('browsePluginDetail', pluginName);
    });

    broadcast.addPopoverHandler('browsePluginDetail', function (value) {
        var pluginName = value;
        var activeTab  = null;

        if (-1 !== value.indexOf('!')) {
            activeTab  = value.substr(value.indexOf('!') + 1);
            pluginName = value.substr(0, value.indexOf('!'));
        }

        var url = 'module=CorePluginsAdmin&action=pluginDetails&pluginName=' + encodeURIComponent(pluginName);

        if (activeTab) {
            url += '&activeTab=' + encodeURIComponent(activeTab);
        }

        Piwik_Popover.createPopupAndLoadUrl(url, 'details');
    });

});
