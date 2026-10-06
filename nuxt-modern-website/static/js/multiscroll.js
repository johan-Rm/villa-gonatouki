var themeajax = {"url":"https:\/\/twofold.fuelthemes.net\/wp-admin\/admin-ajax.php","settings":{"lightbox_effect":"lg-slide","lightbox_autoplay_duration":"5","lightbox_thumbnails_default":"on","map_style":"","right_click":"on","custom_select":true},"l10n":{"loading":"Loading ...","nomore":"Nothing left to load","added":"Added To Cart","added_svg":"<svg xmlns=\"http:\/\/www.w3.org\/2000\/svg\" viewBox=\"0 0 64 64\" enable-background=\"new 0 0 64 64\"><path fill=\"none\" stroke=\"#000\" stroke-width=\"2\" stroke-linejoin=\"bevel\" stroke-miterlimit=\"10\" d=\"m13 33l12 12 24-24\"\/><\/svg>","lightbox_tweet_text":"TwoFold"}};

!(function (m, n, v) {
  'use strict'
  const s = m(document)
  const b = m(n)
  const d = m('body')
  const cs = m('.container-side-wrapper')
  const y = new BezierEasing(0.77, 0, 0.175, 1)
  const o = new MobileDetect(n.navigator.userAgent)
  var c = {
    thb_menuscroll: !1,
    thb_cartscroll: !1,
    init () {
      let e
      const t = this
      for (e in (b
        .on('resize.thb-init', function () {
          m('.page-padding').css({ paddingTop: m('#header').outerHeight() + parseInt(m('#header').css('marginTop')) + 0.1 * b.outerHeight() })
        })
        .trigger('resize.thb-init'),
      t)) {
        if (t.hasOwnProperty(e)) {
          const a = t[e]
          void 0 !== a.selector && void 0 !== a.init && m(a.selector).length > 0 && a.init()
        }
      }
    },
    multiScroll: {
      selector: '.multiscroll',
      init () {
        const o = m(this.selector)
        const t = o.data('autoplay') === 'on' && o.data('autoplay-speed')
        const n = o.find('.ms-section')
        o.multiscroll({
          scrollingSpeed: 1e3,
          easing: 'cubic-bezier(.77,0,.175,1)',
          menu: !1,
          sectionsColor: [],
          navigation: !0,
          navigationPosition: 'right',
          loopBottom: !0,
          loopTop: !0,
          css3: !0,
          paddingTop: 0,
          paddingBottom: 0,
          normalScrollElements: null,
          keyboardScrolling: !0,
          touchSensitivity: 5,
          sectionSelector: '.ms-section',
          leftSelector: '.ms-left',
          rightSelector: '.ms-right',
          onLeave (e, t, a) {
            if (!o.hasClass('split')) {
              const i = n.eq(t - 1).data('color')
              d.hasClass('thb-full-menu-left-enabled') || d.removeClass('logo-light logo-dark').addClass(i)
              if(m('.multiscroll .ms-left .ms-section-image.active').length > 0) {
                cs.removeClass('p-left').addClass('p-right')
              } else {
                cs.removeClass('p-right').addClass('p-left')
              }
            }
          },
          afterRender () {
            if (!o.hasClass('split')) {
              const e = n.eq(0).data('color')
              d.hasClass('thb-full-menu-left-enabled') || d.removeClass('logo-light logo-dark').addClass(e)
            }
            d.hasClass('thb-preload')
              ? Pace.on('done', function () {
                t &&
                                          setInterval(function () {
                                            o.multiscroll.moveSectionDown()
                                          }, t)
              })
              : t &&
                        setInterval(function () {
                          o.multiscroll.moveSectionDown()
                        }, t)
          }
        })
      },
    },
  }
  s.ready(function () {
    c.init()
  })
})(jQuery, this, _)
