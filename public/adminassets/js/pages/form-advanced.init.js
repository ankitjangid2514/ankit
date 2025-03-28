! function(s) {
    "use strict";

    function e() {}
    e.prototype.init = function() {
        s(".select2").select2(), s(".select2-limiting").select2({
            maximumSelectionLength: 2
        }), s(".select2-search-disable").select2({
            minimumResultsForSearch: 1 / 0
        }), s(".select2-ajax").select2({
            ajax: {
                url: "https://api.github.com/search/repositories",
                dataType: "json",
                delay: 250,
                data: function(e) {
                    return {
                        q: e.term,
                        page: e.page
                    }
                },
                processResults: function(e, t) {
                    return t.page = t.page || 1, {
                        results: e.items,
                        pagination: {
                            more: 30 * t.page < e.total_count
                        }
                    }
                },
                cache: !0
            },
            placeholder: "Search for a repository",
            minimumInputLength: 1,
            templateResult: function(e) {
                if (e.loading) return e.text;
                var t = s("<div class='select2-result-repository clearfix'><div class='select2-result-repository__avatar'><img src='" + e.owner.avatar_url + "' /></div><div class='select2-result-repository__meta'><div class='select2-result-repository__title'></div><div class='select2-result-repository__description'></div><div class='select2-result-repository__statistics'><div class='select2-result-repository__forks'><i class='fa fa-flash'></i> </div><div class='select2-result-repository__stargazers'><i class='fa fa-star'></i> </div><div class='select2-result-repository__watchers'><i class='fa fa-eye'></i> </div></div></div></div>");
                return t.find(".select2-result-repository__title").text(e.full_name), t.find(".select2-result-repository__description").text(e.description), t.find(".select2-result-repository__forks").append(e.forks_count + " Forks"), t.find(".select2-result-repository__stargazers").append(e.stargazers_count + " Stars"), t.find(".select2-result-repository__watchers").append(e.watchers_count + " Watchers"), t
            },
            templateSelection: function(e) {
                return e.full_name || e.text
            }
        }), s(".select2-templating").select2({
            templateResult: function(e) {
                return e.id ? s('<span><img src="/assets/images/flags/select2/' + e.element.value.toLowerCase() + '.png" class="img-flag" /> ' + e.text + "</span>") : e.text
            }
        }), s(".colorpicker-default").colorpicker({
            format: "hex"
        }), s(".colorpicker-rgba").colorpicker(), s("#colorpicker-horizontal").colorpicker({
            color: "#88cc33",
            horizontal: !0
        }), s("#colorpicker-inline").colorpicker({
            color: "#DD0F20",
            inline: !0,
            container: !0
        }), s("#timepicker").timepicker({
            icons: {
                up: "mdi mdi-chevron-up",
                down: "mdi mdi-chevron-down"
            }
        }), s("#timepicker2").timepicker({
            showMeridian: !1,
            icons: {
                up: "mdi mdi-chevron-up",
                down: "mdi mdi-chevron-down"
            }
        }), s("#timepicker3").timepicker({
            minuteStep: 15,
            icons: {
                up: "mdi mdi-chevron-up",
                down: "mdi mdi-chevron-down"
            }
        });
        var i = {};
        s('[data-toggle="touchspin"]').each(function(e, t) {
            var a = s.extend({}, i, s(t).data());
            s(t).TouchSpin(a)
        }), s("input[name='demo3_21']").TouchSpin({
            initval: 40,
            buttondown_class: "btn btn-primary",
            buttonup_class: "btn btn-primary"
        }), s("input[name='demo3_22']").TouchSpin({
            initval: 40,
            buttondown_class: "btn btn-primary",
            buttonup_class: "btn btn-primary"
        }), s("input[name='demo_vertical']").TouchSpin({
            verticalbuttons: !0
        }), s("input#defaultconfig").maxlength({
            warningClass: "badge badge-info",
            limitReachedClass: "badge badge-warning"
        }), s("input#thresholdconfig").maxlength({
            threshold: 20,
            warningClass: "badge badge-info",
            limitReachedClass: "badge badge-warning"
        }), s("input#moreoptions").maxlength({
            alwaysShow: !0,
            warningClass: "badge badge-success",
            limitReachedClass: "badge badge-danger"
        }), s("input#alloptions").maxlength({
            alwaysShow: !0,
            warningClass: "badge badge-success",
            limitReachedClass: "badge badge-danger",
            separator: " out of ",
            preText: "You typed ",
            postText: " chars available.",
            validate: !0
        }), s("textarea#textarea").maxlength({
            alwaysShow: !0,
            warningClass: "badge badge-info",
            limitReachedClass: "badge badge-warning"
        }), s("input#placement").maxlength({
            alwaysShow: !0,
            placement: "top-left",
            warningClass: "badge badge-info",
            limitReachedClass: "badge badge-warning"
        })
    }, s.AdvancedForm = new e, s.AdvancedForm.Constructor = e
}(window.jQuery),
function() {
    "use strict";
    window.jQuery.AdvancedForm.init()
}(), $(function() {
    "use strict";
    var o = $(".docs-date"),
        r = $(".docs-datepicker-container"),
        c = $(".docs-datepicker-trigger"),
        l = {
            show: function(e) {
                console.log(e.type, e.namespace)
            },
            hide: function(e) {
                console.log(e.type, e.namespace)
            },
            pick: function(e) {
                console.log(e.type, e.namespace, e.view)
            }
        };
    o.on({
        "show.datepicker": function(e) {
            console.log(e.type, e.namespace)
        },
        "hide.datepicker": function(e) {
            console.log(e.type, e.namespace)
        },
        "pick.datepicker": function(e) {
            console.log(e.type, e.namespace, e.view)
        }
    }).datepicker(l), $(".docs-options, .docs-toggles").on("change", function(e) {
        var t, a = e.target,
            i = $(a),
            s = i.attr("name"),
            n = "checkbox" === a.type ? a.checked : i.val();
        switch (s) {
            case "container":
                n ? (n = r).show() : r.hide();
                break;
            case "trigger":
                n ? (n = c).prop("disabled", !1) : c.prop("disabled", !0);
                break;
            case "inline":
                (t = $('input[name="container"]')).prop("checked") || t.click();
                break;
            case "language":
                $('input[name="format"]').val($.fn.datepicker.languages[n].format)
        }
        l[s] = n, o.datepicker("reset").datepicker("destroy").datepicker(l)
    }), $(".docs-actions").on("click", "button", function(e) {
        var t, a = $(this).data(),
            i = a.arguments || [];
        e.stopPropagation(), a.method && (a.source ? o.datepicker(a.method, $(a.source).val()) : (t = o.datepicker(a.method, i[0], i[1], i[2])) && a.target && $(a.target).val(t))
    })
});
