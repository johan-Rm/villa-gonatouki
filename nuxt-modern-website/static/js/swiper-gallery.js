var themeajax = {"url":"https:\/\/twofold.fuelthemes.net\/wp-admin\/admin-ajax.php","settings":{"lightbox_effect":"lg-slide","lightbox_autoplay_duration":"5","lightbox_thumbnails_default":"on","map_style":"","right_click":"on","custom_select":true},"l10n":{"loading":"Loading ...","nomore":"Nothing left to load","added":"Added To Cart","added_svg":"<svg xmlns=\"http:\/\/www.w3.org\/2000\/svg\" viewBox=\"0 0 64 64\" enable-background=\"new 0 0 64 64\"><path fill=\"none\" stroke=\"#000\" stroke-width=\"2\" stroke-linejoin=\"bevel\" stroke-miterlimit=\"10\" d=\"m13 33l12 12 24-24\"\/><\/svg>","lightbox_tweet_text":"TwoFold"}};

!(function (m, n, v) {
  'use strict'
  const s = m(document)
  const b = m(n)
  const d = m('body')
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
    swiper: {
      selector: '.swiper-container',
      init () {
        const o = m(this.selector)
        const n = m('.swiper-gallery')
        const s = o.find('.thb-arrow')
        const l = n.find('.swiper-pagination span')
        const r = n.data('autoplay') === 'on' && n.data('autoplay-speed')
        const e = n.data('effect') ? n.data('effect') : 'slide'
        const t = n.data('speed') ? n.data('speed') : 1e3
        const i = n.find('.swiper-slide').length
        const c = m('.swiper-thumbnails')
        const a = m('.thumbnail-toggle')
        const d = new TimelineLite({ paused: !0 })
        const u = new TimelineLite({ paused: !0 })
        const h = m('body')
        r && s && (TweenLite.set(o.find('.thb-progress'), { drawSVG: '0% 0%' }), u.to(n.find('.thb-progress'), r / 1e3, { drawSVG: '0% 100%' }, 'start')),
        c.length &&
                            (TweenLite.set(m('.thb-gallery-icon'), { drawSVG: '0% 74%' }),
                            TweenLite.set(m('.thb-thumbnail-icon'), { drawSVG: '0% 48%' }),
                            d
                              .to(m('.thb-gallery-icon'), 1, { drawSVG: '73.7% 100%', ease: y }, 'start')
                              .to(m('.thb-thumbnail-icon'), 1, { drawSVG: '49% 97.5%', ease: y }, 'start')
                              .to(m('.thb-thumbnails'), 0.5, { x: 0, ease: y }, 'start')
                              .to(m('.thb-thumbnails .swiper-container'), 0.5, { opacity: 1, ease: y }, 'start'),
                            a.on('click', function () {
                              const e = m(this)
                              return e.data('toggle') ? (d.timeScale(1).reverse(), e.removeData('toggle')) : (d.timeScale(1).play(), e.data('toggle', 'on')), !1
                            }))
        const p = {
          nextButton: '.swiper-button-next',
          prevButton: '.swiper-button-prev',
          speed: t,
          pagination: '.swiper-pagination',
          paginationClickable: !0,
          preloadImages: !1,
          lazyLoading: !0,
          lazyLoadingInPrevNext: !0,
          lazyLoadingOnTransitionStart: !0,
          loop: i > 1,
          effect: e,
          autoplay: r,
          autoplayDisableOnInteraction: !1,
          keyboardControl: !0,
          mousewheelControl: !0,
          onInit (t) {
            const e = t.activeIndex
            const a = t.slides.eq(e).data('color')
            if (
              (h.hasClass('thb-full-menu-left-enabled') || h.addClass(a),
              b.width() > 1024 &&
                                    s &&
                                    n.find('.thb-arrow').each(function () {
                                      const n = m(this)
                                      o.bind('mousemove', function (e) {
                                        const t = n.parents('.swiper-cursor')
                                        const a = t.offset()
                                        let i = Math.min(e.pageX - a.left, t.width())
                                        let o = e.pageY - a.top
                                        i < 0 && (i = 0), o < 0 && (o = 0), TweenMax.set(n, { x: i - 40, y: o - 40, force3D: !0 })
                                      })
                                    }),
              c.length)
            ) {
              const i = m('.thb-thumbnails').find('.swiper-slide>div')
              r && s && u.fromTo(i, r / 1e3, { scaleX: 0 }, { scaleX: 1 }, 'start')
            }
            l &&
                                l.on('click', function () {
                                  const e = m(this).index()
                                  t.slideTo(e)
                                }),
            b.on('orientationchange', function () {
              v.defer(function () {
                t.update()
              })
            })
          },
          onAutoplayStart () {
            c && r && s && i > 1 && u.play()
          },
          onAutoplayStop () {
            c && r && s && i > 1 && u.stop()
          },
          onSlideChangeStart (e) {
            const t = e.slides.eq(e.activeIndex).attr('data-swiper-slide-index')
            const a = e.slides.eq(t).data('color')
            c && r && s && i > 1 && u.reverse(),
            h.hasClass('thb-full-menu-left-enabled') || h.removeClass('logo-light logo-dark').addClass(a),
            l && (l.removeClass('swiper-pagination-bullet-active'), l.eq(t).addClass('swiper-pagination-bullet-active'))
          },
          onSlideChangeEnd () {
            c && r && s && i > 1 && u.restart()
          }
        }
        e === 'cube' && ((p.cube = { shadow: !1, slideShadows: !1 }), (p.direction = 'vertical')), c && (p.loopedSlides = i)
        const f = new Swiper(n, p)
        if (c.length) {
          const g = new Swiper(c, { direction: 'vertical', slidesPerView: 5, spaceBetween: 1, loop: i > 1, loopedSlides: i, centeredSlides: !0, touchRatio: 0.2, autoplayDisableOnInteraction: !1, slideToClickedSlide: !0 });
          (f.params.control = g).params.control = f
        }
      }
    }
  }
  s.ready(function () {
    c.init()
    // console.log('init swiper gallery')
  })
})(jQuery, this, _)
