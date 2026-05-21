class ScrollFlow {

	_elements = [];

	_frames = {};

	_defaultAttributeValues = {
		'peak': 'end',
		'base-value': 0,
		'peak-value': 1,
	}

	constructor() {

		this._registerElements();
		this._registerEventListeners();

	}

	value(progress, max, min = 0) {
		min = Number(min);
		max = Number(max);

		return min + (max - min) * progress;
	}

	colorValue(progress, colorEnd, colorStart) {
		function hexToRgb(hex) {
			hex = hex.substr(1);
			if(hex.length === 3) {
				hex = hex.split("").map(c => c + c).join("");
			}

			const bigint = parseInt(hex, 16);
			return [
				(bigint >> 16) & 255,
				(bigint >> 8) & 255,
				bigint & 255
			];
		}

		const start = hexToRgb(colorStart);
		const end = hexToRgb(colorEnd);

		const r = Math.round(start[0] + (end[0] - start[0]) * progress);
		const g = Math.round(start[1] + (end[1] - start[1]) * progress);
		const b = Math.round(start[2] + (end[2] - start[2]) * progress);

		return `rgb(${r}, ${g}, ${b})`;
	}

	getElements(){
		return this._elements;
	}

	getElementAttribute(el, name, defaultValue = null) {
		if(defaultValue === null && name in this._defaultAttributeValues){
			defaultValue = this._defaultAttributeValues[name];
		}

		const key = `data-scroll-${name}`;
		return el.hasAttribute(key)? el.getAttribute(key) : defaultValue;
	}

	isElementVisible(el){
		return !!el.offsetParent;
	}

	preloadFrames(el){
		const frames = this.getElementAttribute(el, 'frames');
		const frameUrl = this.getElementAttribute(el, 'frame-url');

		if(frames && frameUrl){
			this._frames[el] = {
				'_frames': parseInt(frames),
				'_frames_loaded': 0
			};

			for(let frame = 1; frame < Number(frames)+1; frame++){
				const currentFrameUrl = frameUrl.replace('{frame}', String(frame).padStart(frames.length, '0'));

				const img = new Image();
				const onLoad = () => {
					img.removeEventListener('load', onLoad);
					this._frames[el][frame] = img;

					this._frames[el]['_frames_loaded']++;
					if(this._frames[el]['_frames_loaded'] === this._frames[el]['_frames']){
						this._update();
					}
				}
				img.addEventListener('load', onLoad);

				img.src = currentFrameUrl;
			}
		}
	}



	_registerElements(){
		for(const el of document.querySelectorAll(`[data-scroll-style], [data-scroll-frames], [data-scroll-handler]`)){
			if(!this._elements.includes(el)){
				this._elements.push(el);
			}
		}

		for(const el of this._elements) {
			const condition = el.getAttribute('data-scroll-handler-condition');
			if(condition && condition in window){
				if(!window[condition]()){
					this._elements.splice(this._elements.indexOf(el), 1);
				}
			}
		}
	}

	_registerEventListeners() {
		// Register elements
		window.addEventListener('load', () => this._registerElements());

		// Update
		const observer = new ResizeObserver(() => this._update());
		observer.observe(document.documentElement);

		document.addEventListener('scroll', () => this._update());

		document.addEventListener("DOMContentLoaded", () => this._update());
		window.addEventListener("load", () => this._update());
		setTimeout(() => this._update());
	}

	_update(elements = null) {
		if(document.body){
			const scroll = window.scrollY;
			const maxScroll = document.body.offsetHeight - window.innerHeight;
			const windowProgress = scroll / maxScroll;

			for(const el of elements? elements : this.getElements()){
				const peak = this.getElementAttribute(el, 'peak');

				const rect = el.getBoundingClientRect();
				const topPosition = rect.top + scroll;

				const minTop = topPosition - window.innerHeight;
				const maxTop = topPosition + el.offsetHeight;

				let progress = (scroll - minTop) / (maxTop - minTop);
				progress = Math.max(0, Math.min(1, progress));

				if(peak === 'start'){
					progress = 1 - progress;
				} else if(peak === 'center'){
					progress = 1 - Math.abs(progress - 0.5) * 2;
				}

				const minWindowWidth = this.getElementAttribute(el, 'min-window-width');
				if(minWindowWidth && minWindowWidth > window.innerWidth){
					progress = 1;
				}


				const frames = this.getElementAttribute(el, 'frames');
				const frameUrl = this.getElementAttribute(el, 'frame-url');

				let value = 0;
				let baseValue = this.getElementAttribute(el, 'base-value');
				let peakValue = this.getElementAttribute(el, 'peak-value');
				if(typeof peakValue === 'string' && peakValue.startsWith('#')){
					value = this.colorValue(progress, peakValue, baseValue);
				} else if(frames && frameUrl){
					baseValue = 1;
					peakValue = Number(frames);

					value = this.value(progress, peakValue, baseValue);
				} else {
					baseValue = Number(baseValue);
					peakValue = Number(peakValue);

					value = this.value(progress, peakValue, baseValue);
				}


				const styleParameter = this.getElementAttribute(el, 'style');
				if(styleParameter){
					if(styleParameter === 'transform.scale'){
						el.style.transform = `scale(${value})`;
					} else if(styleParameter === 'transform.rotate'){
						el.style.transform = `rotate(${value}deg)`;
					} else {
						el.style[styleParameter] = value;
					}
				}

				const handlerName = this.getElementAttribute(el, 'handler');
				if(handlerName && handlerName in window){
					const handler = window[handlerName];
					if(typeof handler === 'function'){
						handler({
							target: el,
							scroll,
							progress,
							windowProgress,
							baseValue,
							peakValue,
							value
						});
					}
				}

				if(frames && frameUrl){
					if(!(el in this._frames)){
						this.preloadFrames(el);
					}

					let currentFrame = parseInt(value);

					const autoplay = this.getElementAttribute(el, 'autoplay');
					if(autoplay){
						if(!('_autoplay_offset' in this._frames[el])){
							this._frames[el]['_autoplay_offset'] = 1;
						}

						if(!('_autoplay' in this._frames[el])){
							this._frames[el]['_autoplay'] = setInterval(() => {
								if(this.isElementVisible(el)){
									this._frames[el]['_autoplay_offset']++;
									if(this._frames[el]['_autoplay_offset'] > this._frames[el]['_frames']){
										this._frames[el]['_autoplay_offset'] = 1;
									}
									this._update([el]);
								}
							}, Number(autoplay));
						}

						currentFrame += this._frames[el]['_autoplay_offset'];
						if(currentFrame > peakValue) currentFrame -= peakValue;
					}

					const currentFrameUrl = frameUrl.replace('{frame}', String(currentFrame).padStart(frames.length, '0'));
					el.style.backgroundImage = `url("${currentFrameUrl}")`;
				}
			}
		}
	}

}

window.ScrollFlow = new ScrollFlow();