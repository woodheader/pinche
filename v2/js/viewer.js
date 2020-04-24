/*!
 * Viewer v1.1.0
 * https://fengyuanchen.github.io/viewer
 *
 * Copyright 2015-present Chen Fengyuan
 * Released under the MIT license
 *
 * Date: 2019-12-14T11:48:41.205Z
 */
!function(t,i){"object"==typeof exports&&"undefined"!=typeof module?i(require("jquery")):"function"==typeof define&&define.amd?define(["jquery"],i):i((t=t||self).jQuery)}(this,function(c){"use strict";function i(t){return(i="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t})(t)}function s(t,i){for(var e=0;e<i.length;e++){var n=i[e];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(t,n.key,n)}}function e(i,t){var e=Object.keys(i);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(i);t&&(n=n.filter(function(t){return Object.getOwnPropertyDescriptor(i,t).enumerable})),e.push.apply(e,n)}return e}function u(s){for(var t=1;t<arguments.length;t++){var o=null!=arguments[t]?arguments[t]:{};t%2?e(Object(o),!0).forEach(function(t){var i,e,n;i=s,n=o[e=t],e in i?Object.defineProperty(i,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):i[e]=n}):Object.getOwnPropertyDescriptors?Object.defineProperties(s,Object.getOwnPropertyDescriptors(o)):e(Object(o)).forEach(function(t){Object.defineProperty(s,t,Object.getOwnPropertyDescriptor(o,t))})}return s}c=c&&c.hasOwnProperty("default")?c.default:c;var o={backdrop:!0,button:!0,navbar:!0,title:!0,toolbar:!0,className:"",container:"body",filter:null,fullscreen:!0,initialViewIndex:0,inline:!1,interval:5e3,keyboard:!0,loading:!0,loop:!0,minWidth:200,minHeight:100,movable:!0,rotatable:!0,scalable:!0,zoomable:!0,zoomOnTouch:!0,zoomOnWheel:!0,slideOnTouch:!0,toggleOnDblclick:!0,tooltip:!0,transition:!0,zIndex:2015,zIndexInline:0,zoomRatio:.1,minZoomRatio:.01,maxZoomRatio:100,url:"src",ready:null,show:null,shown:null,hide:null,hidden:null,view:null,viewed:null,zoom:null,zoomed:null},a="undefined"!=typeof window&&void 0!==window.document,r=a?window:{},h=a&&"ontouchstart"in r.document.documentElement,t=a&&"PointerEvent"in r,p="viewer",d="move",m="switch",f="zoom",g="".concat(p,"-active"),w="".concat(p,"-close"),b="".concat(p,"-fade"),y="".concat(p,"-fixed"),x="".concat(p,"-fullscreen"),l="".concat(p,"-fullscreen-exit"),k="".concat(p,"-hide"),n="".concat(p,"-hide-md-down"),v="".concat(p,"-hide-sm-down"),z="".concat(p,"-hide-xs-down"),D="".concat(p,"-in"),T="".concat(p,"-invisible"),E="".concat(p,"-loading"),I="".concat(p,"-move"),O="".concat(p,"-open"),S="".concat(p,"-show"),C="".concat(p,"-transition"),L="click",N="dblclick",q="dragstart",M="hidden",R="hide",Y="keydown",X="load",F=t?"pointerdown":h?"touchstart":"mousedown",P=t?"pointermove":h?"touchmove":"mousemove",j=t?"pointerup pointercancel":h?"touchend touchcancel":"mouseup",W="ready",A="resize",H="show",V="shown",B="transitionend",K="viewed",U="".concat(p,"Action"),Z=/\s\s*/,$=["zoom-in","zoom-out","one-to-one","reset","prev","play","next","rotate-left","rotate-right","flip-horizontal","flip-vertical"];function _(t){return"string"==typeof t}var Q=Number.isNaN||r.isNaN;function G(t){return"number"==typeof t&&!Q(t)}function J(t){return void 0===t}function tt(t){return"object"===i(t)&&null!==t}var it=Object.prototype.hasOwnProperty;function et(t){if(!tt(t))return!1;try{var i=t.constructor,e=i.prototype;return i&&e&&it.call(e,"isPrototypeOf")}catch(t){return!1}}function nt(t){return"function"==typeof t}function st(i,e){if(i&&nt(e))if(Array.isArray(i)||G(i.length)){var t,n=i.length;for(t=0;t<n&&!1!==e.call(i,i[t],t,i);t+=1);}else tt(i)&&Object.keys(i).forEach(function(t){e.call(i,i[t],t,i)});return i}var ot=Object.assign||function(e){for(var t=arguments.length,i=new Array(1<t?t-1:0),n=1;n<t;n++)i[n-1]=arguments[n];return tt(e)&&0<i.length&&i.forEach(function(i){tt(i)&&Object.keys(i).forEach(function(t){e[t]=i[t]})}),e},at=/^(?:width|height|left|top|marginLeft|marginTop)$/;function rt(t,i){var e=t.style;st(i,function(t,i){at.test(i)&&G(t)&&(t+="px"),e[i]=t})}function ht(t,i){return!(!t||!i)&&(t.classList?t.classList.contains(i):-1<t.className.indexOf(i))}function lt(t,i){if(t&&i)if(G(t.length))st(t,function(t){lt(t,i)});else if(t.classList)t.classList.add(i);else{var e=t.className.trim();e?e.indexOf(i)<0&&(t.className="".concat(e," ").concat(i)):t.className=i}}function ct(t,i){t&&i&&(G(t.length)?st(t,function(t){ct(t,i)}):t.classList?t.classList.remove(i):0<=t.className.indexOf(i)&&(t.className=t.className.replace(i,"")))}function ut(t,i,e){i&&(G(t.length)?st(t,function(t){ut(t,i,e)}):e?lt(t,i):ct(t,i))}var dt=/([a-z\d])([A-Z])/g;function mt(t){return t.replace(dt,"$1-$2").toLowerCase()}function ft(t,i){return tt(t[i])?t[i]:t.dataset?t.dataset[i]:t.getAttribute("data-".concat(mt(i)))}function gt(t,i,e){tt(e)?t[i]=e:t.dataset?t.dataset[i]=e:t.setAttribute("data-".concat(mt(i)),e)}var vt=function(){var t=!1;if(a){var i=!1,e=function(){},n=Object.defineProperty({},"once",{get:function(){return t=!0,i},set:function(t){i=t}});r.addEventListener("test",e,n),r.removeEventListener("test",e,n)}return t}();function pt(e,t,n,i){var s=3<arguments.length&&void 0!==i?i:{},o=n;t.trim().split(Z).forEach(function(t){if(!vt){var i=e.listeners;i&&i[t]&&i[t][n]&&(o=i[t][n],delete i[t][n],0===Object.keys(i[t]).length&&delete i[t],0===Object.keys(i).length&&delete e.listeners)}e.removeEventListener(t,o,s)})}function wt(o,t,a,i){var r=3<arguments.length&&void 0!==i?i:{},h=a;t.trim().split(Z).forEach(function(n){if(r.once&&!vt){var t=o.listeners,s=void 0===t?{}:t;h=function(){delete s[n][a],o.removeEventListener(n,h,r);for(var t=arguments.length,i=new Array(t),e=0;e<t;e++)i[e]=arguments[e];a.apply(o,i)},s[n]||(s[n]={}),s[n][a]&&o.removeEventListener(n,s[n][a],r),s[n][a]=h,o.listeners=s}o.addEventListener(n,h,r)})}function bt(t,i,e){var n;return nt(Event)&&nt(CustomEvent)?n=new CustomEvent(i,{detail:e,bubbles:!0,cancelable:!0}):(n=document.createEvent("CustomEvent")).initCustomEvent(i,!0,!0,e),t.dispatchEvent(n)}function yt(t){var i=t.rotate,e=t.scaleX,n=t.scaleY,s=t.translateX,o=t.translateY,a=[];G(s)&&0!==s&&a.push("translateX(".concat(s,"px)")),G(o)&&0!==o&&a.push("translateY(".concat(o,"px)")),G(i)&&0!==i&&a.push("rotate(".concat(i,"deg)")),G(e)&&1!==e&&a.push("scaleX(".concat(e,")")),G(n)&&1!==n&&a.push("scaleY(".concat(n,")"));var r=a.length?a.join(" "):"none";return{WebkitTransform:r,msTransform:r,transform:r}}var xt=r.navigator&&/(Macintosh|iPhone|iPod|iPad).*AppleWebKit/i.test(r.navigator.userAgent);function kt(t,i){var e=document.createElement("img");if(t.naturalWidth&&!xt)return i(t.naturalWidth,t.naturalHeight),e;var n=document.body||document.documentElement;return e.onload=function(){i(e.width,e.height),xt||n.removeChild(e)},e.src=t.src,xt||(e.style.cssText="left:0;max-height:none!important;max-width:none!important;min-height:0!important;min-width:0!important;opacity:0;position:absolute;top:0;z-index:-1;",n.appendChild(e)),e}function zt(t){switch(t){case 2:return z;case 3:return v;case 4:return n;default:return""}}function Dt(t,i){var e=t.pageX,n=t.pageY,s={endX:e,endY:n};return i?s:u({timeStamp:Date.now(),startX:e,startY:n},s)}var Tt={render:function(){this.initContainer(),this.initViewer(),this.initList(),this.renderViewer()},initContainer:function(){this.containerData={width:window.innerWidth,height:window.innerHeight}},initViewer:function(){var t,i=this.options,e=this.parent;i.inline&&(t={width:Math.max(e.offsetWidth,i.minWidth),height:Math.max(e.offsetHeight,i.minHeight)},this.parentData=t),!this.fulled&&t||(t=this.containerData),this.viewerData=ot({},t)},renderViewer:function(){this.options.inline&&!this.fulled&&rt(this.viewer,this.viewerData)},initList:function(){var h=this,t=this.element,l=this.options,c=this.list,u=[];c.innerHTML="",st(this.images,function(t,i){var e,n=t.src,s=t.alt||(_(e=n)?decodeURIComponent(e.replace(/^.*\//,"").replace(/[?&#].*$/,"")):""),o=l.url;if(_(o)?o=t.getAttribute(o):nt(o)&&(o=o.call(h,t)),n||o){var a=document.createElement("li"),r=document.createElement("img");r.src=n||o,r.alt=s,r.setAttribute("data-index",i),r.setAttribute("data-original-url",o||n),r.setAttribute("data-viewer-action","view"),r.setAttribute("role","button"),a.appendChild(r),c.appendChild(a),u.push(a)}}),st(this.items=u,function(i){var t=i.firstElementChild;gt(t,"filled",!0),l.loading&&lt(i,E),wt(t,X,function(t){l.loading&&ct(i,E),h.loadImage(t)},{once:!0})}),l.transition&&wt(t,K,function(){lt(c,C)},{once:!0})},renderList:function(t){var i=t||this.index,e=this.items[i].offsetWidth||30,n=e+1;rt(this.list,ot({width:n*this.length},yt({translateX:(this.viewerData.width-e)/2-n*i})))},resetList:function(){var t=this.list;t.innerHTML="",ct(t,C),rt(t,yt({translateX:0}))},initImage:function(r){var t,h=this,l=this.options,i=this.image,e=this.viewerData,n=this.footer.offsetHeight,c=e.width,u=Math.max(e.height-n,n),d=this.imageData||{};this.imageInitializing={abort:function(){t.onload=null}},t=kt(i,function(t,i){var e=t/i,n=c,s=u;h.imageInitializing=!1,c<u*e?s=c/e:n=u*e;var o={naturalWidth:t,naturalHeight:i,aspectRatio:e,ratio:(n=Math.min(.9*n,t))/t,width:n,height:s=Math.min(.9*s,i),left:(c-n)/2,top:(u-s)/2},a=ot({},o);l.rotatable&&(o.rotate=d.rotate||0,a.rotate=0),l.scalable&&(o.scaleX=d.scaleX||1,o.scaleY=d.scaleY||1,a.scaleX=1,a.scaleY=1),h.imageData=o,h.initialImageData=a,r&&r()})},renderImage:function(t){var i=this,e=this.image,n=this.imageData;if(rt(e,ot({width:n.width,height:n.height,marginLeft:n.left,marginTop:n.top},yt(n))),t)if((this.viewing||this.zooming)&&this.options.transition){var s=function(){i.imageRendering=!1,t()};this.imageRendering={abort:function(){pt(e,B,s)}},wt(e,B,s,{once:!0})}else t()},resetImage:function(){if(this.viewing||this.viewed){var t=this.image;this.viewing&&this.viewing.abort(),t.parentNode.removeChild(t),this.image=null}}},Et={bind:function(){var t=this.options,i=this.viewer,e=this.canvas,n=this.element.ownerDocument;wt(i,L,this.onClick=this.click.bind(this)),wt(i,q,this.onDragStart=this.dragstart.bind(this)),wt(e,F,this.onPointerDown=this.pointerdown.bind(this)),wt(n,P,this.onPointerMove=this.pointermove.bind(this)),wt(n,j,this.onPointerUp=this.pointerup.bind(this)),wt(n,Y,this.onKeyDown=this.keydown.bind(this)),wt(window,A,this.onResize=this.resize.bind(this)),t.zoomable&&t.zoomOnWheel&&wt(i,"wheel",this.onWheel=this.wheel.bind(this),{passive:!1,capture:!0}),t.toggleOnDblclick&&wt(e,N,this.onDblclick=this.dblclick.bind(this))},unbind:function(){var t=this.options,i=this.viewer,e=this.canvas,n=this.element.ownerDocument;pt(i,L,this.onClick),pt(i,q,this.onDragStart),pt(e,F,this.onPointerDown),pt(n,P,this.onPointerMove),pt(n,j,this.onPointerUp),pt(n,Y,this.onKeyDown),pt(window,A,this.onResize),t.zoomable&&t.zoomOnWheel&&pt(i,"wheel",this.onWheel,{passive:!1,capture:!0}),t.toggleOnDblclick&&pt(e,N,this.onDblclick)}},It={click:function(t){var i=t.target,e=this.options,n=this.imageData,s=ft(i,U);switch(h&&t.isTrusted&&i===this.canvas&&clearTimeout(this.clickCanvasTimeout),s){case"mix":this.played?this.stop():e.inline?this.fulled?this.exit():this.full():this.hide();break;case"hide":this.hide();break;case"view":this.view(ft(i,"index"));break;case"zoom-in":this.zoom(.1,!0);break;case"zoom-out":this.zoom(-.1,!0);break;case"one-to-one":this.toggle();break;case"reset":this.reset();break;case"prev":this.prev(e.loop);break;case"play":this.play(e.fullscreen);break;case"next":this.next(e.loop);break;case"rotate-left":this.rotate(-90);break;case"rotate-right":this.rotate(90);break;case"flip-horizontal":this.scaleX(-n.scaleX||-1);break;case"flip-vertical":this.scaleY(-n.scaleY||-1);break;default:this.played&&this.stop()}},dblclick:function(t){t.preventDefault(),this.viewed&&t.target===this.image&&(h&&t.isTrusted&&clearTimeout(this.doubleClickImageTimeout),this.toggle())},load:function(){var t=this;this.timeout&&(clearTimeout(this.timeout),this.timeout=!1);var i=this.element,e=this.options,n=this.image,s=this.index,o=this.viewerData;ct(n,T),e.loading&&ct(this.canvas,E),n.style.cssText="height:0;"+"margin-left:".concat(o.width/2,"px;")+"margin-top:".concat(o.height/2,"px;")+"max-width:none!important;position:absolute;width:0;",this.initImage(function(){ut(n,I,e.movable),ut(n,C,e.transition),t.renderImage(function(){t.viewed=!0,t.viewing=!1,nt(e.viewed)&&wt(i,K,e.viewed,{once:!0}),bt(i,K,{originalImage:t.images[s],index:s,image:n})})})},loadImage:function(t){var o=t.target,i=o.parentNode,a=i.offsetWidth||30,r=i.offsetHeight||50,h=!!ft(o,"filled");kt(o,function(t,i){var e=t/i,n=a,s=r;a<r*e?h?n=r*e:s=a/e:h?s=a/e:n=r*e,rt(o,ot({width:n,height:s},yt({translateX:(a-n)/2,translateY:(r-s)/2})))})},keydown:function(t){var i=this.options;if(this.fulled&&i.keyboard)switch(t.keyCode||t.which||t.charCode){case 27:this.played?this.stop():i.inline?this.fulled&&this.exit():this.hide();break;case 32:this.played&&this.stop();break;case 37:this.prev(i.loop);break;case 38:t.preventDefault(),this.zoom(i.zoomRatio,!0);break;case 39:this.next(i.loop);break;case 40:t.preventDefault(),this.zoom(-i.zoomRatio,!0);break;case 48:case 49:t.ctrlKey&&(t.preventDefault(),this.toggle())}},dragstart:function(t){"img"===t.target.tagName.toLowerCase()&&t.preventDefault()},pointerdown:function(t){var i=this.options,e=this.pointers,n=t.buttons,s=t.button;if(!(!this.viewed||this.showing||this.viewing||this.hiding||("mousedown"===t.type||"pointerdown"===t.type&&"mouse"===t.pointerType)&&(G(n)&&1!==n||G(s)&&0!==s||t.ctrlKey))){t.preventDefault(),t.changedTouches?st(t.changedTouches,function(t){e[t.identifier]=Dt(t)}):e[t.pointerId||0]=Dt(t);var o=!!i.movable&&d;i.zoomOnTouch&&i.zoomable&&1<Object.keys(e).length?o=f:i.slideOnTouch&&("touch"===t.pointerType||"touchstart"===t.type)&&this.isSwitchable()&&(o=m),!i.transition||o!==d&&o!==f||ct(this.image,C),this.action=o}},pointermove:function(t){var i=this.pointers,e=this.action;this.viewed&&e&&(t.preventDefault(),t.changedTouches?st(t.changedTouches,function(t){ot(i[t.identifier]||{},Dt(t,!0))}):ot(i[t.pointerId||0]||{},Dt(t,!0)),this.change(t))},pointerup:function(t){var i,e=this,n=this.options,s=this.action,o=this.pointers;t.changedTouches?st(t.changedTouches,function(t){i=o[t.identifier],delete o[t.identifier]}):(i=o[t.pointerId||0],delete o[t.pointerId||0]),s&&(t.preventDefault(),!n.transition||s!==d&&s!==f||lt(this.image,C),this.action=!1,h&&s!==f&&i&&Date.now()-i.timeStamp<500&&(clearTimeout(this.clickCanvasTimeout),clearTimeout(this.doubleClickImageTimeout),n.toggleOnDblclick&&this.viewed&&t.target===this.image?this.imageClicked?(this.imageClicked=!1,this.doubleClickImageTimeout=setTimeout(function(){bt(e.image,N)},50)):(this.imageClicked=!0,this.doubleClickImageTimeout=setTimeout(function(){e.imageClicked=!1},500)):(this.imageClicked=!1,n.backdrop&&"static"!==n.backdrop&&t.target===this.canvas&&(this.clickCanvasTimeout=setTimeout(function(){bt(e.canvas,L)},50)))))},resize:function(){var i=this;if(this.isShown&&!this.hiding&&(this.initContainer(),this.initViewer(),this.renderViewer(),this.renderList(),this.viewed&&this.initImage(function(){i.renderImage()}),this.played)){if(this.options.fullscreen&&this.fulled&&!(document.fullscreenElement||document.webkitFullscreenElement||document.mozFullScreenElement||document.msFullscreenElement))return void this.stop();st(this.player.getElementsByTagName("img"),function(t){wt(t,X,i.loadImage.bind(i),{once:!0}),bt(t,X)})}},wheel:function(t){var i=this;if(this.viewed&&(t.preventDefault(),!this.wheeling)){this.wheeling=!0,setTimeout(function(){i.wheeling=!1},50);var e=Number(this.options.zoomRatio)||.1,n=1;t.deltaY?n=0<t.deltaY?1:-1:t.wheelDelta?n=-t.wheelDelta/120:t.detail&&(n=0<t.detail?1:-1),this.zoom(-n*e,!0,t)}}},Ot={show:function(t){var i=0<arguments.length&&void 0!==t&&t,e=this.element,n=this.options;if(n.inline||this.showing||this.isShown||this.showing)return this;if(!this.ready)return this.build(),this.ready&&this.show(i),this;if(nt(n.show)&&wt(e,H,n.show,{once:!0}),!1===bt(e,H)||!this.ready)return this;this.hiding&&this.transitioning.abort(),this.showing=!0,this.open();var s=this.viewer;if(ct(s,k),n.transition&&!i){var o=this.shown.bind(this);this.transitioning={abort:function(){pt(s,B,o),ct(s,D)}},lt(s,C),s.initialOffsetWidth=s.offsetWidth,wt(s,B,o,{once:!0}),lt(s,D)}else lt(s,D),this.shown();return this},hide:function(){var t=0<arguments.length&&void 0!==arguments[0]&&arguments[0],i=this.element,e=this.options;if(e.inline||this.hiding||!this.isShown&&!this.showing)return this;if(nt(e.hide)&&wt(i,R,e.hide,{once:!0}),!1===bt(i,R))return this;this.showing&&this.transitioning.abort(),this.hiding=!0,this.played?this.stop():this.viewing&&this.viewing.abort();var n=this.viewer;if(e.transition&&!t){var s=this.hidden.bind(this),o=function(){setTimeout(function(){wt(n,B,s,{once:!0}),ct(n,D)},0)};this.transitioning={abort:function(){this.viewed?pt(this.image,B,o):pt(n,B,s)}},this.viewed&&ht(this.image,C)?(wt(this.image,B,o,{once:!0}),this.zoomTo(0,!1,!1,!0)):o()}else ct(n,D),this.hidden();return this},view:function(t){var n=this,i=0<arguments.length&&void 0!==t?t:this.options.initialViewIndex;if(i=Number(i)||0,this.hiding||this.played||i<0||i>=this.length||this.viewed&&i===this.index)return this;if(!this.isShown)return this.index=i,this.show();this.viewing&&this.viewing.abort();var e=this.element,s=this.options,o=this.title,a=this.canvas,r=this.items[i],h=r.querySelector("img"),l=ft(h,"originalUrl"),c=h.getAttribute("alt"),u=document.createElement("img");if(u.src=l,u.alt=c,nt(s.view)&&wt(e,"view",s.view,{once:!0}),!1===bt(e,"view",{originalImage:this.images[i],index:i,image:u})||!this.isShown||this.hiding||this.played)return this;this.image=u,ct(this.items[this.index],g),lt(r,g),this.viewed=!1,this.index=i,this.imageData={},lt(u,T),s.loading&&lt(a,E),a.innerHTML="",a.appendChild(u),this.renderList(),o.innerHTML="";function d(){var t,i=n.imageData,e=Array.isArray(s.title)?s.title[1]:s.title;o.innerHTML=_(t=nt(e)?e.call(n,u,i):"".concat(c," (").concat(i.naturalWidth," × ").concat(i.naturalHeight,")"))?t.replace(/&(?!amp;|quot;|#39;|lt;|gt;)/g,"&amp;").replace(/"/g,"&quot;").replace(/'/g,"&#39;").replace(/</g,"&lt;").replace(/>/g,"&gt;"):t}var m;return wt(e,K,d,{once:!0}),this.viewing={abort:function(){pt(e,K,d),u.complete?this.imageRendering?this.imageRendering.abort():this.imageInitializing&&this.imageInitializing.abort():(u.src="",pt(u,X,m),this.timeout&&clearTimeout(this.timeout))}},u.complete?this.load():(wt(u,X,m=this.load.bind(this),{once:!0}),this.timeout&&clearTimeout(this.timeout),this.timeout=setTimeout(function(){ct(u,T),n.timeout=!1},1e3)),this},prev:function(t){var i=0<arguments.length&&void 0!==t&&t,e=this.index-1;return e<0&&(e=i?this.length-1:0),this.view(e),this},next:function(t){var i=0<arguments.length&&void 0!==t&&t,e=this.length-1,n=this.index+1;return e<n&&(n=i?0:e),this.view(n),this},move:function(t,i){var e=this.imageData;return this.moveTo(J(t)?t:e.left+Number(t),J(i)?i:e.top+Number(i)),this},moveTo:function(t,i){var e=1<arguments.length&&void 0!==i?i:t,n=this.imageData;if(t=Number(t),e=Number(e),this.viewed&&!this.played&&this.options.movable){var s=!1;G(t)&&(n.left=t,s=!0),G(e)&&(n.top=e,s=!0),s&&this.renderImage()}return this},zoom:function(t,i,e){var n=1<arguments.length&&void 0!==i&&i,s=2<arguments.length&&void 0!==e?e:null,o=this.imageData;return t=(t=Number(t))<0?1/(1-t):1+t,this.zoomTo(o.width*t/o.naturalWidth,n,s),this},zoomTo:function(t,i,e,n){var s,o,a,r,h,l=this,c=1<arguments.length&&void 0!==i&&i,u=2<arguments.length&&void 0!==e?e:null,d=3<arguments.length&&void 0!==n&&n,m=this.element,f=this.options,g=this.pointers,v=this.imageData,p=v.width,w=v.height,b=v.left,y=v.top,x=v.naturalWidth,k=v.naturalHeight;if(G(t=Math.max(0,t))&&this.viewed&&!this.played&&(d||f.zoomable)){if(!d){var z=Math.max(.01,f.minZoomRatio),D=Math.min(100,f.maxZoomRatio);t=Math.min(Math.max(t,z),D)}u&&.95<t&&t<1.05&&(t=1);var T=x*t,E=k*t,I=T-p,O=E-w,S=p/x;if(nt(f.zoom)&&wt(m,"zoom",f.zoom,{once:!0}),!1===bt(m,"zoom",{ratio:t,oldRatio:S,originalEvent:u}))return this;if(this.zooming=!0,u){var C=(r=this.viewer,{left:(h=r.getBoundingClientRect()).left+(window.pageXOffset-document.documentElement.clientLeft),top:h.top+(window.pageYOffset-document.documentElement.clientTop)}),L=g&&Object.keys(g).length?(a=o=s=0,st(g,function(t){var i=t.startX,e=t.startY;s+=i,o+=e,a+=1}),{pageX:s/=a,pageY:o/=a}):{pageX:u.pageX,pageY:u.pageY};v.left-=(L.pageX-C.left-b)/p*I,v.top-=(L.pageY-C.top-y)/w*O}else v.left-=I/2,v.top-=O/2;v.width=T,v.height=E,v.ratio=t,this.renderImage(function(){l.zooming=!1,nt(f.zoomed)&&wt(m,"zoomed",f.zoomed,{once:!0}),bt(m,"zoomed",{ratio:t,oldRatio:S,originalEvent:u})}),c&&this.tooltip()}return this},rotate:function(t){return this.rotateTo((this.imageData.rotate||0)+Number(t)),this},rotateTo:function(t){var i=this.imageData;return G(t=Number(t))&&this.viewed&&!this.played&&this.options.rotatable&&(i.rotate=t,this.renderImage()),this},scaleX:function(t){return this.scale(t,this.imageData.scaleY),this},scaleY:function(t){return this.scale(this.imageData.scaleX,t),this},scale:function(t,i){var e=1<arguments.length&&void 0!==i?i:t,n=this.imageData;if(t=Number(t),e=Number(e),this.viewed&&!this.played&&this.options.scalable){var s=!1;G(t)&&(n.scaleX=t,s=!0),G(e)&&(n.scaleY=e,s=!0),s&&this.renderImage()}return this},play:function(){var i=this,t=0<arguments.length&&void 0!==arguments[0]&&arguments[0];if(!this.isShown||this.played)return this;var s=this.options,o=this.player,a=this.loadImage.bind(this),r=[],h=0,l=0;if(this.played=!0,this.onLoadWhenPlay=a,t&&this.requestFullscreen(),lt(o,S),st(this.items,function(t,i){var e=t.querySelector("img"),n=document.createElement("img");n.src=ft(e,"originalUrl"),n.alt=e.getAttribute("alt"),h+=1,lt(n,b),ut(n,C,s.transition),ht(t,g)&&(lt(n,D),l=i),r.push(n),wt(n,X,a,{once:!0}),o.appendChild(n)}),G(s.interval)&&0<s.interval){var e=function t(){i.playing=setTimeout(function(){ct(r[l],D),lt(r[l=(l+=1)<h?l:0],D),t()},s.interval)};1<h&&e()}return this},stop:function(){var i=this;if(!this.played)return this;var t=this.player;return this.played=!1,clearTimeout(this.playing),st(t.getElementsByTagName("img"),function(t){pt(t,X,i.onLoadWhenPlay)}),ct(t,S),t.innerHTML="",this.exitFullscreen(),this},full:function(){var t=this,i=this.options,e=this.viewer,n=this.image,s=this.list;return!this.isShown||this.played||this.fulled||!i.inline||(this.fulled=!0,this.open(),lt(this.button,l),i.transition&&(ct(s,C),this.viewed&&ct(n,C)),lt(e,y),e.setAttribute("style",""),rt(e,{zIndex:i.zIndex}),this.initContainer(),this.viewerData=ot({},this.containerData),this.renderList(),this.viewed&&this.initImage(function(){t.renderImage(function(){i.transition&&setTimeout(function(){lt(n,C),lt(s,C)},0)})})),this},exit:function(){var t=this,i=this.options,e=this.viewer,n=this.image,s=this.list;return this.isShown&&!this.played&&this.fulled&&i.inline&&(this.fulled=!1,this.close(),ct(this.button,l),i.transition&&(ct(s,C),this.viewed&&ct(n,C)),ct(e,y),rt(e,{zIndex:i.zIndexInline}),this.viewerData=ot({},this.parentData),this.renderViewer(),this.renderList(),this.viewed&&this.initImage(function(){t.renderImage(function(){i.transition&&setTimeout(function(){lt(n,C),lt(s,C)},0)})})),this},tooltip:function(){var t=this,i=this.options,e=this.tooltipBox,n=this.imageData;return this.viewed&&!this.played&&i.tooltip&&(e.textContent="".concat(Math.round(100*n.ratio),"%"),this.tooltipping?clearTimeout(this.tooltipping):i.transition?(this.fading&&bt(e,B),lt(e,S),lt(e,b),lt(e,C),e.initialOffsetWidth=e.offsetWidth,lt(e,D)):lt(e,S),this.tooltipping=setTimeout(function(){i.transition?(wt(e,B,function(){ct(e,S),ct(e,b),ct(e,C),t.fading=!1},{once:!0}),ct(e,D),t.fading=!0):ct(e,S),t.tooltipping=!1},1e3)),this},toggle:function(){return 1===this.imageData.ratio?this.zoomTo(this.initialImageData.ratio,!0):this.zoomTo(1,!0),this},reset:function(){return this.viewed&&!this.played&&(this.imageData=ot({},this.initialImageData),this.renderImage()),this},update:function(){var t=this.element,i=this.options,e=this.isImg;if(e&&!t.parentNode)return this.destroy();var s=[];if(st(e?[t]:t.querySelectorAll("img"),function(t){i.filter?i.filter(t)&&s.push(t):s.push(t)}),!s.length)return this;if(this.images=s,this.length=s.length,this.ready){var o=[];if(st(this.items,function(t,i){var e=t.querySelector("img"),n=s[i];n&&e?n.src!==e.src&&o.push(i):o.push(i)}),rt(this.list,{width:"auto"}),this.initList(),this.isShown)if(this.length){if(this.viewed){var n=o.indexOf(this.index);0<=n?(this.viewed=!1,this.view(Math.max(this.index-(n+1),0))):lt(this.items[this.index],g)}}else this.image=null,this.viewed=!1,this.index=0,this.imageData={},this.canvas.innerHTML="",this.title.innerHTML=""}else this.build();return this},destroy:function(){var t=this.element,i=this.options;return t[p]&&(this.destroyed=!0,this.ready?(this.played&&this.stop(),i.inline?(this.fulled&&this.exit(),this.unbind()):this.isShown?(this.viewing&&(this.imageRendering?this.imageRendering.abort():this.imageInitializing&&this.imageInitializing.abort()),this.hiding&&this.transitioning.abort(),this.hidden()):this.showing&&(this.transitioning.abort(),this.hidden()),this.ready=!1,this.viewer.parentNode.removeChild(this.viewer)):i.inline&&(this.delaying?this.delaying.abort():this.initializing&&this.initializing.abort()),i.inline||pt(t,L,this.onStart),t[p]=void 0),this}},St={open:function(){var t=this.body;lt(t,O),t.style.paddingRight="".concat(this.scrollbarWidth+(parseFloat(this.initialBodyPaddingRight)||0),"px")},close:function(){var t=this.body;ct(t,O),t.style.paddingRight=this.initialBodyPaddingRight},shown:function(){var t=this.element,i=this.options;this.fulled=!0,this.isShown=!0,this.render(),this.bind(),this.showing=!1,nt(i.shown)&&wt(t,V,i.shown,{once:!0}),!1!==bt(t,V)&&this.ready&&this.isShown&&!this.hiding&&this.view(this.index)},hidden:function(){var t=this.element,i=this.options;this.fulled=!1,this.viewed=!1,this.isShown=!1,this.close(),this.unbind(),lt(this.viewer,k),this.resetList(),this.resetImage(),this.hiding=!1,this.destroyed||(nt(i.hidden)&&wt(t,M,i.hidden,{once:!0}),bt(t,M))},requestFullscreen:function(){var t=this.element.ownerDocument;if(this.fulled&&!(t.fullscreenElement||t.webkitFullscreenElement||t.mozFullScreenElement||t.msFullscreenElement)){var i=t.documentElement;i.requestFullscreen?i.requestFullscreen():i.webkitRequestFullscreen?i.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT):i.mozRequestFullScreen?i.mozRequestFullScreen():i.msRequestFullscreen&&i.msRequestFullscreen()}},exitFullscreen:function(){var t=this.element.ownerDocument;this.fulled&&(t.fullscreenElement||t.webkitFullscreenElement||t.mozFullScreenElement||t.msFullscreenElement)&&(t.exitFullscreen?t.exitFullscreen():t.webkitExitFullscreen?t.webkitExitFullscreen():t.mozCancelFullScreen?t.mozCancelFullScreen():t.msExitFullscreen&&t.msExitFullscreen())},change:function(t){var i,e,h,n=this.options,s=this.pointers,o=s[Object.keys(s)[0]],a=o.endX-o.startX,r=o.endY-o.startY;switch(this.action){case d:this.move(a,r);break;case f:this.zoom((e=u({},i=s),h=[],st(i,function(r,t){delete e[t],st(e,function(t){var i=Math.abs(r.startX-t.startX),e=Math.abs(r.startY-t.startY),n=Math.abs(r.endX-t.endX),s=Math.abs(r.endY-t.endY),o=Math.sqrt(i*i+e*e),a=(Math.sqrt(n*n+s*s)-o)/o;h.push(a)})}),h.sort(function(t,i){return Math.abs(t)<Math.abs(i)}),h[0]),!1,t);break;case m:this.action="switched";var l=Math.abs(a);1<l&&l>Math.abs(r)&&(this.pointers={},1<a?this.prev(n.loop):a<-1&&this.next(n.loop))}st(s,function(t){t.startX=t.endX,t.startY=t.endY})},isSwitchable:function(){var t=this.imageData,i=this.viewerData;return 1<this.length&&0<=t.left&&0<=t.top&&t.width<=i.width&&t.height<=i.height}},Ct=r.Viewer,Lt=function(){function e(t){var i=1<arguments.length&&void 0!==arguments[1]?arguments[1]:{};if(!function(t,i){if(!(t instanceof i))throw new TypeError("Cannot call a class as a function")}(this,e),!t||1!==t.nodeType)throw new Error("The first argument is required and must be an element.");this.element=t,this.options=ot({},o,et(i)&&i),this.action=!1,this.fading=!1,this.fulled=!1,this.hiding=!1,this.imageClicked=!1,this.imageData={},this.index=this.options.initialViewIndex,this.isImg=!1,this.isShown=!1,this.length=0,this.played=!1,this.playing=!1,this.pointers={},this.ready=!1,this.showing=!1,this.timeout=!1,this.tooltipping=!1,this.viewed=!1,this.viewing=!1,this.wheeling=!1,this.zooming=!1,this.init()}var t,i,n;return t=e,n=[{key:"noConflict",value:function(){return window.Viewer=Ct,e}},{key:"setDefaults",value:function(t){ot(o,et(t)&&t)}}],(i=[{key:"init",value:function(){var e=this,t=this.element,n=this.options;if(!t[p]){t[p]=this;var i="img"===t.tagName.toLowerCase(),s=[];st(i?[t]:t.querySelectorAll("img"),function(t){nt(n.filter)?n.filter.call(e,t)&&s.push(t):s.push(t)}),this.isImg=i,this.length=s.length,this.images=s;var o=t.ownerDocument,a=o.body||o.documentElement;if(this.body=a,this.scrollbarWidth=window.innerWidth-o.documentElement.clientWidth,this.initialBodyPaddingRight=window.getComputedStyle(a).paddingRight,J(document.createElement(p).style.transition)&&(n.transition=!1),n.inline){var r=0,h=function(){var t;(r+=1)===e.length&&(e.initializing=!1,e.delaying={abort:function(){clearTimeout(t)}},t=setTimeout(function(){e.delaying=!1,e.build()},0))};this.initializing={abort:function(){st(s,function(t){t.complete||pt(t,X,h)})}},st(s,function(t){t.complete?h():wt(t,X,h,{once:!0})})}else wt(t,L,this.onStart=function(t){var i=t.target;"img"!==i.tagName.toLowerCase()||nt(n.filter)&&!n.filter.call(e,i)||e.view(e.images.indexOf(i))})}}},{key:"build",value:function(){if(!this.ready){var t=this.element,h=this.options,i=t.parentNode,e=document.createElement("div");e.innerHTML='<div class="viewer-container" touch-action="none"><div class="viewer-canvas"></div><div class="viewer-footer"><div class="viewer-title"></div><div class="viewer-toolbar"></div><div class="viewer-navbar"><ul class="viewer-list"></ul></div></div><div class="viewer-tooltip"></div><div role="button" class="viewer-button" data-viewer-action="mix"></div><div class="viewer-player"></div></div>';var n=e.querySelector(".".concat(p,"-container")),s=n.querySelector(".".concat(p,"-title")),o=n.querySelector(".".concat(p,"-toolbar")),a=n.querySelector(".".concat(p,"-navbar")),r=n.querySelector(".".concat(p,"-button")),l=n.querySelector(".".concat(p,"-canvas"));if(this.parent=i,this.viewer=n,this.title=s,this.toolbar=o,this.navbar=a,this.button=r,this.canvas=l,this.footer=n.querySelector(".".concat(p,"-footer")),this.tooltipBox=n.querySelector(".".concat(p,"-tooltip")),this.player=n.querySelector(".".concat(p,"-player")),this.list=n.querySelector(".".concat(p,"-list")),lt(s,h.title?zt(Array.isArray(h.title)?h.title[0]:h.title):k),lt(a,h.navbar?zt(h.navbar):k),ut(r,k,!h.button),h.backdrop&&(lt(n,"".concat(p,"-backdrop")),h.inline||"static"===h.backdrop||gt(l,U,"hide")),_(h.className)&&h.className&&h.className.split(Z).forEach(function(t){lt(n,t)}),h.toolbar){var c=document.createElement("ul"),u=et(h.toolbar),d=$.slice(0,3),m=$.slice(7,9),f=$.slice(9);u||lt(o,zt(h.toolbar)),st(u?h.toolbar:$,function(t,i){var e=u&&et(t),n=u?mt(i):t,s=e&&!J(t.show)?t.show:t;if(s&&(h.zoomable||-1===d.indexOf(n))&&(h.rotatable||-1===m.indexOf(n))&&(h.scalable||-1===f.indexOf(n))){var o=e&&!J(t.size)?t.size:t,a=e&&!J(t.click)?t.click:t,r=document.createElement("li");r.setAttribute("role","button"),lt(r,"".concat(p,"-").concat(n)),nt(a)||gt(r,U,n),G(s)&&lt(r,zt(s)),-1!==["small","large"].indexOf(o)?lt(r,"".concat(p,"-").concat(o)):"play"===n&&lt(r,"".concat(p,"-large")),nt(a)&&wt(r,L,a),c.appendChild(r)}}),o.appendChild(c)}else lt(o,k);if(!h.rotatable){var g=o.querySelectorAll('li[class*="rotate"]');lt(g,T),st(g,function(t){o.appendChild(t)})}if(h.inline)lt(r,x),rt(n,{zIndex:h.zIndexInline}),"static"===window.getComputedStyle(i).position&&rt(i,{position:"relative"}),i.insertBefore(n,t.nextSibling);else{lt(r,w),lt(n,y),lt(n,b),lt(n,k),rt(n,{zIndex:h.zIndex});var v=h.container;_(v)&&(v=t.ownerDocument.querySelector(v)),(v=v||this.body).appendChild(n)}h.inline&&(this.render(),this.bind(),this.isShown=!0),this.ready=!0,nt(h.ready)&&wt(t,W,h.ready,{once:!0}),!1!==bt(t,W)?this.ready&&h.inline&&this.view(this.index):this.ready=!1}}}])&&s(t.prototype,i),n&&s(t,n),e}();if(ot(Lt.prototype,Tt,Et,It,Ot,St),c.fn){var Nt=c.fn.viewer,qt="viewer";c.fn.viewer=function(r){for(var t=arguments.length,h=new Array(1<t?t-1:0),i=1;i<t;i++)h[i-1]=arguments[i];var l;return this.each(function(t,i){var e=c(i),n="destroy"===r,s=e.data(qt);if(!s){if(n)return;var o=c.extend({},e.data(),c.isPlainObject(r)&&r);s=new Lt(i,o),e.data(qt,s)}if("string"==typeof r){var a=s[r];c.isFunction(a)&&((l=a.apply(s,h))===s&&(l=void 0),n&&e.removeData(qt))}}),void 0!==l?l:this},c.fn.viewer.Constructor=Lt,c.fn.viewer.setDefaults=Lt.setDefaults,c.fn.viewer.noConflict=function(){return c.fn.viewer=Nt,this}}});