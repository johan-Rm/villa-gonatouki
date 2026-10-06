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
    pace: {
      selector: 'body',
      init () {
        m(this.selector).hasClass('thb-preload') &&
                        Pace.on('done', function () {
                          TweenMax.to(m('.pace'), 1, {
                            y: -n.innerHeight,
                            ease: y,
                            onComplete () {
                              m('.pace').remove()
                            }
                          })
                        })
      }
    },
    headRoom: {
      selector: '#header',
      init () {
        const e = this
        const t = m(e.selector)
        b.on('scroll', function () {
          e.scroll(t)
        })
      },
      scroll (e) {
        const t = b.scrollTop()
        const a = 'fixed'
        t > 0
          ? v.defer(function () {
            e.addClass(a)
          })
          : v.defer(function () {
            e.removeClass(a)
          })
      }
    },
    right_click: {
      selector: '.right-click-on',
      init () {
        const e = m('#right_click_content')
        const t = new TimelineLite({
          paused: !0,
          onStart () {
            e.css('display', 'flex').addClass('active')
          },
          onReverseComplete () {
            e.css('display', 'none').removeClass('active')
          }
        })
        const a = e.find('.columns>*')
        t.to(e, 0.5, { opacity: 1, ease: y }, 'start').staggerFrom(a, 0.5, { Y: 20, opacity: 0, ease: y }, 0.1),
        b.on('contextmenu', function (e) {
          if (e.which === 3) { return t.play(), !1 }
        }),
        e.on('click', function () {
          t.reverse()
        })
      }
    },
    skrollr: {
      selector: '.parallax_bg',
      init () {
        n.skroller = skrollr.init({
          forceHeight: !1,
          mobileCheck () {
            return !1
          }
        })
      }
    },
    collectionStyle4: {
      selector: '.collection-style4-container',
      init () {
        const s = m(this.selector)
        const l = m('.style4-main', s)
        function r (e) {
          (e = e || s),
          s.find('.style4-album').each(function () {
            const e = m(this)
            const t = e.data('aspect')
            m('.album-image', e).width(function () {
              return m(this).height() / t
            })
          })
        }
        r(),
        b.on(
          'resize',
          v.debounce(function () {
            r()
          }, 20)
        ),
        s.find('.album-link').on('click', function () {
          const e = m(this)
          const t = e.data('security')
          const a = e.data('albumid')
          const o = m('.album-image', e)
          const n = new TimelineMax()
          return (
            m.ajax(themeajax.url, {
              method: 'POST',
              data: { action: 'thb_collection_style4', security: t, albumid: a },
              beforeSend () {
                o.addClass('thb-loading'), !0
              },
              success (e) {
                const t = m.parseHTML(m.trim(e))
                const a = m(t).find('.style4-album-detail')
                const i = m('.back_to_list', a)
                o.removeClass('thb-loading'),
                m(t).appendTo(s),
                TweenMax.set(m(t), { autoAlpha: 0 }),
                r(m(t)),
                c.custom_scroll.init(m(t).find('.custom_scroll')),
                c.lightbox.init(),
                c.shareButton.init(),
                c.panHover.init(),
                c.atvImg.init(),
                n.to(l, 0.2, { autoAlpha: 0 }).to(m(t), 0.2, { autoAlpha: 1 }).to(a, 0.5, { autoAlpha: 1 }),
                i.on('click', function () {
                  return n.reverse(), !1
                })
              }
            }),
            !1
          )
        })
      }
    },
    collectionStyle5: {
      selector: '.collection-style5-container',
      init () {
        m(this.selector)
        b.on('scroll', function () {
          TweenMax.to(m('.album_meta'), 0.2, { autoAlpha: 0 }), TweenMax.to(m(m('.collection-style5:in-viewport(150)').data('target')), 0.2, { autoAlpha: 1 })
        }).trigger('scroll')
      }
    },
    homeSplitTile: {
      selector: '#home-split-tile',
      init () {
        const e = m(this.selector)
        const t = e.find('.thb-arrow')
        const a = new TimelineLite({ paused: !0 })
        const i = e.data('autoplay') === 'on' && e.data('autoplay-speed')
        const o = m('.thb-progress', t)
        function n () {
          b.width() > 1024 &&
                            t &&
                            e.find('.thb-arrow').each(function () {
                              const n = m(this)
                              e.bind('mousemove', function (e) {
                                const t = n.parents('.swiper-cursor')
                                const a = t.offset()
                                let i = Math.min(e.pageX - a.left, t.width())
                                let o = e.pageY - a.top
                                i < 0 && (i = 0), o < 0 && (o = 0), TweenMax.set(n, { x: i - 40, y: o - 40, force3D: !0 })
                              })
                            }),
          i && t && (TweenLite.set(o, { drawSVG: '0% 0%' }), a.to(o, i / 1e3, { drawSVG: '0% 100%' }, 'start')),
          new BoxesFx(document.getElementById('home-split-tile'), a, i)
        }
        d.hasClass('thb-preload')
          ? Pace.on('done', function () {
            n()
          })
          : n()
      }
    },
    custom_scroll: {
      selector: '.custom_scroll',
      init (e) {
        (e || m(this.selector)).each(function () {
          const e = m(this)
          const t = !!e.data('horizontal') && e.data('horizontal')
          e.perfectScrollbar({ suppressScrollX: !t, suppressScrollY: t })
        })
      }
    },
    jarallax: {
      selector: '.video-container.jarallax-video',
      init (e) {
        (e || m(this.selector)).each(function () {
          const e = m(this)
          const t = { speed: 0.8, videoSrc: e.data('video') }
          e.jarallax(t)
        })
      }
    },
    shareButton: {
      selector: '.share_button',
      init () {
        m(this.selector).each(function () {
          const e = m(this)
          const t = m(e.attr('href'))
          const a = t.find('.social')
          const i = new TimelineLite({
            paused: !0,
            onStart () {
              t.css('display', 'flex').addClass('active')
            },
            onReverseComplete () {
              t.css('display', 'none').removeClass('active')
            }
          })
          const o = t.find('a')
          i.to(t, 0.5, { opacity: 1, ease: y }, 'start').staggerFrom(o, 0.5, { rotationX: '-90deg', opacity: 0, ease: y }, 0.1),
          e.on('click', function () {
            return i.timeScale(1).restart(), !1
          }),
          s.keyup(function (e) {
            e.keyCode === 27 && i.progress() > 0 && i.timeScale(1.5).reverse()
          }),
          t.on('click', function () {
            i.timeScale(1.5).reverse()
          }),
          a.on('click', function () {
            const e = screen.width / 2 - 320
            const t = screen.height / 2 - 220 - 100
            return n.open(m(this).attr('href'), 'mywin', 'left=' + e + ',top=' + t + ',width=640,height=440,toolbar=0'), !1
          })
        })
      }
    },
    lightbox: {
      selector: '.gallery, .isotope-grid, .collection_album, .lightbox-gallery, .multiscroll, .single-post .post-content, .collection-style4-detail',
      init () {
        const e = m(this.selector)
        const t = !!d.hasClass('lightbox-download-enabled')
        const a = !!d.hasClass('lightbox-zoom-enabled')
        const i = !!d.hasClass('lightbox-autoplay-enabled')
        const o = !!d.hasClass('lightbox-thumbnails-enabled')
        const n = !!d.hasClass('lightbox-shares-enabled')
        const s = themeajax.settings.lightbox_effect
        let l = 'data-img'
        let r = 1
        e.each(function () {
          m(this).is('.post-content') && (l = !1),
          m(this).lightGallery({
            selector: '[rel="lightbox"]',
            thumbnail: o,
            showThumbByDefault: themeajax.settings.lightbox_thumbnails_default !== 'off',
            exThumbImage: l,
            share: n,
            tweetText: themeajax.l10n.lightbox_tweet_text,
            autoplay: i,
            mode: s,
            autoplayControls: i,
            pause: 1e3 * themeajax.settings.lightbox_autoplay_duration,
            zoom: a,
            download: t,
            hash: !0,
            galleryId: r,
            cssEasing: 'cubic-bezier(.77,0,.175,1)',
            easing: y,
            hideBarsDelay: 99999
          }),
          r++
        })
      }
    },
    reviews: {
      selector: '#respond',
      init () {
        m(this.selector).on('click', 'p.stars a', function () {
          const e = m(this)
          setTimeout(function () {
            e.prevAll().addClass('active')
          }, 10)
        })
      }
    },
    fullMenu: {
      selector: '.thb-full-menu',
      init () {
        const e = m(this.selector)
        e.find('a')
        e.find('li.menu-item-has-children').each(function () {
          const e = m(this)
          const t = e.find('>.sub-menu')
          const a = t.find('>li>a')
          const i = new TimelineMax({ paused: !0 })
          i.to(t, 0.5, { autoAlpha: 1 }, 'start').staggerTo(a, 0.1, { opacity: 1, y: 0 }, 0.03, 'start'),
          e.hoverIntent(
            function () {
              e.addClass('sfHover'), i.timeScale(1).restart()
            },
            function () {
              e.removeClass('sfHover'), i.timeScale(1.5).reverse()
            }
          )
        })
      }
    },
    responsiveNav: {
      selector: '#navigation-menu',
      init () {
        const e = m(this.selector)
        const t = m('.mobile-toggle')
        const a = new TimelineLite({ paused: !0 })
        const i = e.find('.navigation-menu>li')
        const o = e.data('menu-speed')
        const n = m('.menu_overlay')
        const u = m('.navigation-menu .menu-item-type-post_type > a')
        const s = e.data('behaviour') === 'thb-submenu' ? e.find('.navigation-menu li:has(".sub-menu")>a') : e.find('.navigation-menu li:has(".sub-menu")>a span')
        TweenLite.set(t.find('.thb-top-line, .thb-bottom-line'), { drawSVG: '0% 26.5%' }),
        d.hasClass('thb-full-menu-left-enabled') || a.to(m('body'), 0, { className: '+=menu-open' }, 'start'),
        a
          .to(t.find('.thb-mid-line'), 1, { drawSVG: '50% 50%', ease: y }, 'start')
          .to(t.find('.thb-top-line, .thb-bottom-line'), 1, { drawSVG: '62% 100%', ease: y }, 'start')
          .to(e, 0.5, { x: 0, ease: y }, 'start')
          .to(n, 0.5, { scaleX: 1, ease: y }, 'start+=0.2'),
        d.hasClass('thb-full-menu-left-enabled') || a.staggerFromTo(i, o, { rotationX: '90deg', opacity: 0 }, { rotationX: '0', scaleX: 1, opacity: 1, ease: y }, 0.04),
        m('.mobile-toggle').on('click', function () {
          const e = m(this)
          return e.data('toggle') ? (a.timeScale(1).reverse(), e.removeData('toggle')) : (a.timeScale(1).play(), e.data('toggle', 'on')), !1
        }),
        n.on('click', function () {
          return a.timeScale(1).reverse(), m('.mobile-toggle').removeData('toggle'), !1
        }),
        u.on('click', function () {
          return a.timeScale(1).reverse(), m('.mobile-toggle').removeData('toggle'), !1
        }),
        s.on('click', function (e) {
          const t = m(this)
          const a = t.parents('a').length ? t.parents('a') : t
          const i = a.next('.sub-menu')
          a.hasClass('active') ? (a.removeClass('active'), i.slideUp('200')) : (a.addClass('active'), i.slideDown('200')), e.stopPropagation(), e.preventDefault()
        })
      }
    },
    isotope: {
      selector: '.isotope-grid',
      init () {
        m(this.selector).each(function () {
          const e = m(this)
          const t = (e.children('.item'), e.isotope({ itemSelector: '.item', transitionDuration: 0, layoutMode: 'packery' }))
          t.imagesLoaded().progress(function () {
            t.isotope('layout')
          })
        })
      }
    },
    updateCart: {
      selector: '#side-cart',
      init () {
        c.updateCart.quick_cart(), d.bind('wc_fragments_refreshed added_to_cart', c.updateCart.quick_cart)
      },
      quick_cart () {
        m(this.selector)
        m('#side-cart').on('click', '.quick_cart', function () {
          return d.toggleClass('open-cart'), !1
        }),
        m('.cart_placeholder').on('click', function () {
          return d.toggleClass('open-cart'), !1
        })
      }
    },
    ajaxAddToCart: {
      selector: '.ajax_add_to_cart',
      init () {
        let e
        m(this.selector).on('click', function () {
          e = m(this)
        }),
        d.on('added_to_cart', function () {
          e.find('.thb_button_icon').html(themeajax.l10n.added_svg), e.find('span').text(themeajax.l10n.added)
        })
      }
    },
    atvImg: {
      selector: '.atvImg',
      init () {
        m(this.selector)
        atvImg(!1)
      }
    },
    albumOverlay: {
      selector: '.album_overlay',
      init () {
        m(this.selector).each(function () {
          const e = m(this)
          const t = e.find('.album_no, h3, hr, aside')
          const a = new TimelineLite({ paused: !0 })
          e.find('hr')
          o.mobile()
            ? (TweenLite.to(e, 0.2, { opacity: 1, ease: y }), TweenLite.to(e.find('hr'), 0.2, { opacity: 1, scaleX: 1, ease: y }))
            : (a.add(TweenLite.to(e, 0.2, { opacity: 1, ease: y })).add(TweenMax.staggerFromTo(t, 0.21, { rotationX: '45deg', y: 20, opacity: 0 }, { rotationX: '0', scaleX: 1, y: 0, opacity: 1, ease: y }, 0.07)),
            e.hoverIntent(
              function () {
                a.timeScale(1).play()
              },
              function () {
                a.timeScale(1.5).reverse()
              }
            ))
        })
      }
    },
    panHover: {
      selector: '.pan-hover',
      init () {
        m(this.selector).each(function () {
          m(this).find('.pan-hover-inside').panr({ moveTarget: '.photo_link', scaleDuration: 0.7, sensitivity: 30, scaleTo: 1.07, panDuration: 1 })
        })
      }
    },
    photoProof: {
      selector: '.proof-it',
      init () {
        const e = m(this.selector)
        const o = m('.download-photos')
        function n () {
          if (o.length) {
            const e = m('.photo.checked')
            const t = e
              .map(function () {
                return m(this).find('.proof-it').data('id')
              })
              .get()
              .join('-')
            const a = new URL(o.attr('href'))
            a.searchParams.set('ids', t), o.data('count', e.length), o.find('span').text('(' + e.length + ')'), o.attr('href', a)
          }
        }
        o.length && n(),
        e.on('click', function () {
          const t = m(this)
          const e = t.data('security')
          const a = t.data('id')
          const i = t.parents('.photo')
          t.addClass('loading'),
          m.ajax(themeajax.url, {
            method: 'POST',
            data: { action: 'thb_proof', security: e, id: a, checked: !i.hasClass('checked') },
            success (e) {
              i.toggleClass('checked'), t.removeClass('loading'), o.length && n()
            }
          })
        }),
        o.on('click', function () {
          if (o.data('count') < 1) { return !1 }
        })
      }
    },
    fixedMe: {
      selector: '.thb-fixed',
      init (e) {
        const t = e || m(this.selector)
        const a = m('#wpadminbar')
        const i = a ? a.outerHeight() : 0
        o.mobile() ||
                        (t.each(function () {
                          m(this).stick_in_parent({ offset_top: i, spacer: '.sticky-content-spacer', recalc_every: 50 })
                        }),
                        m('.post-content, .products, .woocommerce-product-gallery').imagesLoaded(function () {
                          m(document.body).trigger('sticky_kit:recalc')
                        }),
                        b.on(
                          'resize',
                          v.debounce(function () {
                            m(document.body).trigger('sticky_kit:recalc')
                          }, 30)
                        ))
      }
    },
    carousel: {
      selector: '.slick',
      init (e) {
        (e || m(this.selector)).each(function () {
          const e = m(this)
          const t = e.data('columns')
          const a = !0 === e.data('navigation')
          const i = !1 !== e.data('autoplay')
          const o = {
            dots: !0 === e.data('pagination'),
            arrows: a,
            infinite: !1,
            speed: e.data('speed') ? e.data('speed') : 1e3,
            slidesToShow: t,
            autoplay: i,
            autoplaySpeed: 6e3,
            pauseOnHover: !0,
            focusOnSelect: !0,
            adaptiveHeight: !0,
            accessibility: !1,
            fade: !0 === e.data('fade'),
            cssEase: 'ease-in-out',
            prevArrow: '<button type="button" class="slick-nav slick-prev"><i class="fa fa-angle-left"></i></button>',
            nextArrow: '<button type="button" class="slick-nav slick-next"><i class="fa fa-angle-right"></i></button>',
            responsive: [
              { breakpoint: 1025, settings: { slidesToShow: t < 3 ? t : 3 } },
              { breakpoint: 780, settings: { slidesToShow: t < 2 ? t : 2 } },
              { breakpoint: 640, settings: { slidesToShow: t < 2 ? t : 1 } }
            ]
          }
          e.imagesLoaded(function () {
            e.slick(o)
          })
        })
      }
    },
    albumHeight: {
      selector: '.vertical',
      init (e) {
        const t = this
        const a = m(t.selector)
        t.control(a),
        b.resize(
          v.debounce(function () {
            t.control(a)
          }, 50)
        )
      },
      control (e, t) {
        e.offset().top, e.offset()
        const a = m('#wpadminbar')
        a && a.outerHeight()
        e.each(function () {
          const e = m(this).find('.item')
          const t = m('.page-padding').height()
          e.height(t)
        })
      }
    },
    paginationStyle2: {
      selector: '.pagination-style2',
      init () {
        const s = m(this.selector)
        const e = s.data('security')
        const t = m('.thb_load_more')
        let a = 2
        t.on('click', function () {
          const i = m(this)
          const o = i.text()
          const n = i.data('count')
          return (
            i.text(themeajax.l10n.loading).addClass('loading'),
            m.post(themeajax.url, { action: 'thb_ajax', security: e, page: a++ }, function (e) {
              const t = m.parseHTML(m.trim(e))
              const a = t ? t.length : 0
              e === '' || e === 'undefined' || e === 'No More Posts' || e === 'No $args array created'
                ? i.text(themeajax.l10n.nomore).removeClass('loading').off('click')
                : (m(t)
                  .appendTo(s)
                  .hide()
                  .imagesLoaded(function () {
                    m(t).show(),
                    s.data('isotope') && (s.isotope('appended', m(t)), s.isotope('layout')),
                    TweenMax.set(m(t), { opacity: 0, y: 100 }),
                    TweenMax.staggerTo(m(t), 0.25 * a, { y: 0, opacity: 1, ease: Quart.easeOut }, 0.25)
                  }),
                a < n ? i.text(themeajax.l10n.nomore).removeClass('loading') : i.text(o).removeClass('loading'))
            }),
            !1
          )
        })
      }
    },
    paginationStyle3: {
      selector: '.pagination-style3',
      init () {
        const i = m(this.selector)
        const e = i.data('security')
        let t = 2
        const o = i.data('count')
        var n = v.debounce(function () {
          b.scrollTop() >= s.height() - b.height() - 60 &&
                                (b.off('scroll', n),
                                i.addClass('thb-loading'),
                                m.post(themeajax.url, { action: 'thb_ajax', security: e, page: t++ }, function (e) {
                                  const t = m.parseHTML(m.trim(e))
                                  const a = t ? t.length : 0
                                  i.removeClass('thb-loading'),
                                  e === '' ||
                                            e === 'undefined' ||
                                            e === 'No More Posts' ||
                                            e === 'No $args array created' ||
                                            (m(t)
                                              .appendTo(i)
                                              .hide()
                                              .imagesLoaded(function () {
                                                m(t).show(),
                                                i.data('isotope') && (i.isotope('appended', m(t)), i.isotope('layout')),
                                                TweenMax.set(m(t), { opacity: 0, y: 100 }),
                                                TweenMax.staggerTo(m(t), 0.25 * a, { y: 0, opacity: 1, ease: Quart.easeOut }, 0.25)
                                              }),
                                            o <= a && b.on('scroll', n))
                                }))
        }, 30)
        b.scroll(n)
      }
    },
    widgets: {
      selector: '.widget',
      init () {
        const e = m(this.selector)
        m('.thb-demo-holder')
        m('h6', e).on('click', function () {
          return m(this).parents('.widget').toggleClass('active'), !1
        })
      }
    },
    custom_select: {
      selector: 'select:not(.state_select):not(.country_to_state):not(#calc_shipping_state):not(#rating)',
      init () {
        const e = m(this.selector)
        console.log(themeajax.settings.custom_select),
        themeajax.settings.custom_select
          ? e.selectric({ maxHeight: 300, responsive: !0, expandToItemText: !0, arrowButtonMarkup: '<b class="button selectric-button">&#x25be;</b>' }).on('change', function () {
            const e = m(this).val()
            const t = m('.isotope-grid')
            const a = m('.slick.vertical')
            if (t.length > 0) { t.isotope({ filter: e }) } else if (a.length > 0) {
              if ((a.slick('slickUnfilter'), e === '*')) { return }
              a.slick('slickFilter', e), c.albumHeight.init()
            }
          })
          : m('#header select').show()
      }
    },
    variations: {
      selector: 'form.variations_form',
      init () {
        const e = m(this.selector)
        const a = m('#product-images')
        const t = m('.first img', a).attr('src')
        const i = m('p.price', '.product-information').eq(0)
        const o = i.html()
        e.on('show_variation', function (e, t) {
          i.html(t.price_html), t.hasOwnProperty('image') && t.image.src && m('.first img', a).attr('src', t.image.src).attr('srcset', '')
        }).on('reset_image', function () {
          i.html(o), m('.first img', a).attr('src', t).attr('srcset', '')
        })
      }
    },
    quantity: {
      selector: '.quantity:not(.hidden)',
      init () {
        const e = this
        m(e.selector)
        e.initialize(),
        d.on('updated_cart_totals', function () {
          e.initialize()
        })
      },
      initialize () {
        m('div.quantity:not(.buttons_added), td.quantity:not(.buttons_added)')
          .addClass('buttons_added')
          .append('<input type="button" value="+" class="plus" />')
          .prepend('<input type="button" value="-" class="minus" />')
          .end()
          .find('input[type="number"]')
          .attr('type', 'text'),
        m('.plus, .minus').on('click', function () {
          const e = m(this).closest('.quantity').find('.qty')
          let t = parseFloat(e.val())
          let a = parseFloat(e.attr('max'))
          let i = parseFloat(e.attr('min'))
          let o = e.attr('step')
          return (
            (t && t !== '' && t !== 'NaN') || (t = 0),
            (a !== '' && a !== 'NaN') || (a = ''),
            (i !== '' && i !== 'NaN') || (i = 0),
            (o !== 'any' && o !== '' && void 0 !== o && parseFloat(o) !== 'NaN') || (o = 1),
            m(this).is('.plus') ? (a && (a === t || a < t) ? e.val(a) : e.val(t + parseFloat(o))) : i && (i === t || t < i) ? e.val(i) : t > 0 && e.val(t - parseFloat(o)),
            e.trigger('change'),
            !1
          )
        })
      }
    },
    contact: {
      selector: '.contact_map',
      init () {
        const c = this
        const e = m(c.selector)
        const t = m('.contact-content').height()
        const a = TweenLite.to(m('.contact_map'), 1, { y: -t, ease: y }).reverse()
        let i = 0
        b.width() > 1024 &&
                        m('#contact_area.style1').on('mousewheel', function (e) {
                          const t = e.deltaY
                          a.isActive() || (t < 0 && i === 0 ? ((i = 1), a.reversed(!a.reversed())) : t > 0 && i === 1 && ((i = 0), a.reversed(!a.reversed())))
                        }),
        e.each(function () {
          let e
          const t = m(this)
          const a = t.data('map-zoom')
          const i = t.data('map-center-lat')
          const o = t.data('map-center-long')
          const n = t.data('latlong')
          const s = t.data('pin-image')
          switch (d.hasClass('dark-theme') ? '1' : '0') {
            case '1':
              e = [
                { stylers: [{ hue: '#ff1a00' }, { invert_lightness: !0 }, { saturation: -100 }, { lightness: 33 }, { gamma: 0.5 }] },
                { featureType: 'water', elementType: 'geometry', stylers: [{ color: '#2D333C' }] }
              ]
              break
            default:
              e = [
                { featureType: 'poi', stylers: [{ visibility: 'off' }] },
                { stylers: [{ saturation: -70 }, { lightness: 37 }, { gamma: 1.15 }] },
                { elementType: 'labels', stylers: [{ gamma: 0.26 }, { visibility: 'off' }] },
                { featureType: 'road', stylers: [{ lightness: 0 }, { saturation: 0 }, { hue: '#ffffff' }, { gamma: 0 }] },
                { featureType: 'road', elementType: 'labels.text.stroke', stylers: [{ visibility: 'off' }] },
                { featureType: 'road.arterial', elementType: 'geometry', stylers: [{ lightness: 20 }] },
                { featureType: 'road.highway', elementType: 'geometry', stylers: [{ lightness: 50 }, { saturation: 0 }, { hue: '#ffffff' }] },
                { featureType: 'administrative.province', stylers: [{ visibility: 'on' }, { lightness: -50 }] },
                { featureType: 'administrative.province', elementType: 'labels.text.stroke', stylers: [{ visibility: 'off' }] },
                { featureType: 'administrative.province', elementType: 'labels.text', stylers: [{ lightness: 20 }] }
              ]
          }
          themeajax.settings.map_style !== '' && (e = m.parseJSON(themeajax.settings.map_style))
          const l = {
            center: new google.maps.LatLng(i, o),
            styles: e,
            zoom: a,
            draggable: !1,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            scrollwheel: !1,
            panControl: !1,
            zoomControl: !1,
            mapTypeControl: !1,
            scaleControl: !1,
            streetViewControl: !1
          }
          const r = new google.maps.Map(t[0], l)
          google.maps.event.addListenerOnce(r, 'tilesloaded', function () {
            if (s.length > 0) {
              const e = new Image();
              (e.src = s),
              m(e).load(function () {
                c.setMarkers(r, n, s)
              })
            } else { c.setMarkers(r, n, s) }
          })
        })
      },
      setMarkers (l, r, c) {
        const d = []
        function e (e) {
          let t
          const a = r[e].lat_long.split(',')
          const i = new google.maps.MarkerImage(c, null, null, null, new google.maps.Size(42, 61))
          const o = new google.maps.Marker({ position: new google.maps.LatLng(a[0], a[1]), map: l, animation: google.maps.Animation.DROP, icon: i, optimized: !1 })
          const n = '<div class="marker-info-win"><h4 class="marker-heading">' + r[e].title + '</h4><p>' + r[e].information + '</p></div>'
          const s = new InfoBox({
            alignBottom: !0,
            content: n,
            disableAutoPan: !1,
            maxWidth: 360,
            closeBoxMargin: '10px 10px 10px 10px',
            closeBoxURL: 'http://www.google.com/intl/en_us/mapfiles/close.gif',
            pixelOffset: new google.maps.Size(-180, -80),
            zIndex: null,
            infoBoxClearance: new google.maps.Size(1, 1)
          })
          d.push(s),
          google.maps.event.addListener(
            o,
            'click',
            ((t = e),
            function () {
              d[t].open(l, this)
            })
          )
        }
        for (let t = 0; t + 1 <= r.length; t++) { setTimeout(e, 250 * t, t) }
      }
    }
  }
  s.ready(function () {
    c.init()
  })
})(jQuery, this, _)
