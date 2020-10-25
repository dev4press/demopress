/*jslint regexp: true, nomen: true, undef: true, sloppy: true, eqeq: true, vars: true, white: true, plusplus: true, maxerr: 50, indent: 4 */
/*global ajaxurl, demopress_data*/

;(function($, window, document, undefined) {
    window.wp = window.wp || {};
    window.wp.demopress = window.wp.demopress || {};

    window.wp.demopress.generator = {
        tmp: {
            loader: null,
            content: null
        },
        log: {
            init: function() {
                wp.demopress.generator.tmp.loader = $(".demopress-generator-panel .demopress-gen-loader");
                wp.demopress.generator.tmp.content = $(".demopress-generator-panel .demopress-gen-status");

                wp.demopress.generator.log.run();
            },
            run: function() {
                wp.demopress.generator.tmp.loader.css("visibility", "visible");

                $.ajax({
                    dataType: "html", type: "post", timeout: 1000000,
                    data: {nonce: demopress_data.nonce},
                    url: ajaxurl + "?action=demopress_get_generator_status",
                    success: function(html) {
                        wp.demopress.generator.tmp.content.html(html);

                        var pre = wp.demopress.generator.tmp.content.find("pre");

                        pre.scrollTop(pre.prop("scrollHeight"));
                    },
                    complete: function (data) {
                        wp.demopress.generator.tmp.loader.css("visibility", "hidden");

                        setTimeout(wp.demopress.generator.log.run, 5000);
                    }
                });
            }
        },
        task: {
            run: function() {
                wp.dev4press.admin.panels.settings.run();

                $(document).on("change", ".demopress-builder-switch select", function() {
                    var c = $(this).closest(".demopress-builder-switch"),
                        v = $(this).val(),
                        s = c.data("switch");

                    $("." + s + "-switch").addClass("demopress-is-hidden");
                    $("." + s + "-data-" + v).removeClass("demopress-is-hidden");
                });

                $(document).on("change", ".demopress-type-settings-ctrl input[type=checkbox]", function(){
                    var checked = $(this).is(":checked"),
                        group = $(this).closest(".d4p-group");

                    if (checked) {
                        group.removeClass("demopress-type-settings-hidden");
                    } else {
                        group.addClass("demopress-type-settings-hidden");
                    }
                });
            }
        }
    };

    window.wp.demopress.generator.task.run();
})(jQuery, window, document);
