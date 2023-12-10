# 変数　
resize_timer = false

# 実行
$ ->
	setTimeout ->
		initCommon()
	,100
	return

# 初期化
initCommon = ->
	#リサイズ
	#setResize()
	#スムーススクロール
	setSmoothScroll()
	#イベント
	setEvent()

#image map area
$(window).on 'load scroll', ->
	
	return

#イベント
setEvent = ->
	#スクロール
	scrollTarget = $(getScrollTarget())
	lastScrollTop = 0

	#スクロール
	$(window).scroll ()->
		return

#リサイズ
setResize = ->
	$(window).resize () ->
		if resize_timer!=false
			clearTimeout resize_timer
		resize_timer = setTimeout ->
			return
		, 100

#スムーズスクロール設定
setSmoothScroll = (e) ->
	$(document).on "click", 'a[href^="#"] ,area', (e) ->
		e.preventDefault()
		smoothScroll $(this).attr('href')
		return
	return

#スムーズスクロール実行
smoothScroll = (href) ->
	speed = 600

	if href != '#'
		target = $(if href == '' then 'html' else href)
		position = target.offset().top
		$(getScrollTarget()).stop().animate { scrollTop: position }, speed, 'easeOutExpo'
		false
	else
		true

#スムーズターゲット取得
getScrollTarget = ->
	scrollableElement = undefined
	firefox = navigator.userAgent.match(/Firefox\/([0-9]+)\./)
	if 'scrollingElement' of document
		scrollableElement = document.scrollingElement
	else if ! !window.MSInputMethodContext and ! !document.documentMode
		scrollableElement = document.documentElement
	else if firefox and parseInt(firefox[1]) <= 47
		scrollableElement = document.documentElement
	else
		scrollableElement = document.body
	return scrollableElement


