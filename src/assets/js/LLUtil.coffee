class window.LLUtil

	# コンストラクタ
	constructor: ()->
		# 変数


	# ブラウザ判定
	checkBrowser: ()->
		userAgent = window.navigator.userAgent.toLowerCase()
		appVersion = window.navigator.appVersion.toLowerCase()
		#console.log userAgent
		
		if userAgent.indexOf('msie') != -1 or userAgent.indexOf('trident') != -1
			# IE
			if appVersion.indexOf('msie 6.') != -1
				'ie'
			else if appVersion.indexOf('msie 7.') != -1
				'ie'
			else if appVersion.indexOf('msie 8.') != -1
				'ie'
			else if appVersion.indexOf('msie 9.') != -1
				'ie'
			else if appVersion.indexOf('msie 10.') != -1
				'ie'
			else if userAgent.indexOf('rv:11') != -1
				'ie'
			else
				'ie'
		else if userAgent.indexOf('edge') != -1
			'edge'
		else if userAgent.indexOf('edg') != -1
			'edg'
		else if userAgent.indexOf('opr') != -1
			'opr'
		else if userAgent.indexOf('opera') != -1
			'opera'
		else if userAgent.indexOf('chrome') != -1
			'chrome'
		else if userAgent.indexOf('safari') != -1
			'safari'
		else if userAgent.indexOf('firefox') != -1
			'firefox'
		else if userAgent.indexOf('gecko') != -1
			'gecko'
		else
			# IE
			false

	# OS判定
	checkOS: ()->
		userAgent = window.navigator.userAgent.toLowerCase()
		if userAgent.indexOf('iphone') != -1
			'iphone'
		else if userAgent.indexOf('ipad') != -1
			'ipad'
		else if userAgent.indexOf('android') != -1
			'android'
		else if userAgent.indexOf('win') > -1
			'windows'
		else if userAgent.indexOf('mac') > -1
			'mac'
		else
			'other'

	# スマホ判定
	checkSmp: ()->
		os = @checkOS()
		if os == 'iphone' or os == 'android'
			true
		else
			false

	# タブレット判定
	checkTbl: ()->
		os = @checkOS()
		if os == 'ipad'
			true
		else
			false

	# toucheイベントが有効かどうか
	checkTouchEvent: ()->
		if 'ontouchstart' of window or navigator.msPointerEnabled then true else false

	# ファイル名取得
	getScriptName: ()->
		url = window.location.href
		filename = url.match(".+/(.+?)\.[a-z]+([\?#;].*)?$")
		if filename
			filename = filename[1]
		else
			filename = ""
		return filename