/*
 * Table of Contents
 * Created 16.10.2020
 * Karel Pavelka, dotek.info
 * Example HTML for forms: <nav class="toc" data-headings="legend" data-switch-off="false"></nav>
 * Example HTML for help: <nav class="toc"></nav>
 * Example JS: $(".toc").toc();
 */
(function($) {
    "use strict";

    var toc = function(options) {
            var oThis = $(this);
            var data = $(this).data();
            var hLevel = 0;
            var headingSelectors;
            var dataOptions = {
                content: $(oThis).data("content") || "body", // Main container for Toc
                hToc: $(oThis).data("h-toc") || "strong", // Tag name for heading TOC
                headings: $(oThis).data("headings") || "h1,h2,h3,h4", // Headings for TOC
                txTitle: $(oThis).data("tx-title") || 'Table of contents', // Text  for heading TOC
                classBtn: $(oThis).data("class-btn") || 'btn btn-sm btn-default btn-light', // class for button Show/Hide
                txShow: $(oThis).data("tx-show") || 'Show', // Text
                txHide: $(oThis).data("tx-hide") || 'Hide', // Text
                switchOff: $(oThis).data("switch-off") || false, // Switch Button show or hide
                minH: $(oThis).data("min-h") || '2', // Minimum number of headings to create TOC
                classUl: $(oThis).data("class-ul") || '', // Class name for tag ul
                classLi: $(oThis).data("class-li") || '', // Class name for tag li
                classA: $(oThis).data("class-a") || '' // Class name for tag a
            };
            options = $.extend({}, dataOptions, options);
            headingSelectors = options.headings.split(",");

            options.countToc = $(options.content).find(options.headings).length;
            if (options.countToc > options.minH) {
                $(this).html('');
                $(this).html($('<ul class="' + options.classUl + '"></ul>'));
                var ul = $(this).find("ul:first");
                var stack = [ul];

                $(options.content).find(options.headings).attr("id", function(index, attr) {
                    var genUID = function(text) {
                        if (text.length === 0) {
                            text = "?";
                        }
                        var baseId = text.normalize("NFD").replace(/[^A-Za-z0-9]/g, "").toLowerCase(),
                            suffix = "",
                            count = 1;
                        while (document.getElementById(baseId + suffix) !== null) {
                            suffix = "_" + count++;
                        }
                        return baseId + suffix;
                    };
                    return attr || genUID($(this).text());
                }).each(function() {

                    var elem = $(this),
                        iLevel = $.map(headingSelectors, function(selector, index) {
                            return elem.is(selector) ? index : undefined;
                        })[0];
                    if (iLevel > hLevel) {
                        var parentItem = stack[0].children("li:last")[0];
                        if (parentItem) {
                            stack.unshift($("<ul/>", {
                                'class': options.classUl
                            }).appendTo(parentItem));
                        }
                    } else {
                        stack.splice(0, Math.min(hLevel - iLevel, Math.max(stack.length - 1, 0)));
                    }
                    if (elem.text() != "") {
                        $("<li/>", {
                            'class': options.classLi
                        }).appendTo(stack[0]).append(
                            $("<a/>", {
                                'class': options.classA
                            }).text(elem.text()).attr("href", "#" + elem.attr("id"))
                        );
                    }

                    hLevel = iLevel;
                });
                var sHead = '<' + options.hToc + '>' + options.txTitle + '  ';
                if (options.switchOff === false) {
                    sHead = sHead + '<a href="#" class="btn-toc ' + options.classBtn + '">' + options.txHide + '</a>';
                }
                var sHead = sHead + '</' + options.hToc + '>';
                $(oThis).prepend(sHead);

                if (options.switchOff === false) {
                    $(".btn-toc").click(function() {
                        if ($(oThis).find("ul").is(":hidden")) {
                            $(oThis).find("ul").show();
                            $(this).html(options.txHide);
                        } else {
                            $(oThis).find("ul").hide();
                            $(this).html(options.txShow);
                        }
                        return false;
                    });
                }
            }
        },
        t = $.fn.toc;

    $.fn.toc = toc;

    $.fn.toc.noConflict = function() {
        $.fn.toc = t;

        return this;
    };
}(window.jQuery));
